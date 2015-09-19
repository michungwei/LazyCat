<?php
include_once("_config.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$m_token = post("m_token", 1 );
$m_id = post("m_id", 1 );

$sResult = false;
$sMsg = "";


if($m_token != "" && $m_id != ""){
	$sql = "SELECT * FROM $table_member WHERE member_id = '$m_id'";
	$row = $db -> query_first($sql);
	
	if($row){
		$data["member_token"] = $m_token;
		
		$db -> query_update($table_member, $data, "member_id='$m_id'");
		$db -> close();
		
		$sResult = true;
		$sMsg = "cookie已建立!";
	}else{
		$sMsg = "查無此帳號,請確認帳號輸入正確,或與客服人員聯絡!";
	}

}else{
	$sMsg = "資料傳輸錯誤!請再試一次!";
}

$re["result"] = $sResult;
$re["msg"] = $sMsg;

echo json_encode($re);


/***END PHP***/