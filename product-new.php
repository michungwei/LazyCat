<?php
include_once("_config.php");
include_once($inc_path."lib/_banner.php");
include_once($inc_path."lib/_product.php");
include_once($inc_path."_getpage.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$rows_subbanner = Banner::getSubBanner();

$rows_type = Product::getNewTypeList();
if(count($rows_type) <= 0){
	script("最新商品即將上架!敬請等待!");
}
$type_id = get("type_id", 1) != "" ? get("type_id", 1) : "00";

$rows_hot = Product::getHotPro();
$row_hprice = Product::getNewMAXPrice($type_id);
$row_lprice = Product::getNewMINPrice($type_id);

$height_price = get("height_price", 1) != "" ? get("height_price", 1) : $row_hprice["height_price"];
$low_price = get("low_price", 1) != "" ? get("low_price", 1) : $row_lprice["low_price"];
$max_value_price = get("max_value_price", 1) != "" ? get("max_value_price", 1) : $row_hprice["height_price"];
$min_value_price = get("min_value_price", 1) != "" ? get("min_value_price", 1) : $row_lprice["low_price"];

$order = get("order", 1) != "" ? get("order", 1) : "9";
$page = get("page", 1) != "" ? get("page", 1) : 1;

$query_string = "_".$type_id."_".$max_value_price."_".$min_value_price."_".$order;
$sql = Product::getNewProductList($type_id, $max_value_price, $min_value_price, $order);
getSql($sql, 15, $query_string);
$rows_product = $db -> fetch_all_array($sql);

$car = new shoppingCar();
$carItem = $car-> getCar();
$u = count($carItem);

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
<script type="text/javascript" src="assets/scripts/jquery-2.1.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="assets/scripts/demo.js"></script>
<script src="assets/scripts/product_new.js"></script>
<script src="scripts/coder_member.js"></script>
<script src="scripts/cookie.js"></script>
<script>
var min_price = parseInt("<?php echo $low_price; ?>");
var max_price = parseInt("<?php echo $height_price; ?>");
var min_value_price = parseInt("<?php echo $min_value_price; ?>");
var max_value_price = parseInt("<?php echo $max_value_price; ?>");
var order = "<?php echo $order; ?>";
var pic_path = "<?php echo $web_path_product; ?>"+"m";
$(document).ready(function(e) {	
    select_price(max_price, min_price, max_value_price, min_value_price, "new");
	selectOrder(order);
});
</script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<input name="type_id" type="hidden" id="type_id" value="<?php echo $type_id; ?>">
<input name="serial_id" type="hidden" id="serial_id" value="">
<input name="max_value_price" type="hidden" id="max_value_price" value="<?php echo $max_value_price; ?>">
<input name="min_value_price" type="hidden" id="min_value_price" value="<?php echo $min_value_price; ?>">
<input name="order" type="hidden" id="order" value="<?php echo $order; ?>">
<input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
<div id="subbanner">
    <div class="sub-list"> 
    <?php 
		if($rows_subbanner[1]["subbanner_pic_link"] != ""){
	?>
    	<a href="<?php echo $rows_subbanner[1]["subbanner_pic_link"]; ?>"><img src="<?php echo $web_path_subbanner."m".$rows_subbanner[1]["subbanner_pic"]; ?>" width="1280" height="300" alt="" /></a> 
    <?php
		}else{
	?>
    	<img src="<?php echo $web_path_subbanner."m".$rows_subbanner[1]["subbanner_pic"]; ?>" width="1280" height="300" alt="" />
    <?php
		}
	?>
    </div>
</div>
<div id="gps"> <a href="index.html">home</a><span> / </span><a href="wishlist.html">whishist</a> </div>
<div class="product-wrap">
    <div class="prod-type">
        <h3 class="prod-title">What's news</h3>
        <ul class="type-list">
            <?php
				foreach($rows_type as $row_type){
			?>
            <li class="type-item"><a href="product-new_<?php echo $row_type["product_type_id"]; ?>_<?php echo $row_type["height_price"]; ?>_<?php echo $row_type["low_price"]; ?>_9_1.html"><?php echo $ary_product_type[$row_type["product_type_id"]]; ?> <span class="type-num">(<?php echo $row_type["amount"]; ?>)</span></a></li>
            <?php
				}
			?>
        </ul>
        <h3 class="prod-title">FILTER BY PRICE</h3>
        <div class="price-filer">
            <div id="slider-range"></div>
            <small>
            <label for="amount">Price: </label>
            <input type="text" id="amount" readonly style="border:0; color:#9c9c9c;width:170px" maxlength="50">
            </small> </div>
    </div>
    <div class="prod-list">
        <div class="price-sort">
            <div class="numSelect"> <span class="beSelect"> 不限定 </span>
                <ul class="num">
                    <li isVal="9"> 不限定 </li>
                    <li isVal="1"> 價格高到低 </li>
                    <li isVal="2"> 價格低到高 </li>
                </ul>
            </div>
        </div>
        <div class="prod-all">
            <?php
				foreach($rows_product as $row_product){
			?>
            <a href="product_<?php echo $row_product["product_type_id"]; ?>_<?php echo $row_product["product_id"]; ?>.html">
            <div class="pro-item pro_list" pic1="<?php echo $web_path_product."m".$row_product["product_pic1"]; ?>" pic2="<?php echo $web_path_product."m".$row_product["product_pic2"]; ?>"> <img class="proi-img" src="<?php echo $web_path_product."m".$row_product["product_pic1"]; ?>" alt="" width="240" height="320" />
                <p class="proi-en"><?php echo $row_product["product_name_en"]; ?></p>
                <p class="proi-zh"><?php echo $row_product["product_name_tw"]; ?></p>
                <p class="price">TWD.<?php echo $row_product["product_sell_price"]; ?></p>
            </div>
            </a>
            <?php
				}
			?>
            <ul class="prod-page">
                <?php showPage2("new"); ?>
            </ul>
        </div>
    </div>
</div>
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
                <li class="pro-item pro_hot" pic1="<?php echo $web_path_product."m".$row_hot["product_pic1"]; ?>" pic2="<?php echo $web_path_product."m".$row_hot["product_pic2"]; ?>"> <img class="proi-img" src="<?php echo $web_path_product."m".$row_hot["product_pic1"]; ?>" alt="" height="320" width="240" />
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