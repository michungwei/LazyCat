<?php
include_once("_config.php");
include_once($inc_path.'lib/_shoppingcar.php');

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
<link rel="shortcut icon" href="assets/images/favicon.ico">
<script src="assets/scripts/jquery-2.1.1.min.js"></script>
<script src="scripts/jquery.validate.js"></script>
<script src="scripts/bootstrap.min.js"></script>
<script src="assets/scripts/demo.js"></script>
<script src="assets/scripts/about.js"></script>

</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div class="map"> <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3614.729892992575!2d121.50596490000002!3d25.043239!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3442a909047ede5f%3A0xb9e1ef28c2d46460!2zMTA45Y-w5YyX5biC6JCs6I-v5Y2A6KW_5a-n5Y2X6LevNzItMeiZn-ilv-mWgOaWsOWuvw!5e0!3m2!1szh-TW!2stw!4v1419937651330" width="1280" height="450" frameborder="0" style="border:0"></iframe> </div>
<div id="gps"> <a href="index.html">home</a><span> / </span><a href="product-list.html">shopping car</a> </div>
<div class="company-info">

    <div class="contact">
        <div class="title"></div>
        <div class="cont-form">
            <form action="" method="post" id="contact_form">
                <fieldset>
                    <input name="email" type="text" id="email" onfocus="if(this.value=='your email') {this.value='';this.style.color='#000';}" onblur="if(this.value=='') {this.value='your email';this.style.color='#858585';};" value="your email" style="color:#858585;"/>
                    <textarea name="content" id="content" cols="30" rows="10" onfocus="if(this.value=='your message...') {this.value='';this.style.color='#000';}" onblur="if(this.value=='') {this.value='your message...';this.style.color='#858585';};" style="color:#858585;"></textarea>
                    <input name="cont_btn" type="button" class="btn-white" id="cont_btn" value="SEND / 寄送">
                    <!--<button  type="submit">SEND / 寄送</button>-->
                </fieldset>
            </form>
        </div>
    </div>
</div>
<?php include_once($inc_path."page/_footer.php"); ?>
</body>
</html>