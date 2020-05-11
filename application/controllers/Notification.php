<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');

class Notification extends CI_Controller {


	public function send1 () {

		define( 'API_ACCESS_KEY', 'AIzaSyAfzeHNYjfz842uMxHSJFTTMypwkCepjvw' );

		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}


		$rest = $request["restaurant_id"];
		$menu_details = $request["menu_details"];


		$ids = [];

		$this->db->select("restaurants.*,restaurant_menus.menu_id,restaurant_category.name as restaurant_category_name");
		$this->db->from("restaurants");
		$this->db->join("restaurant_menus", "restaurant_menus.restaurant_id = restaurants.id", "left");
		$this->db->join("restaurant_category", "restaurants.restaurant_category_id = restaurant_category.id", "left");

		$this->db->where("restaurants.id", $rest);


		$result = $this->db->get()->result_array();

		for ( $count = 0; $count < sizeof($result); $count++ ) {

             $result[$count]['distance'] = $this->distance( $result[$count]['lat'], $result[$count]['lng'], 0, 0);
		    //$result[$count]['menu_details'] = $this->getRestaurantMenu( $result[$count]['id'] );

		}

		foreach ($menu_details as $details) {
			
			$ids []  = $details["id"];
			$percentage[$details["id"]] = $details["percentage"];
		}

     
		$offer_details = $this->getRestaurantMenuDetails ( $ids );
		$responseOfferDetails = $this->getNewRestaurantMenuDetails( $ids );

		$msgs = '';
		for ( $count = 0; $count < sizeof($offer_details); $count++ ) {

			$offer_details[$count]["offer_price"] = $offer_details[$count]["price"] - ( $percentage[$offer_details[$count]["id"]]* $offer_details[$count]["price"])/100;
			$offer_details[$count]["offer_title"] = $percentage[$offer_details[$count]["id"]]." % discount in ".$offer_details[$count]["name"];
 			$msgs .= $offer_details[$count]["offer_title"]." ";
 		}


		$result[0]['offer_details'] = $responseOfferDetails;

        $this->db->select("*");
        $this->db->from("app_user");
        $this->db->where("device_id !=''");

 		$resultss = $this->db->get()->result_array();


 		foreach ($resultss as $items ) {

			$registrationIds = array( $items["device_id"] );

			$msg = array
			(
				'message' 	=>  ["data" => $result[0], "status" => 1],
				'title'		=> $msgs,
				'subtitle'	=> 'Tap for more details',
				'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
				'vibrate'	=> 1,
				'sound'		=> 1,
				'largeIcon'	=> 'large_icon',
				'smallIcon'	=> 'small_icon'
			);
			$fields = array
			(
				'registration_ids' 	=> $registrationIds,
				'data'			=> $msg
			);
			 
			$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			 
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$results = curl_exec($ch );
			curl_close( $ch );
			//echo $results;
		}

		echo json_encode( $msg["message"] );
	}


	function distance($lat1, $lon1, $lat2, $lon2) {

		  $theta = $lon1 - $lon2;
		  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		  $dist = acos($dist);
		  $dist = rad2deg($dist);
		  $miles = $dist * 60 * 1.1515;
		  
		  return $miles;
    }

	public function getRestaurantMenu( $restId ) {
        
       $this->db->select("food_items.*,food_category.name as category_name");
       $this->db->from("food_items");
       $this->db->join("food_category", "food_items.menu_id = food_category.id");
	   $this->db->where("food_items.restaurant_id", $restId);

	   $result = $this->db->get()->result_array();

	   for($count = 0; $count < sizeof($result); $count++) {

	   	   $result[$count]['images'] = $this->getImages( $result[$count]['id'] );
	   }

	   return $result;

	}


	public function getRestaurantMenuDetails ( $menuId ) {
        
       $this->db->select("food_items.*,food_category.name as category_name");
       $this->db->from("food_items");
       $this->db->join("food_category", "food_items.menu_id = food_category.id");
       if ( sizeof($menuId) > '0' ) {
         	$this->db->where("food_items.id IN (".implode(',', $menuId).")");
       }
	   

	   $result = $this->db->get()->result_array();

	   for($count = 0; $count < sizeof($result); $count++) {

	   	   $result[$count]['images'] = $this->getImages( $result[$count]['id'] );
	   }

	   return $result;

	}


	public function getNewRestaurantMenuDetails ( $menuId, $percentage = [] ) {
        
       $this->db->select("distinct(food_category.id) as id, food_category.name");
       $this->db->from("food_items");
       $this->db->join("food_category", "food_items.menu_id = food_category.id");
       if ( sizeof($menuId) > '0' ) {
         	$this->db->where("food_items.id IN (".implode(',', $menuId).")");
       }
	   

	   $result = $this->db->get()->result_array();

	   for($count = 0; $count < sizeof($result); $count++) {

	   	   $result[$count]['menu_details'] = $this->getDiscountItemsDetils( $result[$count]['id'], $menuId );
	   	   for ( $count2 = 0; $count2 < sizeof($result[$count]['menu_details']); $count2++ ) {

				$result[$count]['menu_details'][$count2]["offer_price"] = intval($result[$count]['menu_details'][$count2]["price"] - ( $percentage[$result[$count]['menu_details'][$count2]["id"]]* $result[$count]['menu_details'][$count2]["price"])/100);
				$result[$count]['menu_details'][$count2]["offer_title"] = $percentage[$result[$count]['menu_details'][$count2]["id"]]." % discount in ".$result[$count]['menu_details'][$count2]["name"];
			 	$result[$count]['menu_details'][$count2]["percentage"] = $percentage[$result[$count]['menu_details'][$count2]["id"]];
			}

	   }

	   return $result;

	}


	public function getDiscountItemsDetils ( $menuCatId, $menuId ) {
        
       $this->db->select("food_items.*");
       $this->db->from("food_items");
       $this->db->join("food_category", "food_items.menu_id = food_category.id");
       $this->db->where("food_category.id", $menuCatId);

       if ( sizeof($menuId) > '0' ) {
         	$this->db->where("food_items.id IN (".implode(',', $menuId).")");
       }
	   

	   $result = $this->db->get()->result_array();

	   for($count = 0; $count < sizeof($result); $count++) {

	   	   $result[$count]['images'] = $this->getImages( $result[$count]['id'] );
	   }

	   return $result;

	}


	public function getImages( $rest_menu_id ) {

        $this->db->where("item_id", $rest_menu_id);
        return $this->db->get("menu_images")->result_array();
	}


    public function get_all_users_notification( $user_id ) {

    	$user_id = intval($user_id);

    	$this->db->order_by("datetime", "desc");

    	$result = $this->db->get_where("notification_lists",[
    		"app_user_id" => $user_id
    	])->result_array();

    	for  ( $count = 0; $count < sizeof($result); $count++ ) {


    		$result[$count]["details"] = $this->get_push_restaurant_menus_details( $result[$count]["id"] );
    		$result[$count]["notification_details"] = json_decode( $result[$count]["notification_details"], true );
    	}

    	$response = ["status" => "success", "data" => $result];

    	echo json_encode($response);

    }

    public function get_push_restaurant_menus_details ( $notification_id ) {

    	$notificationLists = $this->db->get_where("notification_lists", [
    		"id" => $notification_id
    	])->result_array(); 

    	if ( sizeof($notificationLists) > '0' )  {

            $restaurant_id = $notificationLists[0]["restaurant_id"];
            $result = $this->getRestAurantDetails( $restaurant_id );

            $notifications = json_decode( $notificationLists[0]["notification_details"], true);

            $result[0]['distance'] = $this->distance( $result[0]['lat'], $result[0]['lng'], 0, 0);

            foreach ($notifications as $details) {
			
				$ids []  = $details["id"];
				$percentage[$details["id"]] = $details["percentage"];
			}

				     
			$offer_details = $this->getNewRestaurantMenuDetails ( $ids, $percentage );

			$result[0]['offer_details'] = $offer_details;

			return $result[0];

    	}else {
    		$result = [];
    	}  

    	return $result;

    }
    public function get_push_restaurant_menus ( $notification_id ) {

    	$notificationLists = $this->db->get_where("notification_lists", [
    		"id" => $notification_id
    	])->result_array(); 

    	if ( sizeof($notificationLists) > '0' )  {

            $restaurant_id = $notificationLists[0]["restaurant_id"];
            $result = $this->getRestAurantDetails( $restaurant_id );

            $notifications = json_decode( $notificationLists[0]["notification_details"], true);

            $result[0]['distance'] = $this->distance( $result[0]['lat'], $result[0]['lng'], 0, 0);

            foreach ($notifications as $details) {
			
				$ids []  = $details["id"];
				$percentage[$details["id"]] = $details["percentage"];
			}

				     
			$offer_details = $this->getNewRestaurantMenuDetails ( $ids, $percentage );
			for ( $count = 0; $count < sizeof($offer_details); $count++ ) {

				$offer_details[$count]["offer_price"] = $offer_details[$count]["price"] - ( $percentage[$offer_details[$count]["id"]]* $offer_details[$count]["price"])/100;
				$offer_details[$count]["offer_title"] = $percentage[$offer_details[$count]["id"]]." % discount in ".$offer_details[$count]["name"];
			}


			$result[0]['offer_details'] = $offer_details;

    	}else {
    		$result = [];
    	}  

    	echo  json_encode( ["status" => "success", "data" => $result ] );

    }


    public function getRestAurantDetails ( $rest ) {


    	$this->db->select("restaurants.*,restaurant_menus.menu_id,restaurant_category.name as restaurant_category_name");
		$this->db->from("restaurants");
		$this->db->join("restaurant_menus", "restaurant_menus.restaurant_id = restaurants.id", "left");
		$this->db->join("restaurant_category", "restaurants.restaurant_category_id = restaurant_category.id", "left");

		$this->db->where("restaurants.id", $rest);

		return $this->db->get()->result_array();
    }



	public function send_push () {

		define( 'API_ACCESS_KEY', 'AIzaSyAfzeHNYjfz842uMxHSJFTTMypwkCepjvw' );

		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}


		$rest = $request["restaurant_id"];
		$menu_details = $request["menu_details"];

		$send_push = $request["send_push"];

		if ( $send_push == '0' ) {

			foreach ($menu_details as $details) {
			
				$this->db->where("id", $details["id"]);
				$this->db->update("food_items", ["offer_price" => 0]);
		   }

		   echo json_encode( ["status" => "success", "msg" => "All Offer Price removed"] );
		   exit;
		}

		$ids = [];

		$this->db->select("restaurants.*,restaurant_menus.menu_id,restaurant_category.name as restaurant_category_name");
		$this->db->from("restaurants");
		$this->db->join("restaurant_menus", "restaurant_menus.restaurant_id = restaurants.id", "left");
		$this->db->join("restaurant_category", "restaurants.restaurant_category_id = restaurant_category.id", "left");

		$this->db->where("restaurants.id", $rest);


		$result = $this->db->get()->result_array();

		$all_notification_user = $this->get_all_users( $result[0]['lat'], $result[0]['lng'] );


		foreach ($menu_details as $details) {
				
			$ids []  = $details["id"];
			$percentage[$details["id"]] = $details["percentage"];
		}

	     
		$offer_details = $this->getRestaurantMenuDetails ( $ids );
		$responseOfferDetails = $this->getNewRestaurantMenuDetails( $ids, $percentage );
		
		$msgs = '';
		for ( $count = 0; $count < sizeof($offer_details); $count++ ) {

			$offer_details[$count]["offer_price"] = intval($offer_details[$count]["price"] - ( $percentage[$offer_details[$count]["id"]]* $offer_details[$count]["price"])/100);
			$offer_details[$count]["offer_title"] = $percentage[$offer_details[$count]["id"]]." % discount in ".$offer_details[$count]["name"];
	 		$msgs .= $offer_details[$count]["offer_title"].", ";

	 		$this->db->where("id", $offer_details[$count]["id"]);
			$this->db->update("food_items", ["offer_price" => $offer_details[$count]["offer_price"]]);
	 	}

		$result[0]['offer_details'] = $responseOfferDetails;

	 	$msg = array (
				'message' 	=>  ["data" => $result[0], "status" => 1],
				'title'		=> $msgs,
				'subtitle'	=> 'Tap for more details',
				'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
				'vibrate'	=> 1,
				'sound'		=> 1,
				'largeIcon'	=> 'large_icon',
				'smallIcon'	=> 'small_icon'
		);




		foreach ( $all_notification_user as $notifications ) {


            $insertItems = [];
            $insertItems["app_user_id"] = $notifications["user_id"];
            $insertItems["notification"] = $msgs;
            $insertItems["restaurant_id"] = $rest;
            $insertItems["datetime"] = date("Y-m-d H:i:s");
            $insertItems["notification_details"] = json_encode($menu_details);

            $this->db->insert("notification_lists", $insertItems);

			
			$registrationIds = array( $notifications["device_id"] );

			$fields = array
			(
				'registration_ids' 	=> $registrationIds,
				'data'			=> $msg
			);
			 
			$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			 
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$results = curl_exec($ch );
			curl_close( $ch );
		}

		echo json_encode( $msg["message"] );
		
	}


	public function get_all_users( $rest_lat, $rest_lng ) {

		$this->db->select("app_user.device_id, user_location.*");
		$this->db->from("app_user");
		$this->db->join("user_location", "app_user.user_id = user_location.user_id");
		$this->db->where("app_user.device_id !=''");
		$this->db->where("user_location.user_id !='0'");
		$this->db->group_by("app_user.device_id");

		$result = $this->db->get()->result_array();

		$lists = [];

		foreach ( $result as $items ) {

			$calculate_distance =  $this->distance($rest_lat, $rest_lng, $items["lat"], $items["lng"]);
			if  ( $calculate_distance < 100 ) {

				$lists[] = $items; 
			}

		}
		
		return $lists;

	}

}