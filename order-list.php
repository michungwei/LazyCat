<?php
include_once("_config.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');
include_once($inc_path."_getpage.php");

$member_id = "";
if(isLogin()){
	$member_id = get("member_id", 1);
	//$member_id = $_SESSION["session_id"];
	if($member_id < 0 || $member_id == "" /*|| $member_id != $_SESSION["session_id"]*/){
		script("資料傳輸錯誤,請再試一次!");
	}
}else{
	script("請先登入會員!", "sign.html");	
}
$page = request_pag("page");

$query_str = "member_id=".$member_id."&page=".$page;

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();
$sql = "SELECT *
        FROM $table_order 
        /*LEFT JOIN $table_member ON order_member_id = member_id */
        WHERE order_member_id = $member_id
        ORDER BY order_create_time ASC";
        
getsql($sql, 15, $query_str);
$rows = $db -> fetch_all_array($sql);

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
<link href="assets/stylesheets/alertify.core.css" rel="stylesheet" />
<link href="assets/stylesheets/alertify.default.css" rel="stylesheet" />
<link href="Web_Manage/css/admin_style_gray.css" rel="stylesheet" />
<link rel="shortcut icon" href="assets/images/favicon.ico">
<script src="assets/scripts/jquery-2.1.1.min.js"></script>
<script src="scripts/bootstrap.min.js"></script>
<script src="scripts/jquery.validate.js"></script>
<script src="scripts/alertify.min.js"></script>
<script src="scripts/coder_member.js"></script>
<script src="scripts/memberdo.js"></script>
<script src="assets/scripts/demo.js"></script>
<script>
var year = <?php echo $year; ?>;
var month = <?php echo $month; ?>;
var day = <?php echo $day; ?>;

$(document).ready(function(e) {
    set_ymd_date(1911, year, month, day);
});
</script>
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div class="company-info">
<div class="accordion">
    <table width="100%" cellspacing="0" class="list-table">
        <thead>
            <tr>
                <th width="60" align="center" ></th>
                <th width="120" align="center">訂單編號</th>
                <th width="80" align="center" >付款方式</th>
                <th width="120" align="center" >付款狀態</th>
                <th width="80" align="center" >訂單狀態</th>
                <th width="100" align="center" >訂單金額</th>
                <th width="100" align="center">收件人</th>
                <th align="left"></th>
                <th width="120" align="center">訂單建立時間</th>
            </tr>
        </thead>
        <tbody id="the-list" class="list:cat">
            <?php
                foreach($rows as $row){
            ?>
            <tr>
                <th align="center"><?php echo $row["order_id"]; ?></th>
                <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["order_sno"]; ?></td>
                <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $ary_payment_type[$row["order_payment_type"]]; ?></td>
                <td align="center" style="word-wrap: break-word; word-break: break-all;">
                    <?php echo $ary_payment_state[$row["order_payment_state"]];
                        /*if($row["order_payment_state"] == 0)  
                        {*/
                    ?>
                    <a color="red" href=<?php echo "goPay.php?sno=".$row['order_sno']."&member_id=".$member_id; ?>>
                        (前往付款)
                    </a>
                    <?        
                        //}
                    ?>
                </td>
                <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $ary_order_state[$row["order_state"]]; ?></td>
                <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["order_total_price"]; ?></td>
                <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["order_recipient_name"]; ?></td>
                <td align="left"></td>
                <td align="center"><?php echo $row["order_create_time"]; ?></td>
            </tr>
            <?php
                }
                $db -> close();
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th height="30" colspan="12" align="right"  class="tfoot" scope="col"><?=showpage()?></th>
            </tr>
        </tfoot>
    </table>
</div>
</div>
<?php include_once($inc_path."page/_footer.php"); ?>
</body>
</html>