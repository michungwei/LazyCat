<?php
class Product{
	//New商品
	public static function getNewPro(){
		global $db, $table_product;
		return $db -> fetch_all_array(
								  	  "SELECT *
								   	   FROM $table_product 
								   	   WHERE product_is_show = 1 AND product_weekly = 1
									   ORDER BY product_ind DESC"
		);
	}
	//Hot商品
	public static function getHotPro(){
		global $db, $table_product;
		return $db -> fetch_all_array(
								  	  "SELECT *
								   	   FROM $table_product 
								   	   WHERE product_is_show = 1 AND product_hot = 1
									   ORDER BY product_ind DESC"
		);
	}
	
	//系列商品
	//商品系列列表
	public static function getSerialList($type_id){
		global $db, $table_productserial, $table_product;
		return $db -> fetch_all_array(
									  "SELECT productserial_id, productserial_type_id, productserial_name, productserial_is_show, product_serial_id, COUNT(product_id) AS amount, MAX(product_sell_price) AS height_price, MIN(product_sell_price) AS low_price
									   FROM $table_productserial LEFT JOIN $table_product ON product_serial_id = productserial_id
									   WHERE productserial_is_show = 1 AND productserial_type_id = '$type_id'
									   GROUP BY product_serial_id
									   HAVING amount > 0
									   ORDER BY productserial_ind DESC"
		);
	}
	public static function getSerialList2($keyword){
		global $db, $table_productserial, $table_product;
		return $db -> fetch_all_array(
									  "SELECT product_id, product_ind, product_sno, product_type_id, product_serial_id, product_name_tw, product_name_en, product_sell_price, product_pic1, product_is_show, productserial_id, productserial_ind, productserial_name, COUNT(product_id) AS amount, MAX(product_sell_price) AS height_price, MIN(product_sell_price) AS low_price
									   FROM $table_product LEFT JOIN $table_productserial ON product_serial_id = productserial_id
									   WHERE product_is_show = 1 AND (product_name_tw LIKE '%$keyword%' OR product_name_en LIKE '%$keyword%')
									   GROUP BY product_serial_id
									   HAVING amount > 0
									   ORDER BY productserial_ind DESC"
		);
	}
	//商品列表
	public static function getProductList($type_id, $serial_id, $max_value_price, $min_value_price, $order){
		global $db, $table_product;

		$where_str = " AND product_type_id = '$type_id'";
		if($serial_id != "00"){
			$where_str = " AND product_serial_id = '$serial_id'";
		}

		$order_str = "";
		switch ($order){
			case 1 :
				$order_str = "product_sell_price DESC";
				break;
			case 2 :
				$order_str = "product_sell_price ASC";
				break;
			case 9 :
				$order_str = "product_ind DESC";
				break;
			default :
				break;
		}
		$sql = "SELECT * 
				FROM $table_product 
				WHERE product_is_show = 1 AND (product_sell_price >= $min_value_price AND product_sell_price <= $max_value_price)".$where_str."
				ORDER BY ".$order_str;
		return $sql;
	}
	
	public static function getProductList2($keyword, $max_value_price, $min_value_price, $order){
		global $db, $table_product, $table_productserial;
		
		$order_str = "";
		switch ($order){
			case 1 :
				$order_str = "product_sell_price DESC";
				break;
			case 2 :
				$order_str = "product_sell_price ASC";
				break;
			case 9 :
				$order_str = "product_type_id, product_serial_id DESC";
				break;
			default :
				break;
		}
		
		$sql = "SELECT product_id, product_ind, product_sno, product_type_id, product_serial_id, product_name_tw, product_name_en, product_sell_price, product_pic1, product_is_show, productserial_id, productserial_name
				FROM $table_product LEFT JOIN $table_productserial ON  product_serial_id = productserial_id
				WHERE product_is_show = 1 AND (product_sell_price >= $min_value_price AND product_sell_price <= $max_value_price) AND (product_name_tw LIKE '%$keyword%' OR product_name_en LIKE '%$keyword%')
				ORDER BY ".$order_str;
		return $sql;
	}
	//最高價格
	public static function getMAXPrice($type_id, $serial_id){
		global $db, $table_product;
		$where_str = " AND product_type_id = '$type_id'";
		if($serial_id != "00"){
			$where_str = " AND product_serial_id = '$serial_id'";
		}
		return $db -> query_first(
								  "SELECT MAX(product_sell_price) AS height_price
								   FROM $table_product 
								   WHERE product_is_show = 1".$where_str
		);
	}
	
	public static function getMAXPrice2($keyword){
		global $db, $table_product;
		
		return $db -> query_first(
								  "SELECT MAX(product_sell_price) AS height_price
								   FROM $table_product 
								   WHERE product_is_show = 1 AND (product_name_tw LIKE '%$keyword%' OR product_name_en LIKE '%$keyword%')"
		);
	}
	//最低價格
	public static function getMINPrice($type_id, $serial_id){
		global $db, $table_product;
		$where_str = " AND product_type_id = '$type_id'";
		if($serial_id != "00"){
			$where_str = " AND product_serial_id = '$serial_id'";
		}
		return $db -> query_first(
								  "SELECT MIN(product_sell_price) AS low_price
								   FROM $table_product 
								   WHERE product_is_show = 1".$where_str
		);
	}
	
	public static function getMINPrice2($keyword){
		global $db, $table_product;
		return $db -> query_first(
								  "SELECT MIN(product_sell_price) AS low_price
								   FROM $table_product 
								   WHERE product_is_show = 1 AND (product_name_tw LIKE '%$keyword%' OR product_name_en LIKE '%$keyword%')"
		);
	}
	//商品
	public static function getProduct($pro_id){
		global $db, $table_product;
		return $db -> query_first(
								  "SELECT *
								   FROM $table_product 
								   WHERE product_id = '$pro_id'"
		);
	}
	
	//最新商品
	//最新商品分類列表
	public static function getNewTypeList(){
		global $db, $table_product;
		return $db -> fetch_all_array(
									  "SELECT product_type_id, product_serial_id, COUNT(product_id) AS amount, MAX(product_sell_price) AS height_price, MIN(product_sell_price) AS low_price
									   FROM $table_product 
									   WHERE product_is_show = 1 AND product_weekly = 1
									   GROUP BY product_type_id
									   HAVING amount > 0
									   ORDER BY product_type_id DESC"
		);
	}
	
	//最新商品列表
	public static function getNewProductList($type_id, $max_value_price, $min_value_price, $order){
		global $db, $table_product;
		$where_str = "";
		if($type_id != "00"){
			$where_str = " AND product_type_id = '$type_id' ";
		}
		$str = "";
		switch ($order){
			case 1 :
				$str = "product_sell_price DESC";
				break;
			case 2 :
				$str = "product_sell_price ASC";
				break;
			case 9 :
				$str = "product_ind DESC";
				break;
			default :
				break;
		}
		$sql = "SELECT * 
				FROM $table_product 
				WHERE product_is_show = 1 AND product_weekly = 1 AND (product_sell_price >= $min_value_price AND product_sell_price <= $max_value_price)".$where_str."
				ORDER BY ".$str;
		return $sql;
	}
	//最新商品最高價格
	public static function getNewMAXPrice($type_id){
		global $db, $table_product;
		$where_str = "";
		if($type_id != "00"){
			$where_str = " AND product_type_id = '$type_id' ";
		}
		return $db -> query_first(
								  "SELECT MAX(product_sell_price) AS height_price
								   FROM $table_product 
								   WHERE product_is_show = 1 AND product_weekly = 1".$where_str
		);
	}
	//最新商品最低價格
	public static function getNewMINPrice($type_id){
		global $db, $table_product;
		$where_str = "";
		if($type_id != "00"){
			$where_str = " AND product_type_id = '$type_id' ";
		}
		return $db -> query_first(
								  "SELECT MIN(product_sell_price) AS low_price
								   FROM $table_product 
								   WHERE product_is_show = 1 AND product_weekly = 1".$where_str
		);
	}
	
	
	//WISH LIST
	//wish list列表
	public static function getWishList($member_id){
		global $db, $table_wish, $table_product;
		return $db -> fetch_all_array(
									  "SELECT wish_id, wish_member_id, wish_product_id, wish_create_time, product_id, product_sno, product_name_tw, product_name_en, product_stock, product_sell_price, product_special_price, product_pic1, product_is_show
									   FROM $table_wish LEFT JOIN  $table_product ON wish_product_id = product_id
									   WHERE wish_member_id = '$member_id'
									   ORDER BY wish_create_time DESC"
		);
	}
	
}


/***END PHP***/