<?php
include_once("_config.php");
include_once($inc_path."lib/_product.php");
include_once($inc_path."_getpage.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$type_id = get("type_id", 1) != "" ? get("type_id", 1) : 1;
$serial_id = get("serial_id", 1) != "" ? get("serial_id", 1) : "00";

$rows_serial = Product::getSerialList($type_id);
$rows_hot = Product::getHotPro();

$row_hprice = Product::getMAXPrice($type_id, $serial_id);
$row_lprice = Product::getMINPrice($type_id, $serial_id);


$height_price = get("max_price", 1) != "" ? get("max_price", 1) : $row_hprice["height_price"];
$low_price = get("min_price", 1) != "" ? get("min_price", 1) : $row_lprice["low_price"];
$order = get("order", 1) != "" ? get("order", 1) : "9";
$page = get("page", 1) != "" ? get("page", 1) : 1;

$query_string = $type_id."_".$serial_id."_".$height_price."_".$low_price."_".$order;
$sql = Product::getProductList($type_id, $serial_id, $height_price, $low_price, $order);
getSql($sql, 2, $query_string);
$rows_product = $db -> fetch_all_array($sql);

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
<link href="assets/stylesheets/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/scripts/jquery-2.1.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script type="text/javascript" src="assets/scripts/demo.js"></script>
<script type="text/javascript" src="assets/scripts/product_list.js"></script>
<script>
var min_price = <?php echo $low_price; ?>;
var max_price = <?php echo $height_price; ?>;
var order = <?php echo $order; ?>;
$(document).ready(function(e) {	
    select_price(min_price, max_price, "list");
	selectOrder(order);
});
</script>
</head>
<body>
<div id="bodycontent">
    <input name="type_id" type="hidden" id="type_id" value="<?php echo $type_id; ?>">
    <input name="serial_id" type="hidden" id="serial_id" value="<?php echo $serial_id; ?>">
    <input name="height_price" type="hidden" id="height_price" value="<?php echo $height_price; ?>">
    <input name="low_price" type="hidden" id="low_price" value="<?php echo $low_price; ?>">
    <input name="order" type="hidden" id="order" value="<?php echo $order; ?>">
    <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
    <div class="prod-type">
        <h3 class="prod-title"><?php echo $ary_product_type[$type_id]; ?></h3>
        <ul class="type-list">
            <?php
                    foreach($rows_serial as $row_serial){
                ?>
            <li class="type-item"><a href="javascript:;"><?php echo $row_serial["productserial_name"]; ?> <span class="type-num">(<?php echo $row_serial["amount"]; ?>)</span></a></li>
            <?php /*?><li class="type-item"><a href="product-list_<?php echo $type_id; ?>_<?php echo $row_serial["productserial_id"]; ?>_<?php echo $row_serial["height_price"]; ?>_<?php echo $row_serial["low_price"]; ?>_9_1.html"><?php echo $row_serial["productserial_name"]; ?> <span class="type-num">(<?php echo $row_serial["amount"]; ?>)</span></a></li><?php */?>
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
                    <li isVal="1"> price high to low </li>
                    <li isVal="2"> price low to high </li>
                </ul>
            </div>
        </div>
        <div class="prod-all">
            <?php
                    foreach($rows_product as $row_product){
                ?>
            <a href="product_<?php echo $row_product["product_type_id"]; ?>_<?php echo $row_product["product_id"]; ?>.html">
            <div class="pro-item"> <img class="proi-img" src="<?php echo $web_path_product.$row_product["product_pic1"]; ?>" alt="" width="240" height="320"/>
                <p class="proi-en"><?php echo $row_product["product_name_en"]; ?></p>
                <p class="proi-zh"><?php echo $row_product["product_name_tw"]; ?></p>
                <p class="price">TWD.<?php echo $row_product["product_sell_price"]; ?></p>
            </div>
            </a>
            <?php
                    }
                ?>
            <ul class="prod-page">
                <?php showPage2("list"); ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>