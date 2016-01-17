<?php
include_once("_config.php");
include_once($inc_path."lib/_product.php");
include_once($inc_path."lib/_banner.php");
include_once($inc_path."_getpage.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$rows_hot = Product::getHotPro();
$rows_subbanner = Banner::getSubBanner();

$keyword = get("keyword", 1) != "" ? get("keyword", 1) : "";
$order = get("order", 1) != "" ? get("order", 1) : "9";
$page = get("page", 1) != "" ? get("page", 1) : 1;
$query_string = "";

$type_id = get("type_id", 1) != "" ? get("type_id", 1) : 1;

if($keyword != ""){
	
	$rows_serial = Product::getSerialList2($keyword);
	if(count($rows_serial) <= 0){
		script("查無商品!");
	}
	
	$row_hprice = Product::getMAXPrice2($keyword);
	$row_lprice = Product::getMINPrice2($keyword);
	
	$max_price = get("max_price", 1) != "" ? get("max_price", 1) : $row_hprice["height_price"];
	$min_price = get("min_price", 1) != "" ? get("min_price", 1) : $row_lprice["low_price"];
	$max_value_price = get("max_value_price", 1) != "" ? get("max_value_price", 1) : $max_price;
	$min_value_price = get("min_value_price", 1) != "" ? get("min_value_price", 1) : $min_price;
	
	$query_string = "_".$keyword."_".$max_value_price."_".$min_value_price."_".$order;
	$sql = Product::getProductList2($keyword, $max_value_price, $min_value_price, $order);

}else{
	
	$rows_serial = Product::getSerialList($type_id);
	if(count($rows_serial) <= 0){
		script("尚無商品!");
	}

	$serial_id = get("serial_id", 1) != "" ? get("serial_id", 1) : "00";

	$row_hprice = Product::getMAXPrice($type_id, $serial_id);
	$row_lprice = Product::getMINPrice($type_id, $serial_id);

	$max_price = get("max_price", 1) != "" ? get("max_price", 1) : $row_hprice["height_price"];
	$min_price = get("min_price", 1) != "" ? get("min_price", 1) : $row_lprice["low_price"];
	$max_value_price = get("max_value_price", 1) != "" ? get("max_value_price", 1) : $max_price;
	$min_value_price = get("min_value_price", 1) != "" ? get("min_value_price", 1) : $min_price;
	//$order = get("order", 1) != "" ? get("order", 1) : "9";
	//$page = get("page", 1) != "" ? get("page", 1) : 1;

	$query_string = "_".$type_id."_".$serial_id."_".$max_value_price."_".$min_value_price."_".$order;
	$sql = Product::getProductList($type_id, $serial_id, $max_value_price, $min_value_price, $order);

}

getSql($sql, 15, $query_string);
$rows_product = $db -> fetch_all_array($sql);

switch($type_id){
	case 1:
		$sub_banner_pic = $web_path_subbanner."m".$rows_subbanner[2]["subbanner_pic"];
		$sub_banner_pic_link = $rows_subbanner[2]["subbanner_pic_link"];
		break;
	case 2:
		$sub_banner_pic = $web_path_subbanner."m".$rows_subbanner[3]["subbanner_pic"];
		$sub_banner_pic_link = $rows_subbanner[3]["subbanner_pic_link"];
		break;
	case 3:
		$sub_banner_pic = $web_path_subbanner."m".$rows_subbanner[4]["subbanner_pic"];
		$sub_banner_pic_link = $rows_subbanner[4]["subbanner_pic_link"];
		break;
    case 4:
        $sub_banner_pic = $web_path_subbanner."m".$rows_subbanner[6]["subbanner_pic"];
        $sub_banner_pic_link = $rows_subbanner[6]["subbanner_pic_link"];
        break;
}

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
<script src="assets/scripts/product_list.js"></script>
<script src="scripts/coder_member.js"></script>
<script src="scripts/cookie.js"></script>
<script>
var min_price = parseInt("<?php echo $min_price; ?>");
var max_price = parseInt("<?php echo $max_price; ?>");
var min_value_price = parseInt("<?php echo $min_value_price; ?>");
var max_value_price = parseInt("<?php echo $max_value_price; ?>");
var order = "<?php echo $order; ?>";
var pic_path = "<?php echo $web_path_product; ?>"+"m";
$(document).ready(function(e) {	
    select_price(max_price, min_price, max_value_price, min_value_price, "list");
	selectOrder(order);
	
});
</script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<input name="type_id" type="hidden" id="type_id" value="<?php echo $type_id; ?>">
<input name="serial_id" type="hidden" id="serial_id" value="<?php echo $serial_id; ?>">
<input name="max_value_price" type="hidden" id="max_value_price" value="<?php echo $max_value_price; ?>">
<input name="min_value_price" type="hidden" id="min_value_price" value="<?php echo $min_value_price; ?>">
<input name="order" type="hidden" id="order" value="<?php echo $order; ?>">
<input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
<input name="keyword" type="hidden" id="keyword" value="<?php echo $keyword; ?>">
<div id="subbanner">
    <div class="sub-list"> 
    <?php 
		if($sub_banner_pic_link != ""){
	?>
    	<a href="<?php echo $sub_banner_pic_link; ?>"><img src="<?php echo $sub_banner_pic; ?>" width="1280" height="300" alt="" /></a> 
    <?php
		}else{
	?>
    	<img src="<?php echo $sub_banner_pic; ?>" width="1280" height="300" alt="" />
    <?php
		}
	?>
    </div>
</div>
<div id="gps"> <a href="index.html">home</a><span> / </span><a href="wishlist.html">whishist</a></div>
<div class="product-wrap">
    <div class="prod-type">
        <?php
		if($keyword != ""){
		?>
        <h3 class="prod-title">Search List</h3>
        <ul class="type-list">
            <?php
				foreach($rows_serial as $row_serial){
			?>
            <li class="type-item"><a href="product-list.html?keyword=<?php echo $keyword; ?>&max_value_price=<?php echo $row_serial["height_price"]; ?>&min_value_price=<?php echo $row_serial["low_price"]; ?>&order=9&page=1"><?php echo $row_serial["productserial_name"]; ?> <span class="type-num">(<?php echo $row_serial["amount"]; ?>)</span></a></li>
            <?php
				}
			?>
        </ul>
        <?php	
		}else{
		?>
        <h3 class="prod-title"><?php echo $ary_product_type[$type_id]; ?></h3>
        <ul class="type-list">
            <?php
				foreach($rows_serial as $row_serial){
			?>
            <li class="type-item"><a href="product-list_<?php echo $type_id; ?>_<?php echo $row_serial["productserial_id"]; ?>_<?php echo $row_serial["height_price"]; ?>_<?php echo $row_serial["low_price"]; ?>_9_1.html"><?php echo $row_serial["productserial_name"]; ?> <span class="type-num">(<?php echo $row_serial["amount"]; ?>)</span></a></li>
            <?php
				}
			?>
        </ul>
        <?php
		}
		?>
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
            <div class="pro-item pro_list" pic1="<?php echo $web_path_product."m".$row_product["product_pic1"]; ?>" pic2="<?php echo $web_path_product."m".$row_product["product_pic2"]; ?>"> <img class="proi-img" src="<?php echo $web_path_product."m".$row_product["product_pic1"]; ?>" alt="" width="240" height="320"/>
                <p class="proi-en"><?php echo $row_product["product_name_en"]; ?></p>
                <p class="proi-zh"><?php echo $row_product["product_name_tw"]; ?></p>

                <?php 
                if($type_id == 4)
                {
                ?>
                    <p class="price"><a style="text-decoration:line-through;">TWD.<?php echo $row_product["product_special_price"]; ?></a></p>
                    <p class="price"><font color="red">TWD.<?php echo $row_product["product_sell_price"]; ?></font></p>
                <?php
                }
                else
                {
                ?>
                    <p class="price"><a>TWD.<?php echo $row_product["product_sell_price"]; ?></a></p>
                <?php
                }
                ?>
            </div>
            </a>
            <?php
				}
			?>
            <ul class="prod-page">
                <?php showPage2(); ?>
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