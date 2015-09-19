<?php
include_once("_config.php");

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
<script>
$(document).ready(function(e) {

});
</script>

</head>
<body>
<div class="forget_pwd" style="padding-top:50px;">
    <form id="forgetForm" name="forgetForm">
        <table style="margin:0 auto;">
            <tr>
                <td colspan="2">請填寫會員註冊電子信箱</td>
            </tr>
            <tr>
                <td colspan="2" height="20"><br /></td>
            </tr>
            <tr>
                <td width="70">E-Mail</td>
                <td width="255"><input name="email" type="text" id="email" style="width:100%;"/></td>
            </tr>
            <tr>
                <td colspan="2" height="20"><br /></td>
            </tr>
            <tr>
                <td colspan="2"><input name="forget_pwd_btn" type="button" value="送出" id="forget_pwd_btn" class="btn-block" style="width:100%;"></td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>