<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: application/json');

class Orders extends CI_Controller {


	public function add_restaurant_device()
	{
		
		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}

		$deviceId = $request["deviceId"];
		$device_type = $request["device_type"];
		$device_unique_id = $request["device_unique_id"];
		$restaurantId = $request["restaurant_id"];

		$this->db->select("id");
		$result = $this->db->get_where("track_restaurant_devices", array(
            "unique_id" => $device_unique_id
        ))->result_array();

        if ( sizeof($result) == '0' ) {

            $insert = [];

            $insert["device_id"] = $deviceId; 
            $insert["device_type"] = $device_type;
            $insert["unique_id"] = $device_unique_id ;
            $insert["restaurant_id"] = $restaurantId;

            $this->db->insert( "track_restaurant_devices", $insert );

            $id = $this->db->insert_id();

        }else {

        	$insert["device_id"] = $deviceId; 
            $insert["device_type"] = $device_type;
            $insert["restaurant_id"] = $restaurantId;

            $this->db->where("unique_id", $device_unique_id);
            $this->db->update( "track_restaurant_devices", $insert );

            $id = $result[0]["id"];

        }

        $data = $this->db->get_where("track_restaurant_devices", [
        	"id" => $id
        ])->result_array();

        echo json_encode( [ "status" => 1, "data" => $data ] );
	}
	

	public function add()
	{
		
		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}

		$order_items = $request["order_items"];
		$insert['restaurant_id'] = $request["restaurant_id"];
		$insert['total_amount'] = $request["total_amount"];
		$insert['sub_total'] = $request["sub_total"];
		$insert['delivery_type'] = $request["delivery_type"];
		$insert['shipping_cost'] = $request["shipping_cost"];
		$insert['user_name'] = $request["user_name"];
		$insert['user_address'] = $request["user_address"];
		$insert['user_email'] = $request["user_email"];
		$insert['user_phone'] = $request["user_phone"];

		$insert['user_id'] = $request["user_id"];
		$insert['device_id'] = $request["device_id"];
		$insert['device_type'] = $request["device_type"];

		$this->db->insert ( "order", $insert );
		$id = $this->db->insert_id();

		$this->insert_order_items ( $id, $order_items );
		$this->send_email ( $insert['restaurant_id'], $insert );
		$res = $this->send_push( $insert['restaurant_id'], $order_items );

        echo json_encode( [ "status" => 1, "data" => $res, "msg" => "order notification sent to restaurant owner" ] );
	}

	public function insert_order_items ( $orderId, $items ) {

		foreach ( $items as $item ) {

			$item["order_id"] = $orderId;

			$this->db->insert ( "order_items" , $item );
		}

	}

	public function send_email ( $restaurantId, $data ){

		$result = $this->db->get_where("restaurants", [
			"id" => $restaurantId
		])->result_array();

		if ( sizeof($result) > '0') {

			$email = $result[0]["email"];
			$subject = "New Order Arrived";
            $body = $this->getOrderMessageBody ( $data ) ;

            $this->load->library ("email");
            $config['mailtype'] = 'html';
            $config["crlf"] = "\r\n";

			$this->email->initialize($config);

			$this->email->to($email);
			$this->email->from("info@ntstx.com");
			$this->email->subject($subject);
			$this->email->message($body);

			$this->email->send();
		}
	}

	public function getOrderMessageBody ( $details ) {

	   $msg  = " User Name : ".$details["user_name"]."<br/>";
	   $msg .= " Address : ".$details["user_address"]."<br/>";
	   $msg .= " Email : ".$details["user_email"]."<br/>";
	   $msg .= " Total Amount : $ ".$details["total_amount"]."<br/>";

	   return $msg;
	}

	public function send_push ( $rest, $orderItems ) {

		define( 'API_ACCESS_KEY', 'AIzaSyAfzeHNYjfz842uMxHSJFTTMypwkCepjvw' );

		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}

		$ids = [];

		$this->db->select("restaurants.*,restaurant_menus.menu_id,restaurant_category.name as restaurant_category_name");
		$this->db->from("restaurants");
		$this->db->join("restaurant_menus", "restaurant_menus.restaurant_id = restaurants.id", "left");
		$this->db->join("restaurant_category", "restaurants.restaurant_category_id = restaurant_category.id", "left");

		$this->db->where("restaurants.id", $rest);


		$result = $this->db->get()->result_array();
 		
 		$result[0]['distance'] = 0;

		$all_notification_user = $this->db->get_where( "track_restaurant_devices", ["restaurant_id" => $rest ] )->result_array();

		foreach ($orderItems as $value) {
			$ids[] = $value["item_id"];
		}

		$result[0]['offer_details'] = $this->getNewRestaurantMenuDetails ( $ids );

	 	$msg = array (
				'message' 	=>  ["data" => $result[0], "status" => 1],
				'title'		=> "A new order Received",
				'subtitle'	=> 'Tap for more details',
				'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
				'vibrate'	=> 1,
				'sound'		=> 1,
				'largeIcon'	=> 'large_icon',
				'smallIcon'	=> 'small_icon'
		);

		foreach ( $all_notification_user as $notifications ) {
			
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

		return $msg["message"]["data"];
		
	}


	public function getNewRestaurantMenuDetails ( $menuId ) {
        
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

			   if ( $result[$count]['menu_details'][$count2]['offer_price'] > '0' ) {
		   	   		$result[$count]['menu_details'][$count2]['has_offer_price'] = 1;
		   	   }else {
		   	   		$result[$count]['menu_details'][$count2]['has_offer_price'] = 0;
		   	   }
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

	public function users_lists ( $user_id ) {
		$this-> db-> order_by("id", "desc");
		$result = $this->db->get_where("order", ["user_id" => $user_id])->result_array();
		
		for ( $count = 0; $count < sizeof($result); $count++ ) {

			$menuIds = $this->getOrderMenuIds ( $result[$count]["id"] );
			$result[$count]["menu_details"] = $this->getOrderMenuDetails ( $result[$count]["id"], $menuIds );
		}	


		echo json_encode(  [ "status" => 1, "data" => $result ]  );

	}

	public function update_order_status () {

		$request = json_decode (file_get_contents('php://input'), TRUE);

		if (json_last_error()) {

			echo json_encode(array("status" => 0, 'msg' => 'Sending Json data is not valid'));
			exit;
		}

		$orderId = $request["order_id"];
		$type = intval ( $request["is_order_accepted"] );

		$this->db->where ( "id", $orderId );
		$this->db->update ( "order", [ "is_order_accepted" => $orderId ] );

		$this->sentUserOrderStatusPush ( $orderId, $type );

		echo json_encode(  [ "status" => 1, "msg" => "Order status updated" ]  );
	}

	public function sentUserOrderStatusPush ( $orderId, $orderAccepted ) {

		define( 'API_ACCESS_KEY', 'AIzaSyAfzeHNYjfz842uMxHSJFTTMypwkCepjvw' );

		$this->db->where ( "id", $orderId );
		$orderDetails = $this->db->get("order")->result_array();

		if ( $orderAccepted == '0' ) {
			$status = "cancelled";
		}else {
			$status = "accepted";
		}

		if ( sizeof($orderDetails) > '0' ) {

			$device_type = $orderDetails[0]["device_type"];
			$device_id = $orderDetails[0]["device_id"];
			$restaurant_id = $orderDetails[0]["restaurant_id"];

			$this->db->select("restaurants.*,restaurant_menus.menu_id,restaurant_category.name as restaurant_category_name");
			$this->db->from("restaurants");
			$this->db->join("restaurant_menus", "restaurant_menus.restaurant_id = restaurants.id", "left");
			$this->db->join("restaurant_category", "restaurants.restaurant_category_id = restaurant_category.id", "left");

			$this->db->where("restaurants.id", $restaurant_id);


			$result = $this->db->get()->result_array();
	 		
	 		$result[0]['distance'] = 0;

			$orderItems = $this->db->get_where( "order_items", [ "order_id" => $orderId ] )->result_array();

			foreach ($orderItems as $value) {
				$ids[] = $value["item_id"];
			}

			$result[0]['offer_details'] = $this->getNewRestaurantMenuDetails ( $ids );

			if ( $device_type == 'android' ) {

				$msg = array (
					'message' 	=>  ["data" => $result[0], "status" => 1],
					'title'		=> "Your order is $status by restaurant owner",
					'subtitle'	=> 'Tap for more details',
					'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
					'vibrate'	=> 1,
					'sound'		=> 1,
					'largeIcon'	=> 'large_icon',
					'smallIcon'	=> 'small_icon'
				);


				$registrationIds = array( $device_id );

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
		}

		//echo json_encode(  [ "status" => 1, "data" => $result[0] ]  );
	}

	public function lists ( $restId ) {
		$this-> db-> order_by("id", "desc");
		$result = $this->db->get_where("order", ["restaurant_id" => $restId])->result_array();
		
		for ( $count = 0; $count < sizeof($result); $count++ ) {

			$menuIds = $this->getOrderMenuIds ( $result[$count]["id"] );
			$result[$count]["menu_details"] = $this->getOrderMenuDetails ( $result[$count]["id"], $menuIds );
		}	


		echo json_encode(  [ "status" => 1, "data" => $result ]  );

	}


	public function getOrderMenuDetails ( $orderId, $menuId ) {
        
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

			   if ( $result[$count]['menu_details'][$count2]['offer_price'] > '0' ) {
		   	   		$result[$count]['menu_details'][$count2]['has_offer_price'] = 1;
		   	   }else {
		   	   		$result[$count]['menu_details'][$count2]['has_offer_price'] = 0;
		   	   }

		   	   $orerItemDetails = $this->getOrderItemsDetails ( $orderId, $result[$count]['menu_details'][$count2]['id'] );
			   $result[$count]['menu_details'][$count2]['order_item_quantity'] = $orerItemDetails["quantity"];
			   $result[$count]['menu_details'][$count2]['order_item_price'] =  $orerItemDetails["price"];

			}

	   }

	   return $result;

	}

	public function getOrderItemsDetails ( $orderId, $itemId ) {

		return $this->db->get_where("order_items", [
			"item_id" => $itemId,
			"order_id" => $orderId
		])->result_array()[0];
	}

	public function getOrderMenuIds( $orderId ){

 		$this->db->select("item_id");
 		$result = $this->db->get_where("order_items", ["order_id" => $orderId])->result_array();
		
		$items = [];

		foreach ( $result as $item ) {

			$items[] = $item["item_id"];
		}

		return $items;

	} 
}