<?php
include_once("_config.php");
include_once($inc_path."lib/_lookbook.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');
include_once($inc_path."lib/_product.php");

$lookbook_id = get("lookbook_id", 1);
if($lookbook_id < 0){
	script("資料傳輸錯誤!");
}

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$rows = LookBook::getLookBookPic($lookbook_id);

$rows_hot = Product::getHotPro();

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
<script src="scripts/coder_member.js"></script>
<script src="scripts/cookie.js"></script>
<script>
$(document).ready(function(e) {
	demo();
	IMG_scroll('.hot_box','.hot_left','.hot_right','.hot_list',3,false);
    $("div#footer").addClass("pt10");
});
</script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div class="product-wrap">
    <h3 class="lookbook-title">LOOKBOOK FASHION</h3>
    <?php
	if(count($rows) > 0){
	?>
    <h4 class="bookName">LOOK BOOK <br />
        - <?php echo $rows[0]["lookbook_title"]; ?> -</h4>
    <div class="lookBookW detail">
    	<?php
			foreach($rows as $row){
		?> 
    	<img src="<?php echo $web_path_lookbook."m".$row["lookbookpic_pic"]; ?>" width="100%" alt="" />
        <?php
			}
		?> 
	</div>
    <?php
	}
	?>
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