<?php 
include('_config.php');
include('../inc/_func_smtp.php');

function getParameter($pname){
	return isset($_POST[$pname])?$_POST[$pname]:"";
}
$final_result = getParameter('final_result');
$P_MerchantNumber = getParameter('P_MerchantNumber');
$P_OrderNumber = getParameter('P_OrderNumber');
$P_Amount = getParameter('P_Amount');
$P_CheckSum = getParameter('P_CheckSum');
$final_return_PRC = getParameter('final_return_PRC');
$final_return_SRC = getParameter('final_return_SRC');
$final_return_ApproveCode = getParameter('final_return_ApproveCode');
$final_return_BankRC = getParameter('final_return_BankRC');
$final_return_BatchNumber = getParameter('final_return_BatchNumber');

$Code = "abcd1234";

$Msg = "no_get";
$turl = '';
$order_payment_state = 0;

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

if($final_result=="1"){
	//-- 交易成功
	if(strlen($P_CheckSum)>0){
		$checkstr = md5($P_MerchantNumber.$P_OrderNumber.$final_result.$final_return_PRC.$Code.$final_return_SRC.$P_Amount);
		if(strtolower($checkstr)!=strtolower($P_CheckSum)){
			//print "交易發生問題，驗證碼錯誤!!";
			$Msg = "交易發生問題，驗證碼錯誤!!";
		}else{
			//print "交易成功!!(已檢驗)";
			$Msg = "交易成功!!(已檢驗)!!";
		}
	}else{
		//print "交易成功!!";
		$Msg = "交易成功!!";
	}
	
	$sql = "SELECT order_member_id, member_name, member_email
			FROM $table_order LEFT JOIN $table_member ON order_member_id = member_id
			WHERE order_sno = ".$P_OrderNumber;
	$order_row = $db -> query_first($sql);
	if($order_row){
		$member_name = $order_row["member_name"];
		$member_email = $order_row["member_email"];
		
		$fr_em = $sys_email;
		$fr_na = $sys_name;
		$to_em = $member_email;
		$to_na = $member_name;
		$subject = "【已付款通知】- 感謝您的購買!";
		$msg = "";
		
		$msg .= "親愛的顧客".$member_name.'您好，感謝您的訂購。<br /><br />';
		$msg .= '訂單編號：'.$P_OrderNumber.'。<br />';
		$msg .= '已完成付款。<br />';
		$msg .= '總計：'.$P_Amount.'。<br />';
        $msg .= '感谢您的付款, 我們1~2個工作天將會為您出貨。<br /><br />';
		$msg.='感謝您的訂購，有任何問題請歡迎聯絡客服中心。<br /><br />';
		
		sendMail($fr_em, $fr_na, $to_em, $to_na, $subject, $msg);
	}
	
	
	$order_payment_state = 1;
	$turl='../order-step3.html';
}else{
	//-- 交易失敗，有可能是交易失敗；有可能是交易成功，但通知商家失敗而做了取消，視為交易失敗
	if($final_return_PRC=="8" && $final_return_SRC=="204"){
		//print "交易失敗-->訂單編號重複!";
		$Msg = "交易失敗-->訂單編號重複!";
	}else if($final_return_PRC=="34" && $final_return_SRC=="171"){
		//print "交易失敗-->金融上的失敗!";
		//print "  銀行回傳碼=[".$final_return_BankRC."]<br>";
		$Msg = "交易失敗-->金融上的失敗!銀行回傳碼=[".$final_return_BankRC."]";
	}else{
		//print "交易失敗-->請與商家聯絡!";
		$Msg = "交易失敗-->請與商家聯絡!";
	}
	

	$rows_detail = $db -> fetch_all_array("SELECT * FROM $table_orderdetail WHERE orderdetail_order_sno = '$P_OrderNumber' ORDER BY orderdetail_id");
	foreach($rows_detail as $row_detail){
		$orderdetail_product_id = $row_detail["orderdetail_product_id"];
		$orderdetail_product_sno = $row_detail["orderdetail_product_sno"];
		$orderdetail_amount = $row_detail["orderdetail_amount"];
		
		$db -> begin();
		try{
			$row_stock = $db -> query_first("SELECT * FROM $table_product WHERE product_id = '$orderdetail_product_id' AND product_sno = '$orderdetail_product_sno'");
			
			$db -> query("UPDATE $table_product SET product_stock = (product_stock + '$orderdetail_amount') WHERE product_id = '$orderdetail_product_id' AND product_sno = '$orderdetail_product_sno'");
			
			//新增庫存log
			$data_stock["storelog_product_id"] = $orderdetail_product_id;
			$data_stock["storelog_acc"] = "網站";
			$data_stock["storelog_comment"] = "(金流失敗)庫存由".$row_stock["product_stock"]."修改為".($row_stock["product_stock"]+$orderdetail_amount);
			$data_stock["storelog_create_time"] = request_cd();
			
			$db -> query_insert($table_storelog, $data_stock);
				
		$db -> commit();
		}catch(Exception $e){
			$db -> rollback();
			//script($e -> getMessage());
			$Msg .= $e -> getMessage();
		}
	}
	
	$turl='../order-step4.html';
}

$payreturn2 = array("Msg" => $Msg, "final_result" => $final_result, "P_MerchantNumber" => $P_MerchantNumber, "P_OrderNumber" => $P_OrderNumber, "P_Amount" => $P_Amount, "P_CheckSum" => $P_CheckSum, "final_return_PRC" => $final_return_PRC, "final_return_SRC" => $final_return_SRC, "final_return_ApproveCode" => $final_return_ApproveCode, "final_return_BankRC" => $final_return_BankRC, "final_return_BatchNumber" => $final_return_BatchNumber);
$payreturn2 = base64_encode(serialize($payreturn2));

$db -> query("UPDATE $table_order 
			  SET order_payment_return2 = '$payreturn2', order_payment_state = '$order_payment_state' 
			  WHERE order_sno = '$P_OrderNumber'"
);
	
$db -> close();
	
script($Msg, $turl);


/***** END PHP *****/


