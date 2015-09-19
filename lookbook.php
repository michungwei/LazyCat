<?php
include_once("_config.php");
include_once($inc_path."lib/_lookbook.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$rows = LookBook::getLookBook();

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
<script src="scripts/lazyload.js"></script>
<script>
$(document).ready(function(e) {
	demo();
    $("img.lazy").lazyload({
		effect : "fadeIn"
		//threshold : 200
	});
	
});

</script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div class="product-wrap">
    <h3 class="lookbook-title">LOOKBOOK FASHION</h3>
    <?php
	foreach($rows as $row){
	?>
    <div class="lookBookW">
        <h4 class="bookName">LOOK BOOK <br />
            - <?php echo $row["lookbook_title"]; ?> -</h4>
        <img class="lazy" data-original="<?php echo $web_path_lookbook."m".$row["lookbook_titlepic"]; ?>" width="100%"  alt="" /> <a href="lookbook-detail_<?php echo $row["lookbook_id"]; ?>.html" class="btn-block">shop this look</a> </div>
	<?php
		}
	?>
</div>
<?php include_once($inc_path."page/_footer.php"); ?>
<?php $db -> close(); ?>
</body>
</html>