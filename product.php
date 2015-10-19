<?php
include_once("_config.php");
include_once($inc_path."lib/_product.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

$car = new shoppingCar();
$carItem = $car-> getCar();
$u = count($carItem);

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$type_id = get("type_id", 1);
$pro_id = get("pro_id", 1);
if($pro_id != ""){
	$row = Product::getProduct($pro_id);
	$stock = $row["product_stock"];
	if(!$row){
		script("資料傳送錯誤!");
	}
}else{
	script("資料傳送錯誤!");
}

$member_id = isset($_SESSION["session_id"]) ? $_SESSION["session_id"] : "";
$rows_hot = Product::getHotPro();

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
<link rel="shortcut icon" href="assets/images/favicon.ico">
<script src="assets/scripts/jquery-2.1.1.min.js"></script>
<script src="assets/scripts/demo.js"></script>
<script src="scripts/coder_member.js"></script>
<script src="scripts/cookie.js"></script>
<script src="assets/scripts/product.js"></script>
<script src="scripts/shoppingcar.js"></script>
<script>
var pic_path = "<?php echo $web_path_product; ?>"+"m";
$(document).ready(function(){ 

    //initial color select
    var OriColor = <?php echo "\"".$row["product_color"]."\""; ?>;
    var OriColorStrAry = OriColor.split(",");
    console.log(OriColorStrAry);
    for(index in OriColorStrAry)
    {
        if(OriColorStrAry[index] != "")
        {
            if(index == 0)
                $('#colorSelect').append("<div class='select' style='background-color: "+OriColorStrAry[index]+"'></div>");
            else
                $('#colorSelect').append("<div style='background-color: "+OriColorStrAry[index]+"'></div>");
            OriColorStrAry[index] += ",";
        }
    }
    console.log(OriColorStrAry);

    $('#colorSelect div').click(function()
    {
        $('#colorSelect div').removeClass("select");
        $(this).addClass("select");
    });
});
</script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div id="gps"> <a href="index.html">home</a><span> / </span><a href="product-list.html">shop</a><span> / </span><a href="product-list_<?php echo $type_id; ?>.html"><?php echo $ary_product_type[$type_id]; ?></a> </div>
<div class="orderContent">
    <div class="productImg">
        <div class="arrow-left"></div>
        <div class="arrow-right"></div>
        <div class="proImg"><img src="<?php echo $web_path_product."m".$row["product_pic1"]; ?>" width="450" height="600" alt="" /></div>
        <ul class="proImg-ist">
            <li class="prol-item active"><img src="<?php echo $web_path_product."m".$row["product_pic1"]; ?>" alt="" width="90" height="120" /></li>
            <?php
				if($row["product_pic2"] != ""){
			?>
            <li class="prol-item"><img src="<?php echo $web_path_product."m".$row["product_pic2"]; ?>" width="90" height="120" alt="" /></li>
            <?php
				}
				if($row["product_pic3"] != ""){
			?>
            <li class="prol-item"><img src="<?php echo $web_path_product."m".$row["product_pic3"]; ?>" alt="" width="90" height="120"/></li>
            <?php
				}
				if($row["product_pic4"] != ""){
			?>
            <li class="prol-item"><img src="<?php echo $web_path_product."m".$row["product_pic4"]; ?>" alt="" width="90" height="120"/></li>
            <?php
				}
			?>
        </ul>
    </div>
    <div class="productInfo">
        <h2><?php echo $row["product_name_en"]; ?></h2>
        <h3><?php echo $row["product_name_tw"]; ?></h3>
        <h1 class="price">TWD.<?php echo $row["product_sell_price"]; ?></h1>
        <div id="colorSelect"><a>顏色：</a></div>
        <?php
		if($stock > 0){
		?>
        <div class="numSelect"> <span class="beSelect">1</span>
            <ul class="num">
            	<?php
					$j = $stock<10 ? $stock : 10;
					for($i = 1; $i <= $j; $i ++){
				?>
                <li isSel="<?php echo $i; ?>"><?php echo $i; ?></li>
                <?php
					}
				?>
            </ul>
        </div>
        <button class="btn-white-add" id="buy_btn" pro_id="<?php echo $pro_id; ?>" pro_sno="<?php echo $row["product_sno"]; ?>" member_id="<?php echo $member_id; ?>" page="detail" type_id="<?php echo $type_id; ?>" name_en="<?php echo $row["product_name_en"]; ?>" name_tw="<?php echo $row["product_name_tw"]; ?>" sell_price="<?php echo $row["product_sell_price"]; ?>" pic="<?php echo $row["product_pic1"]; ?>"><img src="assets/images/ui-s/bag.png" height="15" alt="" />&nbsp;&nbsp;ADD TO CAR</button>
        <?php
		}else{
		?>
        <button class="btn-white-add"><img src="assets/images/ui-s/bag.png" height="15" alt="" />&nbsp;&nbsp;補貨中</button>
        <?php
		}
		?>
        <button id="wish_btn" class="btn-white" pro_id="<?php echo $pro_id; ?>" member_id="<?php echo $member_id; ?>" page="detail" type_id="<?php echo $type_id; ?>" style="cursor: pointer;">WISH LIST</button>
        <p class="proC-en"><?php echo $row["product_comment"]; ?></p>
        <ul class="social">
            <li class="soc-item"><a class="ui-s-ig" href="###"></a></li>
            <li class="soc-item "><a class="ui-s-fb" href="###"></a></li>
        </ul>
    </div>
</div>
<div class="hot">
    <!--<div class="recently-title"></div>-->
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