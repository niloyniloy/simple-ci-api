<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');

class Restaurants extends CI_Controller {


	public function all()
	{
		
		$result = $this->db->get("food_category")->result_array();

		echo json_encode(array("status" => 1 , "data" => $result ));
		exit;
	}


	public function search() {

		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}

		$userLat = $request['lat'];
		$userLng = $request['lng'];
		$category_id = $request['category_id'];
		$restaurant_category_id = $request['restaurant_category_id'];

		$this->db->select("restaurants.*,restaurant_menus.menu_id,restaurant_category.name as restaurant_category_name");
		$this->db->from("restaurants");
		$this->db->join("restaurant_menus", "restaurant_menus.restaurant_id = restaurants.id", "left");
		$this->db->join("restaurant_category", "restaurants.restaurant_category_id = restaurant_category.id", "left");
		
		if ( $category_id != '0' ) {
			$this->db->where("restaurant_menus.menu_id", $category_id);
		}

		if ( $restaurant_category_id != '0' ) {
			$this->db->where("restaurants.restaurant_category_id", $restaurant_category_id);
		}
		

		$result = $this->db->get()->result_array();

		for ( $count = 0; $count < sizeof($result); $count++ ) {

            $result[$count]['distance'] = $this->distance( $result[$count]['lat'], $result[$count]['lng'], $userLat, $userLng);
		    $result[$count]['food_category_details'] = $this->getRestaurantMenu( $result[$count]['id'] );

		}


		$sort_col = array();

		foreach ($result as $key=> $row) {
		  $sort_col[$key] = $row['distance'];
		}

		array_multisort($sort_col, SORT_ASC, $result);

		$response = array( "status" => 1, "data" => $result);
		         
		echo json_encode($response);
	}


	public function menuDetails ( $restId = 0 ) {

       $this->db->select("food_category.*,restaurant_menus.id as menu_rest_id");
       $this->db->from("food_category");
       $this->db->join("restaurant_menus", "restaurant_menus.menu_id = food_category.id");
	   $this->db->where("restaurant_menus.restaurant_id", $restId);

	   $result = $this->db->get()->result_array();

	   for ( $count = 0; $count < sizeof($result); $count++ ) {

	   		$result[$count]['images'] = $this->getImages( $result[$count]['menu_rest_id'] );
	   		$result[$count]['item_details'] = $this->getFoodItems( $result[$count]['menu_id'] );
	   }

	   return $result;
	}


	public function getFoodItems($menu_id) {

		$this->db->where("menu_id", $menu_id);  
        return $this->db->get("food_items")->result_array();
	}

	public function list_via_lat_lng() {

        $request = json_decode (file_get_contents('php://input'), TRUE);
        $userLat = $request['lat'];
		$userLng = $request['lng'];

        $result = $this->db->get("restaurants")->result_array();

        for ( $count = 0; $count < sizeof($result); $count++ ) {

            $result[$count]['distance'] = $this->distance( $result[$count]['lat'], $result[$count]['lng'], $userLat, $userLng);
		    $result[$count]['item_details'] = $this->getRestaurantMenu( $result[$count]['id'] );

		}

		$sort_col = array();

		foreach ($result as $key=> $row) {
		  $sort_col[$key] = $row['distance'];
		}

		array_multisort($sort_col, SORT_ASC, $result);

		$response = array( "status" => 1, "data" => $result);
		
		echo json_encode($response);
	}

	public function getRestaurantMenu( $restId ) {
        
	   $allCategory = $this->get_category( $restId  );	

	   for ( $count = 0; $count < sizeof($allCategory); $count++ ) {

	   	    $allCategory[$count]["menu_details"] = $this->getRestCatDetails( $allCategory[$count]["id"], $restId);
	   }

	   return $allCategory;

	}


	public function getRestCatDetails( $catId, $restId ) {

		$this->db->select("*");
        $this->db->from("food_items");
        $this->db->where("food_items.menu_id", $catId);
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

	    return $result;
	}

	public function get_category( $restId  ) {

       $this->db->select("distinct(food_category.id) as id, food_category.name");
       $this->db->from("food_items");
       $this->db->join("food_category", "food_items.menu_id = food_category.id");
	   $this->db->where("food_items.restaurant_id", $restId);

	   return $this->db->get()->result_array();


	}

	public function getImages( $rest_menu_id ) {

        $this->db->where("item_id", $rest_menu_id);
        return $this->db->get("menu_images")->result_array();
	}

	public function restaurantDetails( $pId ) {

        return $this->db->get_where("restaurants", array(
               "id" => $pId
        ))->result_array();
	}


	function distance($lat1, $lon1, $lat2, $lon2) {

		  $theta = $lon1 - $lon2;
		  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		  $dist = acos($dist);
		  $dist = rad2deg($dist);
		  $miles = $dist * 60 * 1.1515;
		  
		  return $miles;
    }


	public function addFoodItem()
	{
		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}

$this->db->insert("log", ["text" => json_encode($request)]);

		$data = [];

        foreach ( $request as $key => $value ) {
            $data[$key] = $value;
        } 

            unset($data['id']);
            unset($data['images']);

            if ( sizeof($data) > '0' ) {
	            if ( $request['id'] == '0' ) {
	                
	                $this->db->insert("food_items", $data);
	            	$itemId = $this->db->insert_id();
	            }else {

	            	$this->db->where("id", $request['id'] );
	            	$this->db->update("food_items", $data);

	            	$itemId = $request['id'];
	            }
            }

            $this->update_menu_image ( $itemId, $request );
            $this->updateMenuImages ( $itemId, $request["images"] );

            $userData = $this->get_items_details ( $itemId );

            $response = array( "status" => 1, "data" => $userData);
            

		echo json_encode( $response );
		exit;
	}


	public function update_menu_image( $insertId, $request ) {

        if ( strlen($request['default_image']) > '0' ) { 

            $imageData =  explode(",", $request['default_image']);
            
			$fileDetailsInfo = explode(';', $imageData[0]);
            $fileType = explode('image/', $fileDetailsInfo[0])[1];
			$data = base64_decode($imageData[1]);
            
            mkdir("uploads/single_menu_image/$insertId");

            $fileName = "uploads/single_menu_image/$insertId/$insertId.$fileType"; 
 
			file_put_contents($fileName, $data);

			if ( file_exists($fileName) ) {

                $url = str_replace("index.php/", "", base_url());

				$this->db->where("id", $insertId);
				$this->db->update("food_items", array( "menu_image" => $url.$fileName ));
			}

        }   

	}

	public function updateMenuImage() {

		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}

		$itemId = $request["id"];

		$this->updateMenuImages ( $itemId, $request['images'] );
		$userData = $this->get_items_details ( $itemId );

        $response = array( "status" => 1, "data" => $userData);

		echo json_encode( $response );
		exit;
	}

	public function updateMenuImages( $insertId, $images ) {

         for ( $count = 0; $count < sizeof($images); $count++ ) { 

            $imageData =  explode(",", $images[$count]);
            
			$fileDetailsInfo = explode(';', $imageData[0]);
            $fileType = explode('image/', $fileDetailsInfo[0])[1];
			$data = base64_decode($imageData[1]);
            
            mkdir("uploads/menu_image/$insertId");

            $fileName = "uploads/menu_image/$insertId/$insertId.$fileType"; 
 
			file_put_contents($fileName, $data);

			if ( file_exists($fileName) ) {

                $url = str_replace("index.php/", "", base_url());

				$insertMenu['item_id'] = $insertId;
				$insertMenu['images'] = $url.$fileName;

				$this->db->insert("menu_images", $insertMenu);
			}

        }   

	}


	public function get_items_details ( $Id ) {

        $result = $this->db->get_where("food_items", array(
            "id" => $Id
        ))->result_array();

        if ( sizeof($result) > '0' ) {

        	$result[0]['category_name'] = $this->getCatname( $result[0]['menu_id']  );
        	$result[0]['images'] = $this->getImages( $Id );

        }

        return $result;
	}
	public function getCatname( $cat_id ) {

        $result = $this->db->get_where("food_category",  [
           "id" => $cat_id
        ])->result_array();

        if ( sizeof($result) > '0' ) {

        	 return $result[0]['name'];
        }else {
        	return '';
        }
	}

	public function food_item_delete( $id ) {

       $this->db->where("id", $id);
       $this->db->delete("food_items");  

       $response = array( "status" => 1, "msg" => "Food Item Deleted"); 

	}


	
}