<?php
include_once("_config.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');
include_once($inc_path."lib/coder_order.php");
include_once('../inc/_func_smtp.php');

$sResult = 0;
$message = "";

$recipient_name = post("recipient_name", 1);
$recipient_mobile = post("recipient_mobile", 1);
$recipient_email = post("recipient_email", 1);
$recipient_address = post("recipient_address", 1);
$payment_type = post("payment_type", 1);
$recipient_wayOption = post("recipient_wayOption", 1);


$sResult = isNull($recipient_name, "姓名", 1, 30);
if($sResult){$sResult = isNull($recipient_mobile, "手機", 1, 15);}
if($sResult){$sResult = isEmail2($recipient_email, "信箱");}
if($sResult){$sResult = isNull($recipient_address, "地址", 1, 99999);}
if($sResult){$sResult = isNull($payment_type, "付款方式", 1, 2);}
if($sResult){$sResult = isNull($recipient_wayOption, "運送方式", 1, 2);}

if($sResult){
	$db = new Database($HS, $ID, $PW, $DB);
	$db -> connect();
	
	$car = new shoppingCar();
	$car -> calculate();
	$total = $car -> total;
	$carItem = $car -> getCarFromDB();
	$u = count($carItem);
	
	if(!isLogin()){
		script("請先登入會員!", "sign.html");
	}else{
		if($u == 0){
			script("您目前未購買任何商品,請繼續購物!!", "product-list.html");
		}
		$member_id = $_SESSION["session_id"];
		//$row_member = CoderMember::getList($memberid);
	}
	
	$car -> backupCar($carItem);
	
	$coderorder = new CoderOrder();

	$myorder = new  CoderOrderItem();
	$myorder -> order_sno = "";
	$myorder -> ind = "";
	$myorder -> member_id = $member_id;
	$myorder -> payment_type = $payment_type;
	$myorder -> payment_state = "0";
	$myorder -> order_state = "0";
	$myorder -> order_comment = "";
	$myorder -> total_price = $total;
	$myorder -> freight = 0;
	$myorder -> recipient_name = $recipient_name;
	$myorder -> recipient_email = $recipient_email;
	$myorder -> recipient_mobile = $recipient_mobile;
	$myorder -> recipient_address = $recipient_address;
	$myorder -> recipient_wayOption = $recipient_wayOption;
	$myorder -> create_time = request_cd();
	$myorder -> update_time = request_cd();
	$myorder -> payment_return = "";
	
	for($i = 0; $i < count($carItem); $i++){
		$detailitem = new CoderOrderDetailItem();
		$detailitem -> order_sno = "";
		$detailitem -> product_id = $carItem[$i] -> product_id;
		$detailitem -> product_sno = $carItem[$i] -> product_sno;
		$detailitem -> product_name_tw = $carItem[$i] -> product_name_tw;
		$detailitem -> product_name_en = $carItem[$i] -> product_name_en;
		$detailitem -> sell_price = $carItem[$i] -> sell_price;
		$detailitem -> amount = $carItem[$i] -> amount;
		$detailitem -> subtotal = $carItem[$i] -> subtotal;
		$detailitem -> create_time = request_cd();
		
		$myorder -> insertDetail($detailitem);
	}
	
	try{
		$result = $coderorder -> chkStock($myorder -> detail_ary);
		if($result[0]){
			$coderorder -> orderInsert($myorder);//寫入資料庫
			
		}else{
			$message = $result[1];	
		}
		
	
		$order_sno = $myorder -> order_sno;
		$total_price = $myorder -> total_price;
		//$member_id = $myorder -> member_id;
		//$create_time = $myorder -> create_time;
		//$manager = $myorder -> manager;

		$coderorder -> orderMail($myorder);

		$car -> clear();
		
		$sResule = 1;
		$message = "訂單送出中...請稍待!";
		
		$_SESSION["order_sno"] = $order_sno;
		$_SESSION["total_price"] = $total_price;
		
	}catch(Exception $ex){
		//$sResule = 0;
		$message = $e -> getMessage();
	}

//$db -> close();
}else{
	//$sResule = 0;
	$message = "資料傳輸錯誤!請再試一次!";
}



$res["result"] = ($sResule == 1) ? true : false;
$res["message"] = $message;
$res["payment_type"] = $payment_type;

echo json_encode($res);