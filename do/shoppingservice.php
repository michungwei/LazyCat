<?php
include_once('_config.php');
include_once($inc_path.'lib/_shoppingcar.php');

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$car = new shoppingCar();
$method = post('method', 1);
//$result = false;
$result = 0;
$msg = "";
$total = "";
$re = array();

if($method == "add"){
	$product_id = post('product_id', 1);
	$product_sno = post('product_sno', 1);
	$product_name_en = post('product_name_en', 1);
	$product_name_tw = post('product_name_tw', 1);
	$special_price = post('special_price', 1);
	$sell_price = post('sell_price', 1);
	$amount = post('amount');
	$subtotal = post('subtotal');
	$pic = post('pic', 1);

	if($product_id > 0 && $product_sno != ""){

		$item = new shoppingItem($product_id, $product_sno, $special_price, $sell_price, $amount, $subtotal, $product_name_en, $product_name_tw, $pic);
		$car -> add($item);//加入car
		$result = 1;
		$car -> calculate();//計算
		$msg = $car -> num;//總數量
		$total = $car -> total;//總金額
	}else{
		$msg = '參數資料傳送錯誤!';
	}
}

if($method == "update"){
	$sid = post('cart_id',1);
	$num = post('num');
	$subtotal = "";
	//$memberid = post('memberid');

	if($sid != ""){
		$car -> modify($sid, $num);
		//$car->checkEvent();
		$car -> calculate();
		$total = $car->total;
		//$freight=$car->freight;
		//$discount=$car->discount;
		//$disbonus=$car->disbonus;
		//$freightprice=$car->freightprice;
		//$freightlimit=$car->freightlimit;
		//$discountcomment=json_encode($car->aryEvent);
    
		$result=1;		
		//$alltotal=($total-$discount-$disbonus+$freight);

		if($num > 0){
			$item = $car -> getItemByID($sid);
			$subtotal = $item -> subtotal;
		}
		
		$msg='[{"carnum":"'.$car -> num.'","amount":"'.$num.'","total":"'.$total.'","subtotal":"'.$subtotal.'"}]';
	}else{
		$msg="參數資料傳送錯誤!";
	}
}

if($method == "clear"){	
	$car -> clear();
	$result = 1;
}

if($method == "chkstock"){
	$product_id = post('product_id', 1);
	$num = post('num');

	if($product_id > 0 && $num > 0){
		$row = $db -> query_first("SELECT * FROM $table_product WHERE  product_id = '$product_id'");
		$stock = $row["product_stock"];
		if($stock >= $num){
			$result = 1;
			
		}else{
			$msg = $row["product_name_tw"]."庫存不足!庫存只剩".$row["product_stock"]."!";
		}
	}else{
		$msg = "參數資料傳送錯誤!";
	}
}
$db -> close();

//echo '[{"result":"'.($result===true ? 'true' : 'false') .'","message":'.$msg.',"total":'.$total.'}]';

$re["result"] = ($result == 1) ? true : false;
$re["message"] = $msg;
$re["total"] = $total;

echo json_encode($re);

/*End PHP*/