<?php if (!defined('BASEPATH'))   exit('No direct script access allowed');
/*
 * render field in listing page 
 * based on type
 */
function dashboard_show_field($field_object , $value  ){
	$CI = & get_instance();
	$CI->lang->load('dashboard');
	/*
	 * check if image 
	 */
	if ( (string)$field_object['type'] == 'image') {
		/*
		 * check for file 
		 * exists
		 */		
		$imageFolder = $CI->config->item('img_upload_path');
		$imageSizeShownHeight = $CI->config->item('img_thumbnail_size_height');
		$fileExist = $imageFolder . 'thumbs/' . $value;
		if ( ( stripos($value, "http://") !== false ) || ( stripos($value, "https://") !== false ) ) {			
			if(((string)$field_object['type'] == 'image')){
				echo '<img src="' . $value . '">';
			}
		}else{
			if ($value != "" && file_exists(FCPATH . $fileExist ) ) {
				echo '<img src="' . CDN_URL . $fileExist . '">';
			}else{
				$fileOriginalImage = $imageFolder . $value;
				if($value != "" && file_exists(FCPATH . $fileOriginalImage ) ){
					echo '<img src="' . CDN_URL . $fileOriginalImage . '" width="'.$imageSizeShownHeight.'">';
				}
			}						
		}

	}elseif ( ( (string)$field_object['type'] ) == 'select') {
		/*
		 * this is for select item 
		 */
		$optionsArray = array ();
		$total_options = ($field_object->count ());
		for($i = 0; $i < $total_options; $i ++) {
			$attributes = $field_object->option [$i]->attributes ();
			$optionsArray [( string ) $attributes ['key']] = dashboard_lang(( string ) $field_object->option [$i]);
		}
		echo $optionsArray [$value];
	}elseif ( ( (string)$field_object['type'] ) == 'datetime') {
		/*
		 * this is for date time
		 */
		if($value != 0){
			echo date( "Y-m-d", $value);
		}		
	}elseif ( ( (string)$field_object['type'] ) == 'adds_city') {
		/*
		 * this is for date tim
		 */

		$response = ''; 

        if  ( $value > '0' ) {

             $CI = &get_instance(); 
        	 $CI->db->select("city.name");
        	 $CI->db->from("city");
        	 $CI->db->join("add_city", "city.id = add_city.city_id");
        	 $CI->db->where("add_city.add_id", $value);

        	 $result = $CI->db->get()->result_array();
             
        	 foreach ( $result as $city ) {
              
                $response = $response." ".$city['name'];   
        	 }
              
        }

        echo $response;
        
		
	} else {
		/*
		 * this is every thing 
		 * else
		 */
		$listing_field_ellipsis_length = config_item('listing_field_ellipsis_length');		
		
		$listing_tooltip = $value; 
		
		if ($listing_field_ellipsis_length <= strlen ( $value )) {
			$listing_tooltip = substr ( $value, 0, $listing_field_ellipsis_length ) . " ...";
		}
		
		echo $listing_tooltip;
	}
}


function listing_multi_select_dropdown($table_name, $col_name,$ref_table,$ref_table_col_name,$multi_select_array=array()) {
    
    $CI = & get_instance();
    $prefix = $CI->config->item("prefix");
    if($ref_table){
        $CI->db->select($prefix.$ref_table."`.".$ref_table_col_name."`");
    }else{
        $CI->db->select($col_name);
    }
    $CI->db->distinct();
    
    if($ref_table){
        $CI->db->join($prefix.$ref_table,$prefix.$ref_table.".id=".$prefix.$table_name."`.".$col_name."`");
    }
    
    if($multi_select_array){
        foreach($multi_select_array as $key=>$value){
	        if($value != $CI->config->item('please_select')){
	            
	            if($ref_table){
	                
	                if($key == $col_name){
	                $CI->db->like($prefix.$ref_table.".".$ref_table_col_name,$value);
	                }else{
	                    $CI->db->like($prefix.$table_name.".".$key,$value);
	                }
	            }else{
	               
    	            if($col_name == "`".$prefix.$table_name."`.`".$key."`"){
    	            $CI->db->like($prefix.$table_name.".".$key,$value);
    	            }else{
    	                
    	                
    	            }
	            
	            }
	            	
	        }
        }  
    }
    $result = $CI->db->get($prefix.$table_name);
    $last_query =  $CI->db->last_query()."<br>";
    return $result->result_array();
    
}


function send_mail($from, $to, $subject, $view_file, $data) {
    $CI = & get_instance();
    $CI->load->library('email');
    

    $subject = html_entity_decode($subject);

    $CI->email->from($from);
    $CI->email->to($to);
    $CI->email->subject($subject);
    $CI->email->set_mailtype('html');
    $sub_view['message'] = $CI->load->view($view_file, $data, TRUE);
    $msg = $CI->load->view("core/email_main_template", $sub_view, TRUE);
    $CI->email->message($msg);
    $CI->email->send();
}