<?php
include_once("_config.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$pro_id = post("pro_id", 1 );
$member_id = post("member_id", 1 );
$pro_color = post("pro_color", 1 );

$sResult = false;
$sMsg = "";


if($pro_id != "" && $member_id != ""){
	$sql = "SELECT * FROM $table_wish WHERE wish_member_id = '$member_id' AND wish_product_id = '$pro_id' AND wish_color = '$pro_color'";
	$row = $db -> query_first($sql);
	
	if(!$row){
		$data["wish_member_id"] = $member_id;
		$data["wish_product_id"] = $pro_id;
		$data["wish_create_time"] = request_cd();
		$data["wish_color"] = $pro_color;
		
		$db -> query_insert($table_wish, $data);
		$db -> close();
		
		
		$sMsg = "此商品已加入您的WISH LIST中!";
	}else{
		$sMsg = "此商品已在您的WISH LIST中!";
	}
	$sResult = true;

}else{
	$sMsg = "資料傳輸錯誤!請再試一次!";
}

$re["result"] = $sResult;
$re["msg"] = $sMsg;

echo json_encode($re);


/***END PHP***/