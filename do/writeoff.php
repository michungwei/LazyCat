<?php
include_once('_config.php');
include_once('../inc/_func_smtp.php');

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

function getParameter($pname){
	return isset($_POST[$pname])?$_POST[$pname]:"";
}

//$code               = "abcd1234";
$code = "2fvxqj5x";
$merchantnumber     = getParameter('merchantnumber');
$ordernumber        = getParameter('ordernumber');
$amount             = getParameter('amount');
$paymenttype        = getParameter('paymenttype');

$serialnumber       = getParameter('serialnumber');
$writeoffnumber     = getParameter('writeoffnumber');
$timepaid           = getParameter('timepaid');
$tel                = getParameter('tel');
$hash               = getParameter('hash');

$verify = md5("merchantnumber=".$merchantnumber.
		      "&ordernumber=".$ordernumber.
		      "&serialnumber=".$serialnumber.
		      "&writeoffnumber=".$writeoffnumber.
		      "&timepaid=".$timepaid.
		      "&paymenttype=".$paymenttype.
		      "&amount=".$amount.
		      "&tel=".$tel.
		      $code);
     
print "verify=".$verify;
if(strtolower($hash)!=strtolower($verify)){
	//-- 驗證碼錯誤，資料可能遭到竄改，或是資料不是由ezPay簡單付發送
	print "驗證碼錯誤!".
		  "\nhash=".hash.
		  "\nmerchantnumber=".$merchantnumber.
		  "\nordernumber=".$ordernumber.
		  "\nserialnumber=".$serialnumber.
		  "\nwriteoffnumber=".$writeoffnumber.
		  "\ntimepaid=".$timepaid.
		  "\npaymenttype=".$paymenttype.
		  "\namount=".$amount.
		  "\ntel=".$tel;

	
	$db -> query("UPDATE $table_order 
			  	  SET order_payment_return = '驗證碼錯誤' 
			      WHERE order_sno = '$ordernumber'"
	);

}else{
	//-- 驗證正確，請更新資料庫訂單狀態
	print "驗證碼正確!".
		"\nmerchantnumber=".$merchantnumber.
		"\nordernumber=".$ordernumber.
		"\nserialnumber=".$serialnumber.
		"\nwriteoffnumber=".$writeoffnumber.
		"\ntimepaid=".$timepaid.
		"\npaymenttype=".$paymenttype.
		"\namount=".$amount.
		"\ntel=".$tel;
		  
	$payreturn = "merchantnumber".$merchantnumber."ordernumber".$ordernumber."amount".$amount."paymenttype".$paymenttype."serialnumber".$serialnumber."writeoffnumber".$writeoffnumber."timepaid".$timepaid."tel".$tel."hash".$hash."verify".$verify;
	$payreturn = base64_encode(serialize($payreturn));
	
	$data["order_payment_state"] = 1;
	$data["order_payment_return"] = $payreturn;
	$db -> query_update($table_order, $data, "order_sno='$ordernumber'");
		  
				  
	$sql = "SELECT order_member_id, member_name, member_email
			FROM $table_order LEFT JOIN $table_member ON order_member_id = member_id
			WHERE order_sno = '$ordernumber'";
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
		$msg .= '訂單編號：'.$ordernumber.'。<br />';
		$msg .= '已完成付款。<br />';
		$msg .= '總計：'.$amount.'。<br />';
        $msg .= '感谢您的付款, 我們1~2個工作天將會為您出貨。<br /><br />';
		$msg.='感謝您的訂購，有任何問題請歡迎聯絡客服中心。<br /><br />';
		
		sendMail($fr_em, $fr_na, $to_em, $to_na, $subject, $msg);
	}
}
$db -> close();	
	
/*****END PHP*****/ 

	 
