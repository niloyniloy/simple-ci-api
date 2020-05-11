<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');

class Properties extends CI_Controller {
	public function search  ()
	{
        $postParams = json_decode (file_get_contents('php://input'), TRUE);
        $price_min = 0;
        $price_max = 0;
        
        $this->db->insert("request_log", ["log" => json_encode($postParams)]);

        foreach ( $postParams as $key => $value ) {
        	if ( $key == 'price_min' ) {

        		$price_min = $value;
        	}else if ( $key == 'price_max' ) {
        		$price_max = $value;
        	}else if ($key == 'zipcode') {
                $this->db->where ("zipcode LIKE '%".$value."%'");
            }else {
        		if ( $value == '' || $value == '0' || $value == 'dont_matter' || $value == 'Please select') {

        		}else {
        			$this->db->where ( $key, $value );
        		}
        		
        	}
            
        }

        $result = $this->db->get("properties")->result_array();
        $this->db->insert("request_log", ["log" => $this->db->last_query()]);

        $response = array( "status" => 1, "data" => $result );

		echo json_encode( $response );
		exit;
	}


    public function add ()
    {
        $request = json_decode (file_get_contents('php://input'), TRUE);

        if (json_last_error()) {

            echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
            exit;
        }

        foreach ( $request as $key => $value ) {
                $data[$key] = $request[$key];
        }


        if ( $request['id'] == '0' ) {

                $this->db->insert("properties", $data);
                $id = $this->db->insert_id();
                $msg = "properties Successfully added";

        } else {

                $this->db->where("id", $request['id']);
                $this->db->update("properties", $data);

                $id = $request['id'];
                $msg = "properties Successfully Updated";
        }

        $userData = $this->get_properties_details ( $id );
        $response = array( "status" => 1, "data" => $userData,  "msg" => $msg);

        echo json_encode( $response );
        exit;
    }
    

    public function get_properties_details ( $Id ) {

        $result = $this->db->get_where("properties", array(
            "id" => $Id
        ))->result_array();

        if ( sizeof($result) > '0' ) {
            return $result;
        }else {
            return [];
        }
    }

    public function lists ( $buyerID = 0 ) {

        $result = $this->db->get_where("properties", array(
            "buyer_id" => $buyerID
        ))->result_array();

        $response = array( "status" => 1, "data" => $result );

        echo json_encode( $response );
        exit;

    }


    public function deleteProperty ( $id = 0 ) {

        $this->db->where("id", $id);
        $this->db->delete ("properties");

        $response = array( "status" => 1, "msg" => "Property Deleted" );

        echo json_encode( $response );
        exit;
    }
}
