<?php
include_once("_config.php");
include_once($inc_path."lib/_product.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

$car = new shoppingCar();
$carItem = $car-> getCar();
$u = count($carItem);

if(!isLogin()){
	script("請先登入會員!", "sign.php");
}else{
	if($u == 0){
		script("您目前未購買任何商品,請繼續購物!!");
	}
}

$car -> calculate();
$total = $car -> total;

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

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
<script src="scripts/jquery.validate.js"></script>
<script src="assets/scripts/cart_list.js"></script>
<script src="scripts/coder_member.js"></script>
<script src="scripts/shoppingcar.js"></script>
<script src="scripts/cookie.js"></script>
<script src="assets/scripts/order.js"></script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div id="gps"> <a href="index.html">home</a><span> / </span><a href="wishlist.html">wishlist</a> </div>
<div class="orderStep">
    <div class="step1 active"></div>
    <div class="step2"></div>
    <div class="step3"></div>
</div>
<div class="orderContent">
    <div class="wish-list ord">
        <table class="wh-tb">
            <tr>
                <td width="30"></td>
                <td width="125"></td>
                <td width="200">商品名稱 / NAME</td>
                <td width="120">顏色 / COLOR</td>
                <td width="120">價格 / PRICE</td>
                <td width="100">數量 / QUANTITY</td>
                <td>TOTAL</td>
            </tr>
            <?php
				for($i = 0; $i < count($carItem); $i++){
					$product_id = $carItem[$i] -> product_id;
					$product_sno = $carItem[$i] -> product_sno;
					$product_name_en = $carItem[$i] -> product_name_en;
					$product_name_tw = $carItem[$i] -> product_name_tw;
					$sell_price = $carItem[$i] -> sell_price;
					$amount = $carItem[$i] -> amount;
					$subtotal = $carItem[$i] -> subtotal;
					$pic = $carItem[$i] -> pic;
                    $product_color = $carItem[$i] -> product_color;
                    $cart_id = $product_id.$product_sno.$product_color;
                    $row_product = Product::getProduct($product_id);
                    if($row_product["product_color"] != NULL)
                    {
                        $colorAry = explode(",", $row_product["product_color"]);
                        $colorStr = $colorAry[$product_color];
                    }
                    else
                    {
                        $colorStr = "無";
                    }
                    $stockAry = explode(",", $row_product["product_stock"]);
                    $stock = $stockAry[$product_color];
			?>
            <tr>
                <td><button class="btn-del cart_del_btn" cart_id="<?php echo $cart_id; ?>">×</button></td>
                <td><img src="<?php echo $web_path_product."m".$pic; ?>" width="90" height="120" alt="" /></td>
                <td><?php echo $product_name_en; ?> <br />
                    <?php echo $product_name_tw; ?><br /></td>
                <td class="color"><div id="colorSelect"><div style='background-color: <?php echo $colorStr; ?>'></div></div></td>
                <td class="sell_price_td"> TWD.<span class="sell_price"><?php echo $sell_price; ?></span></td>
                <!--<td class="cart_list_td" color="<?php echo $product_color; ?>"><span class="cost" style="cursor: pointer;" cart_id="<?php echo $cart_id; ?>" product_id="<?php echo $product_id; ?>">&#8211;</span>&nbsp;&nbsp;&nbsp;&nbsp; <span class="num"><?php echo $amount; ?></span> &nbsp;&nbsp;&nbsp;&nbsp;<span class="plus" style="cursor: pointer;" cart_id="<?php echo $cart_id; ?>">&plus;</span></td>-->
                <td class="cart_list_td" color="<?php echo $product_color; ?>">
                    <select style="width: 50px;margin-right:50px" class="numSelect" id="numSelect" cart_id="<?php echo $cart_id; ?>" product_id="<?php echo $product_id; ?>">
                        <?php
                        for($j = 1; $j <= $stock; $j++)
                        {
                        ?>
                            <option value="<?php echo $j; ?>" <?php echo ($j == $amount)? "selected" : ""; ?>><?php echo $j;?></option>
                        <?php
                        } 
                        ?>
                    </select>
                </td>
                <td class="subtotal_price_td">TWD.<span class="subtotal_price"><?php echo $subtotal; ?></span></td>
            </tr>
            <?php
				}
			?>
        </table>
    </div>
    <div class="subtotal-wrap">
        <div class="subt">
            <div class="ui-s-subtotal"></div>
            <div class="ord-price">TWD.<span class="total_price"><?php echo $total; ?></span></div>
        </div>
        <!--<button class="btn-white" id="update_cart_btn" style="cursor: pointer;">UPDATE CAR</button>-->
        <button class="btn-white" id="cheakout_btn" style="cursor: pointer;"> 結帳 </button>
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