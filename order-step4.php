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
//	if($u == 0){
//		script("您目前未購買任何商品,請繼續購物!!", "product-list.html");
//	}
	$memberid = $_SESSION["session_id"];
	//$row_member = CoderMember::getList($memberid);
}

$rows_hot = Product::getHotPro();
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
<link href="assets/stylesheets/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="assets/images/favicon.ico">
<script src="assets/scripts/jquery-2.1.1.min.js"></script>
<script src="scripts/jquery.validate.js"></script>
<script src="scripts/coder_member.js"></script>
<script src="scripts/shoppingcar.js"></script>
<script src="scripts/cookie.js"></script>
<script src="assets/scripts/demo.js"></script>
<script src="assets/scripts/order.js"></script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div id="gps"> <a href="index.html">home</a><span> / </span><a href="order-step1.html">shopping car</a> </div>
<div class="orderStep">
    <div class="step1"></div>
    <div class="step2"></div>
    <div class="step3 active"></div>
</div>
<div class="orderContent"> <span class="bigW">SORRY!</span><span>您尚未完成您的的訂單。如有疑問請聯絡客服人員！</span> </div>
<div class="hot"> 
    <div class="hot-title"></div>
    <div class="new-wrap hot_box">
        <div class="arrow-left hot_left"></div>
        <div class="arrow-right hot_right"></div>
        <div class="new-outer hot_list">
            <ul class="new-list">
                <?php
					foreach($rows_hot as $row_hot){
				?>
                <a href="product_<?php echo $row_hot["product_type_id"]; ?>_<?php echo $row_hot["product_id"]; ?>.html">
                <li class="pro-item"> <img class="proi-img" src="<?php echo $web_path_product."m".$row_hot["product_pic1"]; ?>" alt="" height="320" width="240" />
                    <p class="proi-en"><?php echo $row_hot["product_name_en"]; ?></p>
                    <p class="proi-zh"><?php echo $row_hot["product_name_tw"]; ?></p>
                    <p class="price">TWD.<?php echo $row_hot["product_sell_price"]; ?></p>
                </li>
                </a>
                <?php
					}
				?>
            </ul>
        </div>
    </div>
</div>
<?php include_once($inc_path."page/_footer.php"); ?>
<?php $db -> close(); ?>
</body>
</html>