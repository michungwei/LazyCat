<?php 
include('_config.php');

function getParameter($pname){
	return isset($_POST[$pname]) ? $_POST[$pname] : "";
}

$MerchantNumber = getParameter('MerchantNumber');
$OrderNumber = getParameter('OrderNumber');
$PRC = getParameter('PRC');
$SRC = getParameter('SRC');
$Amount = getParameter('Amount');
$CheckSum = getParameter('CheckSum');
$ApprovalCode = getParameter('ApprovalCode');
$BankResponseCode = getParameter('BankResponseCode');
$BatchNumber = getParameter('BatchNumber');

//$Code = "abcd1234";
$Code = "2fvxqj5x";

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php
$chkstr = $MerchantNumber.$OrderNumber.$PRC.$SRC.$Code.$Amount;
$chkstr = md5($chkstr);

if($PRC=="0" && $SRC=="0"){
	//-- 回傳成功，但結果有可能遭竄改，因此需和編碼內容比較
	if(strtolower($chkstr)==strtolower($CheckSum)){
?>
        <br><?php print $chkstr ?>
        <br><?php print $CheckSum ?>
        <br>交易成功
        <br>訂單編號　：<?php print $OrderNumber ?>
        <br>交易金額　：<?php print $Amount ?>
        <br>授權碼　　：<?php print $ApprovalCode ?>
        <br>銀行回傳碼：<?php print $BankResponseCode ?>
        <br>批次號碼  ：<?php print $BatchNumber ?>
<?php
		$payreturn = array("Msg" => "OK", "CheckSum" => $CheckSum, "PRC" => $PRC, "SRC" => $SRC, "ApprovalCode" => $ApprovalCode, "BankResponseCode" => $BankResponseCode, "MerchantNumber" => $MerchantNumber, "OrderNumber" => $OrderNumber, "Amount" => $Amount, "BatchNumber" => $BatchNumber);
		
	}else{
?>
        //-- 資料遭竄改
        交易結果有誤，請與我們聯絡!
<?php
		$payreturn = array("Msg" => "資料遭竄改!");	
	}
}else if($PRC=="34" && $SRC=="171"){
?>
    <br>交易失敗(金融失敗)
    <br>訂單編號　：<?php print $OrderNumber ?>
    <br>交易金額　：<?php print $Amount ?>
    <br>銀行回傳碼：<?php print $BankResponseCode ?>

<?php
	$payreturn = array("Msg" => "交易失敗(金融失敗)!");
}else if($PRC=="8" && $SRC=="204"){
?>
	<br>訂單編號重複!
<?php
	$payreturn = array("Msg" => "訂單編號重複!");
}else if($PRC=="52" && $SRC=="554"){
?>
	<br>使用者帳號密碼錯誤!
<?php
	$payreturn = array("Msg" => "使用者帳號密碼錯誤!");
}else{
?>
    <br>交易失敗(系統錯誤)
    <br>訂單編號　：<?php print $OrderNumber ?>
    <br>交易金額　：<?php print $Amount ?>
<?php
	$payreturn = array("Msg" => "交易失敗(系統錯誤)!");
}

$payreturn = base64_encode(serialize($payreturn));
$db -> query("UPDATE $table_order 
			  SET order_payment_return = '$payreturn' 
			  WHERE order_sno = '$OrderNumber'");
			  
$db -> close();
?>
</body>
</html>
