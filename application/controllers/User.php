<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');

class User extends CI_Controller {


	public function details ($id = 0)
	{
		$result = $this->db->get_where("users", array(
            "id" => $id
		))->result_array();

		if ( sizeof($result) > '0' ) {

            $response = array( "status" => 1, "user_data" => $result );
		}else {
            $response = array( "status" => 0,  "msg" => "Wrong User Id");
		}

		echo json_encode( $response );
		exit;
	}


	public function update()
	{
		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}


		$result = $this->db->get_where("users", array(
            "id" => trim($request['id']),

		))->result_array();

		if ( sizeof($result) == '0' ) {

            $response = array( "status" => 0,  "msg" => "User Id Do Not Exists" );
		}else {

			$data['first_name'] = $request['first_name'];
			$data['last_name'] = $request['last_name'];
			$data['email'] = $request['email'];
			$data['password'] = $request['password'];
			$data['gender'] = $request['gender'];
			$data['modified_at'] = date("Y-m-d H:i:s");
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
            $data['city'] = $request['city'];
            $data['country'] = $request['country'];
            $data['date_of_birth'] = $request['date_of_birth'];

            if ( strlen($data['first_name']) == '0' || strlen($data['last_name']) == '0' ) {

                $response = array( "status" => 0,  "msg" => "Please Enter Full Name");
            }else {

            	$this->db->where("id", $request['id'] );
            	$this->db->update("users", $data );

                $userData = $this->get_user_details ( $request['id'] );

            	$response = array( "status" => 1, "user_data" => $userData,  "msg" => "User Data Sucessfully Done");
            }
		}

		echo json_encode( $response );
		exit;
	}

	public function get_user_details ( $userId ) {

        return $this->db->get_where("users", array(
            "id" => $userId
        ))->result_array();
	}
}
