<?php
include_once("_config.php");
include_once($inc_path."_imgupload.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$id = post("cid");
$order_sno = post("order_sno", 1);
$old_order_state = post("old_order_state", 1);
$order_state = post("order_state", 1);

$data["order_state"] = $order_state;
if($order_state == 3){
	$data["order_payment_state"] = 1;
}

if($old_order_state <= 3 && $order_state > 3){
	$rows_detail = $db -> fetch_all_array("SELECT * FROM $table_orderdetail WHERE orderdetail_order_sno = '$order_sno' ORDER BY orderdetail_id");
	//print_r($rows_detail);
	foreach($rows_detail as $row_detail){
		$orderdetail_product_id = $row_detail["orderdetail_product_id"];
		$orderdetail_product_sno = $row_detail["orderdetail_product_sno"];
		$orderdetail_amount = $row_detail["orderdetail_amount"];
		$orderdetail_product_color = $row_detail["orderdetail_product_color"];
		
		$db -> begin();
		try{
			$row_stock = $db -> query_first("SELECT * FROM $table_product WHERE product_id = '$orderdetail_product_id' AND product_sno = '$orderdetail_product_sno'");

			$stockAry = explode( ",", $row_stock["product_stock"]);
			$stockAry[$orderdetail_product_color] += $orderdetail_amount;

			$stockStr = "";
			for($j = 0; $j < count($stockAry) - 1; $j ++)
			{
				$stockStr .= $stockAry[$j].",";
			}
			
			$db -> query("UPDATE $table_product SET product_stock = '$stockStr' WHERE product_id = '$orderdetail_product_id' AND product_sno = '$orderdetail_product_sno'");
			
			//新增庫存log
			$data_stock["storelog_product_id"] = $orderdetail_product_id;
			$data_stock["storelog_acc"] = $_SESSION["madmin"];
			$data_stock["storelog_comment"] = "(訂單取消/退訂)庫存由".$row_stock["product_stock"]."修改為".$stockStr;
			$data_stock["storelog_create_time"] = request_cd();
			
			$db -> query_insert($table_storelog, $data_stock);
				
		$db -> commit();
		}catch(Exception $e){
			$db -> rollback();
			script($e -> getMessage());
		}
	}
}

$data["order_comment"] = request_str("order_comment");
$data["order_update_time"] = request_cd();
	
$db -> query_update($table_order, $data, "$id_column = $id");


$db -> close();

script("修改完成!", "edit.php?id=".$id);

/*End PHP*/



