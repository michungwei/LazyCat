<?php
include_once("_config.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$w_id = post("w_id", 1 );

$sResult = false;
$sMsg = "";


if($w_id != ""){
	$sql = "SELECT * FROM $table_wish WHERE wish_id = '$w_id'";
	$row = $db -> query_first($sql);
	
	if($row){
		$db -> query("DELETE FROM $table_wish WHERE wish_id = '$w_id'");
		$db -> close();
		
		$sMsg = "此商品已移出您的WISH LIST中!";
	}else{
		$sMsg = "查無此商品!請與客服人員聯絡!";
	}
	$sResult = true;

}else{
	$sMsg = "資料傳輸錯誤!請再試一次!";
}

$re["result"] = $sResult;
$re["msg"] = $sMsg;

echo json_encode($re);


/***END PHP***/