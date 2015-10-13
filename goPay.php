<?php
include_once("_config.php");

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
$sno = get("sno", 1);

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();
$sql = "SELECT *
        FROM $table_order 
        /*LEFT JOIN $table_member ON order_member_id = member_id */
        WHERE order_member_id = $member_id AND order_sno = $sno
        ORDER BY order_create_time ASC";
$row = $db -> query_first($sql);

echo "order_sno = ".$row["order_sno"];
echo "order_pay = ".$row["order_total_price"];

?>