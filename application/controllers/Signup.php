<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');

class Signup extends CI_Controller {


	public function index()
	{
		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}


		$result = $this->db->get_where("restaurants", array(
            "email" => trim($request['email']),
		))->result_array();

		if ( sizeof($result) > '0' && strlen($request['email']) > '0' && $request['id'] == '0') {

            $response = array( "status" => 0,  "msg" => "Email already Exists");
		}else {

            foreach ( $request as $key => $value ) {
            	$data[$key] = $request[$key];
            }


            if ( $request['id'] == '0' ) {

                $this->db->insert("restaurants", $data);
            	$id = $this->db->insert_id();
                $msg = "SignUp Successfully Done";

            } else {

                $this->db->where("id", $request['id']);
                $this->db->update("restaurants", $data);
                $id = $request['id'];
                $msg = "Restaurants Successfully Updated";
            }
          

            $this->update_profile_image ( $id, $request );

            $userData = $this->get_user_details ( $id );

            $userData[0]['is_address_added'] = $this->is_address_added( $userData[0]['id'] );

            $response = array( "status" => 1, "image" => $request['image'] ,"data" => $userData,  "msg" => $msg);
            
		}

		echo json_encode( $response );
		exit;
	}
	

	public function update_profile_image( $insertId, $request ) {

        if ( strlen($request['image']) > '0' ) { 

            $imageData =  explode(",", $request['image']);
            
			$fileDetailsInfo = explode(';', $imageData[0]);
            $fileType = explode('image/', $fileDetailsInfo[0])[1];
			$data = base64_decode($imageData[1]);
            
            mkdir("uploads/restaurant_image/$insertId");

            $fileName = "uploads/restaurant_image/$insertId/$insertId.$fileType"; 
 
			file_put_contents($fileName, $data);

			if ( file_exists($fileName) ) {

                $url = str_replace("index.php/", "", base_url());

				$this->db->where("id", $insertId);
				$this->db->update("restaurants", array( "image" => $url.$fileName ));
			}

        }   

	}


	public function get_user_details ( $Id ) {

        $result = $this->db->get_where("restaurants", array(
            "id" => $Id
        ))->result_array();

        if ( sizeof($result) > '0' ) {
            $result[0]["restaurant_category_name"] = $this->get_restaurant_category( $result[0]["restaurant_category_id"] );
        
            return $result;
        }else {
            return [];
        }
	}

    public function get_restaurant_category( $id ) {

       $result = $this->db->get_where("restaurant_category", [
                    "id" => $id 
        ])->result_array();

        if ( sizeof($result) > '0' ) {
            return $result[0]["name"];
        }else {
            return "";
        }

    }

	public function register_device() {

		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}

		$deviceId = $request['deviceId'];
        $device_unique_id = $request['device_unique_id'];
        $device_type = $request['device_type'];
        $user_id = intval($request['user_id']);
        
        $result = $this->db->get_where("app_user", array(
            "unique_id" => $device_unique_id
        ))->result_array();

        if ( sizeof( $result ) == '0' ) {

            $this->db->insert("app_user", array("user_id" => $user_id , "device_id" => $deviceId, "unique_id" => $device_unique_id, "type" => $device_type )); 
            $result = $this->db->get_where("app_user", array("id" => $this->db->insert_id()))->result_array();
         
        }else {

            $this->db->where("unique_id", $device_unique_id);
            $this->db->update("app_user", ["device_id" => $deviceId, "user_id" => $user_id ]);

            $result = $this->db->get_where("app_user", array("unique_id" => $device_unique_id ))->result_array();
        }

        $response = array("status" => "success", "data" => $result); 

        echo json_encode($response);

	}
    public function remove_device() {

        $request = json_decode (file_get_contents('php://input'), TRUE);

        if (json_last_error()) {

            echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
            exit;
        }

        $device_unique_id = $request['device_unique_id'];

        $this->db->where("unique_id", $device_unique_id);
        $this->db->delete("app_user");

        $response = array("status" => "success", "msg" => "unique id deleted"); 

        echo json_encode($response);
    }

	public function add_location() {

		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}


		    foreach ( $request as $key => $value ) {
            	$data[$key] = $request[$key];
            }

            if ( $data['user_id'] == '0' )  {

                $data['user_id'] = $this->get_app_user_id( $data['name'], $data['phone'], $data['email']);
            }

            unset( $data['name'] );
            unset( $data['phone'] );
            unset( $data['email'] );  

            $this->db->insert("user_location", $data);
            $id = $this->db->insert_id();

            $result = $this->get_location_details($id);

            $response = array("status" => "success", "data" => $result);

            echo json_encode($response);
	}

    public function get_app_user_id( $name, $phone, $email ) {
        
        $data['name'] = $name;
        $data['phone'] = $phone;
        $data['email'] = $email;
        $data['device_id'] = '';

        $this->db->insert("app_user", $data);

        return $this->db->insert_id();

    }


	public function get_location_details ( $id ) {

        $this->db->where("id", $id);
        return $this->db->get("user_location")->result_array();
	}

    public function delete_location ( $id ) {

        $this->db->where("id", $id);
        $this->db->delete("user_location");

        $response = array("status" => "success", "msg" => "location deleted");

        echo json_encode($response);
    }

	public function all_user_location ( $user_id ) {

        $this->db->where("user_id", $user_id);
        $result = $this->db->get("user_location")->result_array();

        $response = array("status" => "success", "data" => $result);

        echo json_encode($response);
	}

    public function is_address_added( $userId ) {

        $result = $this->db->get_where("user_location", array(
            "user_id" => $userId
        ))->result_array();

        if ( sizeof($result) > '0' ) {

            return 1;
        }else{
            return 0;
        }
    }

    public function last_address_added( $userId ) {

        $this->db->order_by("id", "desc");
        $this->db->limit(1);

        $result = $this->db->get_where("user_location", array(
            "user_id" => $userId
        ))->result_array();

       return $result;
    }


}