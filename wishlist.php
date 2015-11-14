<?php
include_once("_config.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path."lib/_product.php");
include_once($inc_path.'lib/_shoppingcar.php');
include_once($inc_path."lib/_banner.php");

$member_id = "";
if(isLogin()){
	$member_id = $_SESSION["session_id"];
}else{
	script("請先登入會員!", "sign.html?page=wish");	
}

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$rows = Product::getWishList($member_id);
$rows_subbanner = Banner::getSubBanner();

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
<script src="assets/scripts/demo.js"></script>
<script src="scripts/coder_member.js"></script>
<script src="scripts/cookie.js"></script>
<script src="assets/scripts/wish_list.js"></script>
<script src="scripts/shoppingcar.js"></script>
<script>

</script>
</head>

<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div id="subbanner" class="small">
    <div class="sub-list"> 
    <?php 
		if($rows_subbanner[5]["subbanner_pic_link"] != ""){
	?>
    	<a href="<?php echo $rows_subbanner[5]["subbanner_pic_link"]; ?>"><img src="<?php echo $web_path_subbanner."m".$rows_subbanner[5]["subbanner_pic"]; ?>" width="1280" height="127" alt="" /></a> 
    <?php
		}else{
	?>
    	<img src="<?php echo $web_path_subbanner."m".$rows_subbanner[5]["subbanner_pic"]; ?>" width="1280" height="127" alt="" /> 
    <?php
		}
	?>
    </div>
</div>
<div id="gps"> <a href="index.html">home</a><span> / </span><a href="wishlist.html">whishist</a> </div>
<div class="product-wrap">
    <div class="wish-list">
        <table class="wh-tb">
            <?php
			if(count($rows) > 0){
			?>
            <thead>
            <th colspan="6">WISHLIST</th>
                    </thead>
            <tr>
                <td width="30"></td>
                <td width="125"></td>
                <td width="292">商品名稱 / NAME</td>
                <td width="292">顏色 / COLOR</td>
                <td width="135">價格 / PRICE</td>
                <td width="140">庫存 / STOCK</td>
                <td></th>
            </tr>
            <?php
				foreach($rows as $row){
                    $colorAry = explode(",", $row["product_color"]);
                    $colorStr = $colorAry[$row["wish_color"]];
                    $stockAry = explode(",", $row["product_stock"]);
                    $stockNum = $stockAry[$row["wish_color"]];
			?>
            <tr class="list">
                <td><button class="btn-del wish_del" w_id="<?php echo $row["wish_id"]; ?>">×</button></td>
                <td><img src="<?php echo $web_path_product."m".$row["product_pic1"]; ?>" height="120" width="90" alt="" /></td>
                <td><a href="product_<?php echo $row["product_type_id"]; ?>_<?php echo $row["product_id"]; ?>.html"><?php echo $row["product_name_en"]; ?> <br />
                    <?php echo $row["product_name_tw"]; ?></a></td>
                <td><div id="colorSelect"><div style='background-color: <?php echo $colorStr; ?>'></div></div></td>
                <td> TWD.<?php echo $row["product_sell_price"]; ?></td>
                <td><span class="num"><?php echo ($stockNum > 0) ? $stockNum : "補貨中"; ?></span></td>
                <td>
                <?php
				if($stockNum > 0){
				?>
                <button class="btn-white-add wish_add_cart" w_color="<?php echo $row["wish_color"]; ?>" w_id="<?php echo $row["wish_id"]; ?>" pro_id="<?php echo $row["product_id"]; ?>" pro_sno="<?php echo $row["product_sno"]; ?>" name_en="<?php echo $row["product_name_en"]; ?>" name_tw="<?php echo $row["product_name_tw"]; ?>" sell_price="<?php echo $row["product_sell_price"]; ?>" pic="<?php echo $row["product_pic1"]; ?>" style="cursor: pointer;"><img src="assets/images/ui-s/bag.png" height="15" alt="" />&nbsp;&nbsp;ADD TO CAR</button>
                <?php
				}else{
				?>
                <button class="btn-white-add"><img src="assets/images/ui-s/bag.png" height="15" alt="" />&nbsp;&nbsp;補貨中</button>
                <?php
				}
				?>
                </td>
            </tr>
            <?php
				}
			}else{
				echo ("您尚未建立wishlist!");
			}
			?>
        </table>
    </div>
</div>
<?php include_once($inc_path."page/_footer.php"); ?>
<?php $db -> close(); ?>
</body>
</html>