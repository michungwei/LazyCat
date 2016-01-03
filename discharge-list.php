<?php
include_once("_config.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path.'lib/_shoppingcar.php');
include_once($inc_path."_getpage.php");

$member_id = "";
if(isLogin()){
	//$member_id = get("member_id", 1);
	$member_id = $_SESSION["session_id"];
	if($member_id < 0 || $member_id == "" || $member_id != $_SESSION["session_id"]){
		script("資料傳輸錯誤,請再試一次!");
	}
}else{
	script("請先登入會員!", "sign.html");	
}
$page = request_pag("page");

$query_str = "member_id=".$member_id."&page=".$page;

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$row_member = CoderMember::getList($member_id);
$disIdAry = array();
$disAmtAry = array();
$disRow = array();
$rows = array();

$disIdAry = explode(',', $row_member['member_discharge_id']);
$disAmtAry = explode(',', $row_member['member_discharge_amount']);

$cnt = 0;
for($i = 0; $i < sizeof($disIdAry) - 1; $i ++)
{
    if(CoderMember::getDischargeList($disIdAry[$i]))
    {
        $rows[$cnt] = CoderMember::getDischargeList($disIdAry[$i]);
        $rows[$cnt]['discharge_amount'] = $disAmtAry[$i];
        $cnt ++;
    }
}
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
</head>
<body>
<?php include_once($inc_path."page/_menu.php"); ?>
<div class="company-info">
<div class="accordion">
    <table width="100%" cellspacing="0" class="list-table">
        <thead>
            <tr>
                <th width="60" align="center" ></th>
                <th width="120" align="center">抵用金編號</th>
                <th width="250" align="center">名稱</th>
                <th width="80" align="center" >餘額</th>
                <th width="120" align="center" >開始時間</th>
                <th width="120" align="center" >結束時間</th>
            </tr>
        </thead>
        <tbody id="the-list" class="list:cat">
            <?php
                for($i = 0; $i < sizeof($rows); $i ++){
            ?>
            <tr>
                <th align="center"></th>
                <th align="right"><?php echo $rows[$i]["discharge_id"]; ?></th>
                <th align="center"><?php echo $rows[$i]["discharge_name"]; ?></th>
                <th align="center"><?php echo $rows[$i]["discharge_amount"]; ?></th>
                <th align="center"><?php echo $rows[$i]["discharge_start_time"]; ?></th>
                <th align="center"><?php echo ($rows[$i]["discharge_forever"])? "無期限":$rows[$i]["discharge_end_time"]; ?></th>
            </tr>
            <?php
                }
                $db -> close();
            ?>
        </tbody>
    </table>
</div>
</div>
<?php include_once($inc_path."page/_footer.php"); ?>
</body>
</html>