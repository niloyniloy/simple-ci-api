<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');

class Category extends CI_Controller {


	public function all()
	{
		
		$result = $this->db->get("restaurant_category")->result_array();

		echo json_encode(array("status" => 1 , "data" => $result ));
		exit;
	}
	
}