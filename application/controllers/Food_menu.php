<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');

class Food_menu extends CI_Controller {


	public function all()
	{
		
		$result = $this->db->get("food_category")->result_array();

		echo json_encode(array("status" => 1 , "data" => $result ));
		exit;
	}


	public function getRestaurantMenu( $restId ) {
        
       $this->db->select("food_items.*,food_category.name as category_name");
       $this->db->from("food_items");
       $this->db->join("food_category", "food_items.menu_id = food_category.id");
	   $this->db->where("food_items.restaurant_id", $restId);

	   $result = $this->db->get()->result_array();

	   for($count = 0; $count < sizeof($result); $count++) {

	   	   $result[$count]['images'] = $this->getImages( $result[$count]['id'] );

	   	   if ( $result[$count]['offer_price'] > '0' ) {
	   	   	   $result[$count]['has_offer_price'] = 1;
	   	   }else {
	   	   	   $result[$count]['has_offer_price'] = 0; 
	   	   }
	   }

	   echo json_encode(array("status" => "success" , "data" => $result ));

	}

	public function getImages( $rest_menu_id ) {

        $this->db->where("item_id", $rest_menu_id);
        return $this->db->get("menu_images")->result_array();
	}


	public function delete ( $id = 0 ) {
        
       $this->db->where("id", $id);
       $this->db->delete("food_items");

       echo json_encode(array("status" => "success" , "message" => "Food item successfully deleted" ));

	}
	
}