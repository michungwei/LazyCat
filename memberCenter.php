<?php
include_once("_config.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

$member_id = "";
if(isLogin()){
	$member_id = get("member_id", 1);
	//$member_id = $_SESSION["session_id"];
	if($member_id < 0 || $member_id == ""){
		script("資料傳輸錯誤,請再試一次!");
	}
}else{
	script("請先登入會員!", "sign.html");	
}

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();
$row = CoderMember::getList($member_id);
if($row){
	$account = $row["member_account"];
	$name = $row["member_name"];
	$name = $row["member_name"];
	$password = $row["member_password"];
	$birthday = $row["member_birthday"];
	$email = $row["member_email"];
	$mobile_nation = $row["member_mobile_nation"];
	$mobile = $row["member_mobile"];
	$address = $row["member_address"];
	$year = substr($birthday, 0, 4);
	$month = substr($birthday, 4, 2);
	$day = substr($birthday, 6, 2);
}

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
<link href="assets/stylesheets/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
<link href="assets/stylesheets/alertify.core.css" rel="stylesheet" />
<link href="assets/stylesheets/alertify.default.css" rel="stylesheet" />
<link rel="shortcut icon" href="assets/images/favicon.ico">
<script src="assets/scripts/jquery-2.1.1.min.js"></script>
<script src="scripts/bootstrap.min.js"></script>
<script src="scripts/jquery.validate.js"></script>
<script src="scripts/alertify.min.js"></script>
<script src="scripts/coder_member.js"></script>
<script src="scripts/memberdo.js"></script>
<script src="assets/scripts/demo.js"></script>
<script>
var year = <?php echo $year; ?>;
var month = <?php echo $month; ?>;
var day = <?php echo $day; ?>;

$(document).ready(function(e) {
    set_ymd_date(1911, year, month, day);
});
</script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div class="company-info">
    <div class="signin sep-mr">
        <form action="" id="modifyForm" name="modifyForm">
            <fieldset>
                <table class="sign-tb">
                    <tr>
                        <td></td>
                        <td><span class="mem-title">會員中心</span></td>
                    </tr>
                    <tr>
                        <td>會員帳號</td>
                        <td><p class="memNum"><?php echo $account; ?></p></td>
                    </tr>
                    <tr>
                        <td>中文姓名</td>
                        <td><input name="name" type="text" id="name" value="<?php echo $name; ?>" /></td>
                    </tr>
                    <tr>
                        <td>電子郵件</td>
                        <td><input name="email" type="text" id="email" value="<?php echo $email; ?>" /></td>
                    </tr>
                    <tr>
                        <td>生日</td>
                        <td><select name="ymd_year" id="ymd_year">
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
                                <option value="886" <?php echo ($mobile_nation == 886) ? "selected" : ""; ?>>Taiwan (+886)</option>
                                <option value="86" <?php echo ($mobile_nation == 86) ? "selected" : ""; ?>>China (+86)</option>
                                <option value="852" <?php echo ($mobile_nation == 852) ? "selected" : ""; ?>>Hong Kong (+852)</option>
                                <option value="853" <?php echo ($mobile_nation == 853) ? "selected" : ""; ?>>Macao (+853)</option>
                                <option value="60" <?php echo ($mobile_nation == 60) ? "selected" : ""; ?>>Malaysia (+60)</option>
                                <option value="65" <?php echo ($mobile_nation == 65) ? "selected" : ""; ?>>Singapore (+65)</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input name="mobile" type="text" id="mobile" value="<?php echo $mobile; ?>"/></td>
                    </tr>
                    <tr>
                        <td>聯絡地址</td>
                        <td><input name="address" type="text" id="address" value="<?php echo $address; ?>"/>
                            <br>
                            <a style="color:#FF0000; font-size:7px;">請確認填寫地址完整無誤，避免錯誤導致無法收件</a>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input name="modify_btn" type="button" class="btn-block" value="修改完成" id="modify_btn" style="width:100%;"></td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>
    <div class="signup">
        <form action="" id="passwordForm" name="passwordForm">
            <fieldset>
                <table class="sign-tb">
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td>輸入原密碼</td>
                        <td><input name="oldpassword" type="password" id="oldpassword" /></td>
                    </tr>
                    <tr>
                        <td>新密碼</td>
                        <td><input name="password" type="password" id="password" /></td>
                    </tr>
                    <tr>
                        <td>在輸入一次</td>
                        <td><input name="repassword" type="password" id="repassword" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input name="pwd_btn" type="button" class="btn-block" value="密碼修改" id="pwd_btn" style="width:100%;"></td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>
</div>
<?php include_once($inc_path."page/_footer.php"); ?>
</body>
</html>