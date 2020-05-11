<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');

class Seller extends CI_Controller {


	public function signin ()
	{
		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}


		$result = $this->db->get_where("seller", array(
            "email" => trim($request['email']),
            "password" => trim($request['password'])
		))->result_array();

		if ( sizeof($result) > '0' ) {

            $response = array( "status" => 1, "data" => $result,  "msg" => "Login Successfully Done.");
		}else {
            $response = array( "status" => 0,  "msg" => "Email or Password Do not Match");
		}

		echo json_encode( $response );
		exit;
	}


    public function signup ()
    {
        $request = json_decode (file_get_contents('php://input'), TRUE);

        if (json_last_error()) {

            echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
            exit;
        }


        $result = $this->db->get_where("seller", array(
            "email" => trim($request['email']),
        ))->result_array();

        if ( sizeof($result) > '0' && strlen($request['email']) > '0' && $request['id'] == '0') {

            $response = array( "status" => 0,  "msg" => "Email already Exists");
        }else {

            foreach ( $request as $key => $value ) {
                $data[$key] = $request[$key];
            }


            if ( $request['id'] == '0' ) {

                $day = intval($data["signup_day"]);

                $data["valid"] = time() + $day*86400;

                unset( $data["signup_day"] );

                $this->db->insert("seller", $data);
                $id = $this->db->insert_id();

                $msg = "SignUp Successfully Done";


            } else {

                $this->db->where("id", $request['id']);
                $this->db->update("seller", $data);

                $id = $request['id'];
                $msg = "seller Successfully Updated";
            }

            $userData = $this->get_user_details ( $id );

            $response = array( "status" => 1, "data" => $userData,  "msg" => $msg);
            
        }

        echo json_encode( $response );
        exit;
    }
    

    public function get_user_details ( $Id ) {

        $result = $this->db->get_where("seller", array(
            "id" => $Id
        ))->result_array();

        if ( sizeof($result) > '0' ) {
            return $result;
        }else {
            return [];
        }
    }
}
