<?php

require_once APPPATH."/core/Dashboard_controller.php";
require_once('stripe/init.php');

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Advertisement extends Dashboard_controller
{

    public $current_class_name;

    function __construct()
    {
        parent::__construct();
        
        $this->current_class_name = strtolower(__CLASS__);
        $this->secret_key = "sk_test_gQ5iwFFIv5s731QwSzH2DoPr";
        $this->publishable_key = "pk_test_ujXzyyqxzaxrQDuCkgWIK0jI";
    }


    public function do_payment () {

        try {

    	$add_id = $this->input->get('add_id');

    	$token  = $_POST['stripeToken'];
	    $email  = $this->input->get('email');

        \Stripe\Stripe::setApiKey( $this->secret_key );

	    $customer = \Stripe\Customer::create(array(
	        'email' => $email,
	        'card'  => $token
	    ));

	    $charge = \Stripe\Charge::create(array(
	        'amount'   => 5000,
	        'currency' => 'usd',
	        'customer' => $customer->id
	    ));

        if ( $charge['paid'] === true  && strlen($charge['id']) > '0' ) {

             $this->db->where("id", $add_id);
             $this->db->update("advertisement", array('is_paid' => 1));

             $data = array();
             $data['add_id'] = $add_id;
             $data['payment_id'] = $charge['id'];
             $data['date'] = date('Y-m-d H:i:s');

             $this->db->insert("payment_history", $data);

             $this->session->set_flashdata ( 'flash_message', 'Payment Successfully Done' );
        }else {

             $this->session->set_flashdata ( 'flash_message', 'Something went wrong.Payment not completed' );
        }


        }catch ( Exception $e ) {

             $this->session->set_flashdata ( 'flash_message', $e->getMessage() );
        }

        redirect(base_url('dbtables/advertisement/edit/'.$add_id));
       
    }


    	
	public function edit($id = 0) {
		$supperUserPermission = $this->dashboard_model->getSupperUserPermission ( );

		$dashboard_helper = Dashboard_main_helper::get_instance ();
		$dashboard_helper->set ( "supper_user", 0 );
		$dashboard_helper->set ( "supper_user_edit_permission", 0 );

		$supper_user_edit_permission =0 ;

		if( is_object($supperUserPermission) ){

			$dashboard_helper->set ( "supper_user", 1 );

			$dashboard_helper->set ( "supper_user_edit_permission", $supperUserPermission->edit );

			$supper_user_edit_permission = $supperUserPermission->edit ;
		}

		$supper_user = $dashboard_helper->get ( "supper_user" );

		$controller_sub_folder = $this->config->item ( 'controller_sub_folder' );



		$this->load->helper ( 'html' );
		$this->load->helper ( 'form' );

		$xmlData = $this->xml_parsing ();

		$tableName = $this->tablePrefix . $xmlData ['name'];

		$primary_key = (string) $xmlData ['primary_key'];
		$created_on = (string) $xmlData ['fieldname_created_on'];
		$modified_on = (string) $xmlData ['fieldname_modified_on'];

		/*
		 * GET user permission data
		 */
		$tableNameFixed = ( string ) $xmlData ["name"];
		$userPermissionDataArray = $this->getUserPermissionDataArray ( $tableNameFixed );


		if (array_key_exists ( "*", $userPermissionDataArray )) {

			$dashboard_helper->set ( "supper_user", 1 );
			$dashboard_helper->set ( "supper_user_edit_permission", $userPermissionDataArray['*'] );
			$supper_user=1;
			$supper_user_edit_permission =  $userPermissionDataArray['*'];
		}



		$data_edit = array ();

		$post = $this->input->post ();

		if (isset ( $post ) && $post) {

			$data = array ();
			$fieldDefaultValue = array();

			foreach ( $xmlData->field as $value ) {
				$fieldName = ( string ) $value ['name'];
				$fieldType = ( string ) $value ['type'];
				$fieldDefaultValue[$fieldName] = ( string ) $value ['default_value'];

				if (isset ( $fieldType ) && (($fieldType == "image") || ($fieldType == "file"))) {

					if (@$_FILES [$fieldName]) {
						$table_name = ( string ) $xmlData ['name'];

						$timestamp = strtotime ( "now" );
						$fileName = preg_replace("/[^a-zA-Z0-9.]+/", "", $_FILES[$fieldName]['name']);

						if($_FILES[$fieldName]['name'] != "" && ($fieldType != "file")){
							$imageExtension = strtolower(end(explode('.', $_FILES[$fieldName]['name']) ) );
							$config_allows_file_type = $this->config->item('img_upload_allowed_types');

							if(! in_array($imageExtension, $config_allows_file_type) ){
								$controller_sub_folder = $this->config->item ( 'controller_sub_folder' );
								$this->session->set_flashdata ( 'flash_message', dashboard_lang('_DADHBOARD_IMAGE_UPLOAD_TYPE_ERROR') );

								$this->session->set_userdata("dashboard_application_message_type", "error");
								redirect ( base_url () . $controller_sub_folder.'/'.$this->current_class_name.'/'.$this->config->item('edit_view').'/'.($id?$id:'') );
								exit();
							}
						}

						if (strlen ( $_FILES [$fieldName] ['name'] ) > 0) {
							$uploadedfileurl = $this->do_upload ( $table_name, $fileName, $fieldName, $timestamp, $fieldType, $id );

							$data = array_merge ( $data, array (
									$fieldName => $uploadedfileurl
							) );
						}
					}
				} else if( isset ( $fieldType ) && ($fieldType == "datetime") ) {

					if ( (is_array ( $userPermissionDataArray ) && count ( $userPermissionDataArray ) > 0) OR ($supper_user)) {
						$fieldName = ( string ) $value ['name'];

						if (array_key_exists ( $fieldName, $userPermissionDataArray )) {

							if($userPermissionDataArray[$fieldName]){

								$data = array_merge ( $data, array (
										$fieldName => (trim( $this->input->post ( $fieldName ) ) == '') ? $fieldDefaultValue[$fieldName] : strtotime(trim( $this->input->post ( $fieldName ) ) )
								) );

							}

						}else if ( $supper_user) {

							if($supper_user_edit_permission){

								$data = array_merge ( $data, array (
										$fieldName => (trim( $this->input->post ( $fieldName ) ) == '') ? $fieldDefaultValue[$fieldName] : strtotime(trim( $this->input->post ( $fieldName ) ) )
								) );
							}
						}
					}


				}else if( isset ( $fieldType ) && ($fieldType == "password") ) {

				    if ( (is_array ( $userPermissionDataArray ) && count ( $userPermissionDataArray ) > 0) OR ($supper_user)) {
				        $fieldName = ( string ) $value ['name'];

				        if (array_key_exists ( $fieldName, $userPermissionDataArray )) {

				            if($userPermissionDataArray[$fieldName]){

				                $data = array_merge ( $data, array (
				                    $fieldName => (trim( $this->input->post ( $fieldName ) ) == '') ? $fieldDefaultValue[$fieldName] : trim( md5($this->input->post ( $fieldName ) ) )
				                ) );

				            }

				        }else if ( $supper_user) {

				            if($supper_user_edit_permission){

				                $data = array_merge ( $data, array (
				                    $fieldName => (trim( $this->input->post ( $fieldName ) ) == '') ? $fieldDefaultValue[$fieldName] : trim( md5($this->input->post ( $fieldName ) ) )
				                ) );
				            }
				        }
				    }



				}
				 else {


					if ( (is_array ( $userPermissionDataArray ) && count ( $userPermissionDataArray ) > 0) OR ($supper_user)) {
						$fieldName = ( string ) $value ['name'];

						if (array_key_exists ( $fieldName, $userPermissionDataArray )) {

							if($userPermissionDataArray[$fieldName]){

								$data = array_merge ( $data, array (
										$fieldName => (trim( $this->input->post ( $fieldName ) ) == '') ? $fieldDefaultValue[$fieldName] : trim( $this->input->post ( $fieldName ) )
								) );

							}

						}else if ( $supper_user) {

							if($supper_user_edit_permission){

								$data = array_merge ( $data, array (
										$fieldName => (trim( $this->input->post ( $fieldName ) ) == '') ? $fieldDefaultValue[$fieldName] : trim( $this->input->post ( $fieldName ) )
								) );
							}
						}
					}


				}
			}




			if ($id > 0) {

				if(@$data [$primary_key]) {

					unset ( $data [$primary_key] );

				}
				if(@$modified_on) {

					$data [$modified_on] = strtotime('now');

				}

				if( count($data) ){


					$this->db->where ( $primary_key, $id );
                    $data['user_id'] = $this->session->userdata('user_id');

                    unset($data['plan_id']);
                    unset($data['is_paid']);
                    unset($data['total_viewed']);
                    unset($data['total_clicked']);
                    unset($data['ads_city']);

					$result = $this->db->update ( $tableName, $data );

					$this->db->where("id", $id);
                    $this->db->update("advertisement", array('ads_city' => $id ));

					if ($result) {

                        $this->insert_cities ( $id, $_POST['city'] );
                         
						$message_success = dashboard_lang ( "SUCCESSFULL_EDIT_MESSAGE" );

						$this->session->set_flashdata ( 'flash_message', $message_success );


					}
				}


				$edit_path = $controller_sub_folder . '/' . $this->current_class_name . "/".$this->config->item('edit_view').'/'.$id;

				redirect ( base_url () . $edit_path );

			} else {

				unset ( $data [$primary_key] );
				if(@$created_on) {

					$data [$created_on] = strtotime('now');
					$data [$modified_on] = strtotime('now');

				}

				$edit_path = $controller_sub_folder . '/' . $this->current_class_name . "/".$this->config->item('edit_view') ;

				if(count($data)){

                    $data['user_id'] = $this->session->userdata('user_id');
                    
                    $this->db->select("total_count");
                    $plan_details = $this->db->get_where("plan_types", array(
                        "id" => $data['plan_id']
                    ))->result_array();

                    if ( sizeof($plan_details) > '0' ) {

                    	 $data['view_remains'] = $plan_details[0]['total_count'];
                    }

                    unset($data['is_paid']);

                    $data['total_viewed'] = 0;
                    $data['total_clicked'] = 0;

					$result = $this->db->insert ( $tableName, $data );

					if ($result) {

						$id = $this->db->insert_id();

                        $this->db->where("id", $id);
                        $this->db->update("advertisement", array('ads_city' => $id ));

                        $this->insert_cities ( $id, $_POST['city'] );

               			$edit_path .= '/'.$id;

						$message_success = dashboard_lang ( "SUCCESSFULL_INSERT_MESSAGE" );

						$this->session->set_flashdata ( 'flash_message', $message_success );

					}

				}



				redirect ( base_url () . $edit_path );
			}
		}

		if ($id > 0) {

			$this->db->select ( "*" );
			$this->db->where ( $primary_key, $id );
			$queryEditData = $this->db->get ( $tableName );

			if ($queryEditData->num_rows () > 0) {

				foreach ( $queryEditData->result () as $row ) {

					foreach ( $xmlData->field as $value ) {

						$fieldName = ( string ) $value ['name'];

						$data_edit [$fieldName] = $row->$fieldName;
					}
				}
			}

			$data ['data_edit'] = $data_edit;
			$data ['id'] = $id;
		}

		$viewPathAction = $this->current_class_name . "/" . $this->config->item ( "editing_action_file_name" );

		if (! $this->view_exists ( $viewPathAction )) {
			$viewPathAction = $this->getCoreViewPath ( 'edit_action' );
		}

		$view_additional = $this->current_class_name . "/" . $this->config->item ( "edit_form_bottom");
		if($this->view_exists($view_additional)){
			$data['view_additional'] = $view_additional;
		}

		$data ['viewPathAction'] = $viewPathAction;
		$data ['edit_field_array'] = $userPermissionDataArray;
		$data ['data_load'] = $xmlData;

		$data ['form_name'] = $this->current_class_name;
		$data ['class_name'] = $this->current_class_name;
		$data ['delete_method'] = $this->config->item ( "delete_view" );
		$data ['copy_method'] = $this->config->item ( "copy_view" );
		$data ['edit_path'] = $controller_sub_folder . '/' . $this->current_class_name . "/" . $this->config->item ( "edit_view" );
		$data ['listing_path'] = $this->current_class_name . "/" . $this->config->item ( "listing_view" );
        $data ['publishable_key'] = $this->publishable_key; 

		$viewPath = $this->current_class_name . "/" . $this->config->item ( "edit_view" );
		if (! $this->view_exists ( $viewPath )) {
			$viewPath = $this->getCoreViewPath ( 'edit' );
		}

		$this->template->write_view ( 'content', $viewPath, $data );
		$this->template->render ();
	}

    public function insert_cities ( $add_id, $cities ) {

        $this->db->query("DELETE FROM `add_city` WHERE add_id='$add_id'")  ;

        foreach ( $cities as $city ) {

        	$data['add_id'] = $add_id;
        	$data['city_id'] = $city;

        	$this->db->insert("add_city", $data);
        }
    }

   
}
