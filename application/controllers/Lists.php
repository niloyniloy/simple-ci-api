<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');

class Lists extends CI_Controller {
    

    public function details ( $table = "" ) {

        if ( strlen($table) == '0' ) {
            echo json_encode( ["status" => 0, "msg" => "no table name given"] );
            exit;
        }
        $this->db->order_by("id", "asc");
        $result = $this->db->get($table)->result_array();

        $response = array( "status" => 1, "data" => $result );

        echo json_encode( $response );
        exit;
    }


    public function states () {

        $result = $this->db->get("country")->result_array();

        for ( $count = 0; $count < sizeof($result); $count++ ) {

            $result[$count]["states"] = $this->db->get_where( "states", [ "country_id" => $result[$count]["id"] ] )->result_array();
        }

        $response = array( "status" => 1, "data" => $result );

        echo json_encode( $response );
    }


    public function bathroom() {
	 $this->db->order_by("id", "asc");
        $result = $this->db->get("bathroom")->result_array();

        $response = array( "status" => 1, "data" => $result );

        echo json_encode( $response );

    }

    public function bedroom () {

 	$this->db->order_by("id", "asc");
        $result = $this->db->get("bedroom")->result_array();

        $response = array( "status" => 1, "data" => $result );

        echo json_encode( $response );

    }


    public function purchase_type () {

	 $this->db->order_by("id", "asc");
        $result = $this->db->get("purchase_type")->result_array();

        $response = array( "status" => 1, "data" => $result );

        echo json_encode( $response );

    }


}
