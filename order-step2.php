<?php
include_once("_config.php");
include_once($inc_path."lib/_product.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

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
		script("您目前未購買任何商品,請繼續購物!!");
	}
	$memberid = $_SESSION["session_id"];
	$row_member = CoderMember::getList($memberid);
}
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
<link href="assets/stylesheets/screen.css" media="screen, projection" rel="stylesheet" />
<link href="assets/stylesheets/alertify.core.css" rel="stylesheet" />
<link href="assets/stylesheets/alertify.default.css" rel="stylesheet" />
<link rel="shortcut icon" href="assets/images/favicon.ico">
<script src="assets/scripts/jquery-2.1.1.min.js"></script>
<script src="scripts/jquery.validate.js"></script>
<script src="scripts/bootstrap.min.js"></script>
<script src="assets/scripts/demo.js"></script>
<script src="assets/scripts/cart_list.js"></script>
<script src="scripts/coder_member.js"></script>
<script src="scripts/shoppingcar.js"></script>
<script src="scripts/cookie.js"></script>
<script src="scripts/alertify.min.js"></script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div id="gps"> <a href="index.html">home</a> <span> / </span><a href="order-step1.html">shopping car</a> </div>
<div class="orderStep">
    <div class="step1"></div>
    <div class="step2 active"></div>
    <div class="step3"></div>
</div>
<div class="orderContent">
    <form name="order_pay_form" id="order_pay_form" action="" method="">
        <div class="order-form">
            <div class="checkout-type">
                <div class="chk-type active"> <span class="en">SIGN IN TO CHEACK OUT</span><br/>
                    會員登入 </div>
                <div class="chk-type"> <span class="en"> CREATE AN ACCOUNT</span><br/>
                    加入免費會員方便快速結帳 </div>
            </div>
            <fieldset>
                <lable for="recipient_name" class="field-grop half">
                    <p class="fieldTitle"> 姓名 <span class="req">*</span></p>
                    <input name="recipient_name" type="text" id="recipient_name" value="<?php echo $row_member["member_name"]; ?>" />
                </lable>
                <lable for="recipient_mobile" class="field-grop half">
                    <p class="fieldTitle"> 手機 <span class="req">*</span></p>
                    <input name="recipient_mobile" type="text" id="recipient_mobile" value="<?php echo $row_member["member_mobile"]; ?>" />
                </lable>
                <lable for="recipient_email" class="field-grop">
                    <p class="fieldTitle"> 電子郵件 <span class="req">*</span></p>
                    <input name="recipient_email" type="text" id="recipient_email" value="<?php echo $row_member["member_email"]; ?>" />
                </lable>
                <lable for="recipient_address" class="field-grop">
                    <p class="fieldTitle"> 地址 <span class="req">*</span></p>
                    <input name="recipient_address" type="text" id="recipient_address" value="<?php echo $row_member["member_address"]; ?>" />
                </lable>
            </fieldset>
			<p class="fieldTitle mb10"> 運送方式 <span class="req">*</span></p>
			<select class="mb10" name="recipient_way" id="recipient_way" value="<?php echo $row_member["member_address"]; ?>">
				<option value="1" selected>台灣</option>
				<option value="2">海外</option>
			</select>
			<div class="mb10" id="recipient_optionTW" >
				<input type="radio" name="recipient_wayOption" id="recipient_wayOption" value="1" />店到店
				<br>
				<font color="red">超商取貨只配合全家，通常寄出約兩個工作天到貨。</font>
				<br>
				<font color="red">商品到達指定門市後超過七天未領取，包裹會自動被退回轉運中心喔！</font>
				<br>
				<input class="mt10" type="radio" name="recipient_wayOption" id="recipient_wayOption" value="2" />貨運宅配
				<br>
				<font color="red">宅配人員寄送到您指定收件地址，約兩個工作天內到貨。</font>
			</div>
			<div class="mb10" id="recipient_optionElse" >
				<input type="radio" name="recipient_wayOption" id="recipient_wayOption" value="3" />國際配送（中國、香港、澳門）
				<br>
				<font color="red">港澳地區約5-6天到貨、中國地區約7-12天到貨。</font>
				<br>
				<input class="mt10" type="radio" name="recipient_wayOption" id="recipient_wayOption" value="4" />國際配送（新加坡、馬來西亞）
				<br>
				<font color="red">新加坡、馬來西亞寄送約8-10天到貨。</font>
			</div>
            <input type="button" onclick="history.back()" class="btn-white" style="cursor: pointer;" value="back">
            <input name="haveBag" type="hidden" value="<?php echo $car -> chkHaveBag(); ?>" />
            <input name="totalPrice" type="hidden" value="<?php echo $total; ?>" />
            <!--<div href="javascript: history.go(-1)">back</div>-->
        </div>
        <div class="order-list">
            <div class="your-order">
                <div class="youo-title"></div>
                <table class="tab-list">
                    <thead>
                        <tr>
                            <td width="200" align="left">PRODUCT</td>
                            <td align="right">PRICE</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						for($i = 0; $i < count($carItem); $i++){
							$product_id = $carItem[$i] -> product_id;
							$product_sno = $carItem[$i] -> product_sno;
							$product_name_en = $carItem[$i] -> product_name_en;
							$product_name_tw = $carItem[$i] -> product_name_tw;
							$sell_price = $carItem[$i] -> sell_price;
							$amount = $carItem[$i] -> amount;
							$subtotal = $carItem[$i] -> subtotal;
							$pic = $carItem[$i] -> pic;
						?>
                        <tr>
                            <td width="190" style="word-wrap: break-word; word-break: break-all;"><?php echo $product_name_tw; ?>*<?php echo $amount; ?></td>
                            <td><?php echo $subtotal; ?></td>
                        </tr>
                        <?php
						}
						?>
                        <tr>
                            <td width="190" style="word-wrap: break-word; word-break: break-all;">運費</td>
                            <td class="freight">0</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>TOTAL</td>
                            <td class="total"><?php echo $total; ?></td>
                        </tr>
                    </tfoot>
                </table>
                <?php
				foreach($ary_payment_type as $key => $val){
				?>
                <div class="chk-way">
                    <h4 class="chk-title" style="width:100%;line-height:1.2em;">
                        <span><input name="payment_type" type="radio" value="<?php echo $key; ?>"></span>
                        <span><?php echo $val; ?></span></h4>
                </div>
                <?php
				}
				?>
            </div>
            <input type="button" class="btn-white" style="cursor: pointer;" id="order_pay_btn" value="確認訂單">
        </div>
    </form>
</div>
<?php include_once($inc_path."page/_footer.php"); ?>
<?php $db -> close(); ?>
</body>
</html>