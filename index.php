<?php
include_once("_config.php");
include_once($inc_path."lib/_banner.php");
include_once($inc_path."lib/_product.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$rows_banner = Banner::getBanner();
$banner_pic = "";
$banner_item = "";
foreach($rows_banner as $row_banner){
	if($row_banner['banner_pic_link'] != ""){
		$banner_pic .= '<a href="'.$row_banner['banner_pic_link'].'"><img src='.$web_path_banner.'m'.$row_banner['banner_pic'].' alt="" /></a>';
	}else{
		$banner_pic .= '<img src='.$web_path_banner.'m'.$row_banner['banner_pic'].' alt="" />';
	}
	$banner_item .= '<li class="bang-item"></li>'; 
}

$rows_pic = Banner::getPic();
$rows_new = Product::getNewPro();
$rows_hot = Product::getHotPro();
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
<link href="assets/stylesheets/screen.css" media="screen, projection" rel="stylesheet">
<link rel="shortcut icon" href="<?php echo $favicon; ?>">
<script src="assets/scripts/jquery-2.1.1.min.js"></script>
<script src="assets/scripts/jquery.timers-1.1.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="assets/scripts/demo.js"></script>
<script src="assets/scripts/index.js"></script>
<script src="scripts/coder_member.js"></script>
<script src="scripts/cookie.js"></script>
<script>
var pic_path = "<?php echo $web_path_product; ?>"+"m";
</script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div id="banner">
    <div class="btn-left"></div>
    <div class="btn-right"></div>
    <div class="banner-list"> <?php echo $banner_pic; ?></div>
    <ul class="banner-page">
        <?php echo $banner_item; ?>
    </ul>
</div>
<div class="discover">
    <div class="title">
        <h2>DISCOVER</h2>
        <p></p>
    </div>
    <div class="dis-List">
        <?php
			foreach($rows_pic as $row_pic){
		?>
        <a href="<?php echo $row_pic["pic_link"]; ?>">
        <div class="dis-item">
            <div class="disi-img"><img src="<?php echo $web_path_pic."m".$row_pic["pic_pic"]; ?>" height="261" width="301" alt="" /></div>
            <div class="disi-name">
                <h3 class="disi-h3"><?php echo $row_pic["pic_name_en"]; ?></h3>
                <p class="disi-p"><?php echo $row_pic["pic_name_tw"]; ?></p>
            </div>
        </div>
        </a>
        <?php
			}
		?>
    </div>
</div>
<div class="new">
    <div class="new-title"></div>
    <div class="new-wrap new_box">
        <div class="arrow-left new_left"></div>
        <div class="arrow-right new_right"></div>
        <div class="new-outer new_list">
            <ul class="new-list">
                <?php
					foreach($rows_new as $row_new){
				?>
                <a href="product_<?php echo $row_new["product_type_id"]; ?>_<?php echo $row_new["product_id"]; ?>.html">
                <li class="pro-item pro_new" pic1="<?php echo $web_path_product."m".$row_new["product_pic1"]; ?>" pic2="<?php echo $web_path_product."m".$row_new["product_pic2"]; ?>"> <img class="proi-img" src="<?php echo $web_path_product."m".$row_new["product_pic1"]; ?>" height="320" width="240" alt="" />
                    <p class="proi-en"><?php echo $row_new["product_name_en"]; ?></p>
                    <p class="proi-zh"><?php echo $row_new["product_name_tw"]; ?></p>
                    <p class="price">TWD.<?php echo $row_new["product_sell_price"]; ?></p>
                </li>
                </a>
                <?php
					}
				?>
            </ul>
        </div>
    </div>
</div>
<div id="subbanner">
    <div class="sub-list"> 
    <?php 
		if($rows_subbanner[0]["subbanner_pic_link"] != ""){
	?>
    	<a href="<?php echo $rows_subbanner[0]["subbanner_pic_link"]; ?>"><img src="<?php echo $web_path_subbanner."m".$rows_subbanner[0]["subbanner_pic"]; ?>" width="1280" height="300" alt="" /></a> 
    <?php
		}else{
	?>
    	<img src="<?php echo $web_path_subbanner."m".$rows_subbanner[0]["subbanner_pic"]; ?>" width="1280" height="300" alt="" />
    <?php
		}
	?>
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
                <li class="pro-item pro_hot" pic1="<?php echo $web_path_product."m".$row_hot["product_pic1"]; ?>" pic2="<?php echo $web_path_product."m".$row_hot["product_pic2"]; ?>"> <img class="proi-img" src="<?php echo $web_path_product."m".$row_hot["product_pic1"]; ?>" height="320" width="240" alt="" />
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