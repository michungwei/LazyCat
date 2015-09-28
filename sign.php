<?php
include_once("_config.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

$page = (get("page", 1) != "") ? get("page", 1) : "";
$pro_id = (get("pro_id", 1) != "") ? get("pro_id", 1) : "";
$type_id = (get("type_id", 1) != "") ? get("type_id", 1) : "";

$car = new shoppingCar();
$carItem = $car-> getCar();
$u = count($carItem);
?>
<!DOCTYPE html>
<html lang="zh_tw">
<head>
<meta charset="UTF-8">
<meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible'>
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="description" content="<?php echo $description; ?>">
<meta name="author" content="<?php echo $author; ?>">
<meta name="copyright" content="<?php echo $copyright; ?>">
<title><?php echo $web_name; ?></title>
<link href="assets/stylesheets/screen.css" media="screen, projection" rel="stylesheet" />
<!--bill add 20141018-->
<link href="assets/stylesheets/alertify.core.css" rel="stylesheet" />
<link href="assets/stylesheets/alertify.default.css" rel="stylesheet" />
<link media="screen" rel="stylesheet" href="ui/colorbox/colorbox.css" />
<link rel="shortcut icon" href="assets/images/favicon.ico">
<!--bill add 20141018-->
<script src="assets/scripts/jquery-2.1.1.min.js"></script>
<script src="assets/scripts/demo.js"></script>
<!--bill add 20141018-->
<script src="scripts/jquery.validate.js"></script>
<script src="scripts/bootstrap.min.js"></script>
<script src="scripts/alertify.min.js"></script>
<script src="scripts/coder_member.js"></script>
<script src="scripts/memberdo.js"></script>
<script src="scripts/cookie.js"></script>
<script src="scripts/md5.js"></script>
<script src="ui/colorbox/jquery.colorbox-min.js"></script>
<script>
$(document).ready(function(e) {
	demo();
	set_ymd_date(1911,"","","");
	
	$("div.signin").on("click", "a.for_pwd", function(){
		parent.$.colorbox({
			href:"forget_pwd.html",
			iframe:true, 
			width:"410", 
			height:"300",
			scrolling:false
		});
	});
    $("#agreement").click(function(){
        $.colorbox({
           href :   "agreement.php", //在燈箱中要顯示的html字段
           iframe : true,
           width : 700, //燈箱中間區塊的寬度
           height : 600, //燈箱中間區塊的高度
        });
    });
});

</script>
<!--bill add 20141018-->
<script>  
    function refresh_code(){   
        document.getElementById("imgcode").src="captcha.php";   
    }   
</script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div class="sign-info">
    <div class="signin bdrdash">
        <form id="login_member_form">
            <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
            <input name="pro_id" type="hidden" id="pro_id" value="<?php echo $pro_id; ?>">
            <input name="type_id" type="hidden" id="type_id" value="<?php echo $type_id; ?>">
            <table class="sign-tb" id="login_member_table">
                <tr>
                    <td width="70"></td>
                    <td><div class="ui-s-sign"></div></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td width="70">會員帳號</td>
                    <td width="255"><input name="account" type="text" id="account"/></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>會員密碼</td>
                    <td><input name="password" type="password" id="password" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:right;"><span class="sign_span">
                        <label for="remember">
                            <input name="remember" type="checkbox" id="remember" value="1" />
                            記住帳號</label>
                        </span><span> <a href="javascript:;" class="for_pwd">忘記密碼</a></span></td>
                </tr>
                <tr>
                    <td>請輸入右圖驗證碼</td>
                    <td><img id="imgcode" src="captcha.php" onclick="refresh_code()" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input name="checkword" type="text" id="checkword" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>我已經詳細閱讀並同意<span id="agreement">LAZYCAT服務條款</span></td>
                </tr>
                <tr>
                    <td></td>
                    <td><!--<button id="login_member_btn" class="btn-block" >登入</button>-->
                        
                        <input name="" type="button" value="登入" id="login_member_btn" class="btn-block"></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="signup">
        <form id="join_member_form">
            <table class="sign-tb" id="join_member_table">
                <tr>
                    <td width="70"></td>
                    <td><div class="ui-s-create"></div></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td width="70">電子郵件</td>
                    <td width="255"><input name="acc" type="text" id="acc" />
                        <br>
                        <span><span style="color:#FF0000;">*</span>電子郵件即為會員帳號</span></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>會員密碼</td>
                    <td><input name="pwd" type="password" id="pwd" />
                        <br>
                        <span><span style="color:#FF0000;">*</span>請輸入8-12位的英文大小寫字母或數字</span></td>
                </tr>
                <tr>
                    <td>確認密碼</td>
                    <td><input name="repwd" type="password" id="repwd" /></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>姓名</td>
                    <td><input name="name" type="text" id="name" /></td>
                </tr>
                <!--<tr>
                    <td>電子郵件</td>
                    <td><input name="email" type="text" id="email" /></td>
                </tr>-->
                <tr>
                    <td>性別</td>
                    <td>
                        <select name="sex" id="sex">
                            <option value="0">女</option>
                            <option value="1">男</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>生日</td>
                    <td><!--<input name="birthday" type="text" id="birthday" />-->
                        
                        <select name="ymd_year" id="ymd_year">
                            <option value="">年</option>
                        </select>
                        <select name="ymd_month" id="ymd_month">
                            <option value="">月</option>
                        </select>
                        <select name="ymd_day" id="ymd_day">
                            <option value="">日</option>
                        </select></td>
                </tr>
                <tr>
                    <td>手機</td>
                    <td><select name="mobile_national_number" id="mobile_national_number">
                            <option value="886" selected>Taiwan (+886)</option>
                            <option value="86" >China (+86)</option>
                            <option value="852" >Hong Kong (+852)</option>
                            <option value="853" >Macao (+853)</option>
                            <option value="60" >Malaysia (+60)</option>
                            <option value="65" >Singapore (+65)</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<td></td>
                    <td><input name="mobile" type="text" id="mobile" /></td>
                </tr>
                <tr>
                    <td>郵遞區號</td>
                    <td><input name="address_code" type="text" id="address_code" /></td>
                </tr>
                <tr>
                    <td>地址</td>
                    <td><select name="address_country" id="address_country">
                            <option value="台灣" selected>台灣</option>
                            <option value="中國" >中國</option>
                            <option value="香港" >香港</option>
                            <option value="澳門" >澳門</option>
                            <option value="馬來西亞" >馬來西亞</option>
                            <option value="新加坡" >新加坡</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <select name="address_city" id="address_city">
                            <option value="" selected>縣市</option>
                            <option value="基隆市" >基隆市</option>
                            <option value="台北市" >台北市</option>
                            <option value="新北市" >新北市</option>
                            <option value="桃園縣" >桃園縣</option>
                            <option value="新竹市" >新竹市</option>
                            <option value="新竹縣" >新竹縣</option>
                            <option value="苗栗縣" >苗栗縣</option>
                            <option value="台中市" >台中市</option>
                            <option value="南投縣" >南投縣</option>
                            <option value="彰化縣" >彰化縣</option>
                            <option value="雲林縣" >雲林縣</option>
                            <option value="嘉義市" >嘉義市</option>
                            <option value="嘉義縣" >嘉義縣</option>
                            <option value="台南市" >台南市</option>
                            <option value="高雄市" >高雄市</option>
                            <option value="屏東縣" >屏東縣</option>
                            <option value="宜蘭縣" >宜蘭縣</option>
                            <option value="花蓮縣" >花蓮縣</option>
                            <option value="台東縣" >台東縣</option>
                            <option value="澎湖縣" >澎湖縣</option>
                            <option value="金門縣" >金門縣</option>
                            <option value="馬祖" >馬祖</option>
                        </select>
                        <input name="address" type="text" id="address" />
                        <br>
                        <a style="color:#FF0000; font-size:7px;">請確認填寫地址與郵遞區號完整無誤，避免錯誤導致無法收件</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><!--<button id="join_member_btn" class="btn-block">加入會員</button>-->
                        
                        <input type="button" value="加入會員" id="join_member_btn" class="btn-block"></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php include_once($inc_path."page/_footer.php"); ?>
</body>
</html>