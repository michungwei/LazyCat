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


//金流資料
//$act_url = "https://testmaple2.neweb.com.tw/NewebmPP/cdcard.jsp";
$act_url = "https://taurus.neweb.com.tw/NewebmPP/cdcard.jsp";
$MerchantNumber = "760535"; //商店編號
$OrderNumber = $order_sno;
$Amount = $total_price;
$OrgOrderNumber = "lazycat_".$order_sno;
$ApproveFlag = 1;
$DepositFlag = 1;
$Englishmode = 0;
$iphonepage = 0;
$OrderURL = $web_url."do/PaymentType_feedback2.php";
$ReturnURL = $web_url."do/PaymentType_receive.php";
//$Code = "abcd1234";
$Code = "2fvxqj5x";
$checksum = md5($MerchantNumber.$OrderNumber.$Code.$Amount);

$db -> close();
?>
<!DOCTYPE html>
<html lang="zh_tw">
<head>
<meta charset="UTF-8" />
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="description" content="<?php echo $description; ?>">
<meta name="author" content="<?php echo $author; ?>">
<meta name="copyright" content="<?php echo $copyright; ?>">
<title><?php echo $web_name; ?></title>
<script src="../assets/scripts/jquery-2.1.1.min.js"></script>
<script>
$(document).ready(function(e) {
   $("#pay_form").submit();
});
</script>
</head>
<body>
<form action="<?php echo $act_url; ?>" method="post" id="pay_form" name="pay_form">
    <input type="hidden" name="MerchantNumber" value="<?php print $MerchantNumber; ?>">
	<input type="hidden" name="OrderNumber"    value="<?php print $OrderNumber; ?>">
	<input type="hidden" name="Amount"         value="<?php print $Amount; ?>">
	<input type="hidden" name="OrgOrderNumber" value="<?php print $OrgOrderNumber; ?>">
	<input type="hidden" name="ApproveFlag"    value="<?php print $ApproveFlag; ?>">
	<input type="hidden" name="DepositFlag"    value="<?php print $DepositFlag; ?>">
	<input type="hidden" name="Englishmode"    value="<?php print $Englishmode; ?>">                    
	<input type="hidden" name="iphonepage"     value="<?php print $iphonepage; ?>">                    
	<input type="hidden" name="OrderURL"       value="<?php print $OrderURL; ?>">
	<input type="hidden" name="ReturnURL"      value="<?php print $ReturnURL; ?>">
	<input type="hidden" name="checksum"       value="<?php print $checksum; ?>">
	<input type="hidden" name="op"             value="AcceptPayment">
    
    <!--<input type="submit">-->
</form>

</body>
</html>