<?php
include_once("_config.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$car = new shoppingCar();
$product = $car -> getBackupCar();

$u = count($product);

if(!isLogin()){
	script("請先登入會員!", "sign.html");
}else{
	if($u == 0 && !$_SESSION["isGoPay"]){
		script("您目前未購買任何商品,請繼續購物!!", "product-list.html");
	}
	$memberid = $_SESSION["session_id"];
	//$row_member = CoderMember::getList($memberid);
}

$order_sno = $_SESSION["order_sno"];
$total_price = $_SESSION["total_price"];
$regetOrder = $_SESSION["regetOrder"];


//金流資料
$act_url = "https://aquarius.neweb.com.tw/CashSystemFrontEnd/Payment";
$query_url = "https://aquarius.neweb.com.tw/CashSystemFrontEnd/Query";
$code = "dufu7iz4";
$merchantnumber = "460060"; //商店編號
$ordernumber = $order_sno;
$amount = $total_price;
$paymenttype = "ATM";
$paytitle = "LacyCat購物".$ordernumber;
$paymemo = "LacyCat購物".$ordernumber;
$bankid = "007";

$duedate = "";
$payname = "";
$payphone = "";
$returnvalue = "0";

$hash = md5($merchantnumber.$code.$amount.$ordernumber);
$nexturl = $web_url."order-step5.html";

$db -> close();
?>
<!DOCTYPE html>
<html lang="zh_tw">
<head>
<meta charset="UTF-8" />
<meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible'>
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="description" content="<?php echo $description; ?>">
<meta name="author" content="<?php echo $author; ?>">
<meta name="copyright" content="<?php echo $copyright; ?>">
<title><?php echo $web_name; ?></title>
<script src="../assets/scripts/jquery-2.1.1.min.js"></script>
<script>
$(document).ready(function(e) {
    $("#pay_form_ATM").submit();
});
</script>
</head>
<body>
	<?php
	if($regetOrder)
	{
	?>
	<form action="<?php echo $query_url; ?>" method="post" id="pay_form_ATM" name="pay_form_ATM">
    <input name="merchantnumber" type="hidden" id="merchantnumber" value="<?php print $merchantnumber; ?>">
	<input name="ordernumber" type="hidden" id="ordernumber"       value="<?php print $ordernumber; ?>">
	<input name="amount" type="hidden" id="amount"                 value="<?php print $amount; ?>">
	<input name="paymenttype" type="hidden" id="paymenttype"       value="<?php print $paymenttype; ?>">
	<input name="bankid" type="hidden" id="bankid"                 value="<?php print $bankid; ?>">
    <input name="nexturl" type="hidden" id="nexturl"               value="<?php print $nexturl; ?>">
    <input name="hash" type="hidden" id="hash"              	   value="<?php print $hash; ?>">
    <input name="operation" type="hidden" id="operation"           value="regetorder">
    
    <!--<input type="submit">-->
	</form>
	<?php
		$_SESSION["regetOrder"] = false;
	}
	else
	{
	?>
	<form action="<?php echo $act_url; ?>" method="post" id="pay_form_ATM" name="pay_form_ATM">
    <input name="merchantnumber" type="hidden" id="merchantnumber" value="<?php print $merchantnumber; ?>">
	<input name="ordernumber" type="hidden" id="ordernumber"       value="<?php print $ordernumber; ?>">
	<input name="amount" type="hidden" id="amount"                 value="<?php print $amount; ?>">
	<input name="paymenttype" type="hidden" id="paymenttype"       value="<?php print $paymenttype; ?>">
	<input name="bankid" type="hidden" id="bankid"                 value="<?php print $bankid; ?>">
	<input name="paytitle" type="hidden" id="paytitle"             value="<?php print $paytitle; ?>">
	<input name="paymemo" type="hidden" id="paymemo"               value="<?php print $paymemo; ?>">                    
	<input name="payname" type="hidden" id="payname"               value="<?php print $payname; ?>">                    
	<input name="payphone" type="hidden" id="payphone"             value="<?php print $payphone; ?>">
	<input name="duedate" type="hidden" id="duedate"               value="<?php print $duedate; ?>">
	<input name="returnvalue" type="hidden" id="returnvalue"       value="<?php print $returnvalue; ?>">
    <input name="nexturl" type="hidden" id="nexturl"               value="<?php print $nexturl; ?>">
    <input name="hash" type="hidden" id="hash"              	   value="<?php print $hash; ?>">
    <input name="operation" type="hidden" id="operation"           value="<?php print $regetOrder; ?>">
    
    <!--<input type="submit">-->
	</form>
	<?php
	}
	?>
</body>
</html>