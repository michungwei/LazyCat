<?php
include_once("_config.php");
include_once($inc_path."lib/_product.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

function getParameter($pname, $defaultStr){
    return isset($_POST[$pname])?$_POST[$pname]:$defaultStr;
}
$stName = getParameter('stName', '選擇門市');
$stCate = getParameter('stCate', 'TFM');
$stCode = getParameter('stCode', '0');

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$car = new shoppingCar();
$freight = $car -> calculate();
$total = $car -> total;
$carItem = $car -> getCarFromDB();
$u = count($carItem);
$disNameAry = array();
$disIdStr = '';

if(!isLogin()){
	script("請先登入會員!", "sign.html");
}else{
	if($u == 0){
		script("您目前未購買任何商品,請繼續購物!!");
	}
	$memberid = $_SESSION["session_id"];
	$row_member = CoderMember::getList($memberid);

    $disIdAry = explode(',', $row_member['member_discharge_id']);
    $disAmtAry = explode(',', $row_member['member_discharge_amount']);
    $cnt = 0;
    for($i = 0; $i < sizeof($disIdAry) - 1; $i++)
    {
        if(CoderMember::getDischarge($disIdAry[$i]))
        {
            $disNameAry[$cnt] = CoderMember::getDischarge($disIdAry[$i]);
            $disIdStr .= $disIdAry[$i].',';
            $cnt ++;
        }
    }
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
<script>
$(document).ready(function(){

    var disCnt = <?php echo sizeof($disNameAry); ?>;
    var freight = <?php echo $freight; ?>;
    var disSum = 0;
    var totalPrice = $('input[name="totalPrice"]').val();
    var promo_code = $('input[name="recipient_promoCode"]').val();
    var disAmtStr = '';
    for(var i = 0; i < disCnt; i++)
    {
        $("#disRange"+i).on('input',{index:""+i}, changeDisAmtTxt);
        $("#disRange"+i).on('change', function(){
            calTotalPrice(
                parseInt($('input[name="totalPrice"]').val()) + parseInt($('input[name="freight"]').val()),
                $('input[name="recipient_promoCode"]').val(),
                $('input[name="discharge_amount"]').val());
        });
        disSum += parseInt($("#disRange"+i).val());
        disAmtStr += $("#disRange"+i).val() + ',';
    }
    if(disSum > 0)
    {
        $('#discharge').show();
        $('.discharge').replaceWith("<td class='discharge'>- " + disSum +"</td>");
        $('input[name="discharge_amount"]').val(disSum);
        $('input[name="dischargeStr"]').val(disAmtStr);
    }
    else
        $('#discharge').hide();
    calTotalPrice(parseInt(totalPrice) + parseInt($('input[name="freight"]').val()), promo_code, disSum);

    function changeDisAmtTxt(event)
    {
        var index = event.data.index;
        var res = document.getElementById("disFont"+index);
        var p = document.getElementById("disRange"+index);
        //console.log("range slider!! " + $("#disRange"+index).val());
        //res.innerHTML = "TWD $" + p.value;
        disChargeHandler();
    }
    function disChargeHandler()
    {
        var disSum = 0;
        var disAmtStr = '';
        for(var i = 0; i < disCnt; i++)
        {
            //$("#disRange"+i).on('input',{index:""+i}, changeDisAmtTxt);
            disSum += parseInt($("#disRange"+i).val());
            disAmtStr += $("#disRange"+i).val() + ',';
        }
        if(disSum > 0)
        {
            $('#discharge').show();
            $('.discharge').replaceWith("<td class='discharge'>- " + disSum +"</td>");
            $('input[name="discharge_amount"]').val(disSum);
            $('input[name="dischargeStr"]').val(disAmtStr);
        }
        else
        {
            $('#discharge').hide();
            $('input[name="discharge_amount"]').val(disSum);
        }
    }
});
</script>
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
                <input type="radio" <?php /*if($stCode != "0")*/ echo "checked";?> name="recipient_wayOption" id="recipient_wayOption" value="1" />店到店
                <input type="button" name="ezship_choose" id="ezship_choose" value="<?php echo $stName; ?>" onclick="window.location.href = 'http://map.ezship.com.tw/ezship_map_web_2014.jsp?rtURL=<?php echo $web_url;?>order-step2.html';"/>
                <input type="hidden" name="ezship_name" id="ezship_name" value="<?php echo $stName; ?>"/>
                <input type="hidden" name="ezship_cate" id="ezship_cate" value="<?php echo $stCate; ?>"/>
                <input type="hidden" name="ezship_code" id="ezship_code" value="<?php echo $stCode; ?>"/>
                <!--<input type="hidden" name="ezship_type" id="ezship_type" value="<?php echo $stName; ?>"/>
                <a><?php echo $stName; ?></a>-->
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
            <lable for="recipient_promoCode" class="field-grop">
                    <p class="fieldTitle"> 折扣碼 </p>
                    <input name="recipient_promoCode" type="text" id="recipient_promoCode" value="" />
            </lable>
            <label for="recipient_discharge" class="field-grop">
                <?php 
                if(sizeof($disNameAry) > 0) 
                {
                ?>
                <p class="fieldTitle"> 使用抵用金 </p>
                <?php
                }
                ?>
                <br>
                <?php 
                for($i = 0; $i < sizeof($disNameAry); $i++)
                {
                    if($disAmtAry[$i] > 0)
                    {
                ?>
                    <div id="disContain">
                        <div id="disName">
                            <font>&nbsp;&nbsp;<?php echo $disNameAry[$i]['discharge_name'];?></font>
                        </div>
                        <div id="disAmount">
                            <input id="disRange<?php echo $i;?>" name="discharge" type="number" min="0" max="<?php echo $disAmtAry[$i];?>" value="0" >&nbsp;&nbsp;<font id="disFont<?php echo $i;?>" color="gray" style="line-height:50px;">(有TWD $<?php echo $disAmtAry[$i];?>可使用)</font>
                        </div>
                    </div>
                <?php
                    }
                }
                ?>
            </lable>
            <input type="button" onclick="history.back()" class="btn-white" style="cursor: pointer;" value="back">
            <input name="haveBag" type="hidden" value="<?php echo $car -> chkHaveBag(); ?>" />
            <input name="totalPrice" type="hidden" value="<?php echo $total; ?>" />
            <input name="freight" type="hidden" value="<?php echo $freight; ?>" />
            <input id="discharge_amount" name="discharge_amount" type="hidden" value="0" />
            <input id="dischargeStr" name="dischargeStr" type="hidden" value="" />
            <input id="dischargeIdStr" name="dischargeIdStr" type="hidden" value="<?php echo $disIdStr; ?>" />
            <input id="oriDischargeStr" name="oriDischargeStr" type="hidden" value="<?php echo $row_member['member_discharge_amount']; ?>" />
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
                        $sum = 0;
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
                            $sum += $subtotal;
						}
						?>
                        <tr>
                            <td width="190" style="word-wrap: break-word; word-break: break-all;">運費</td>
                            <td class="freight">0</td>
                        </tr>
                        <tr id="promo_money">
                            <td width="190" style="word-wrap: break-word; word-break: break-all; font-color: 'red'">折扣金額</td>
                            <td class="promo_money">- 0</td>
                        </tr>
                        <tr id="promo_discount">
                            <td width="190" style="word-wrap: break-word; word-break: break-all; font-color: 'red'">折扣%數</td>
                            <td class="promo_discount">x 1</td>
                        </tr>
                        <tr id="discharge">
                            <td width="190" style="word-wrap: break-word; word-break: break-all; font-color: 'red'">抵用金</td>
                            <td class="discharge">- 0</td>
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
                if(1000 - $sum > 0)
                {
                    $temp = 1000 - $sum;
				?>
                <div class="buy_hint"><font size="1" color="red">購物滿1000元免運費，離免運費還差<?php echo $temp; ?>元</font></div>
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