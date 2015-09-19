<?php
include_once('_config.php');
include_once($inc_path."_func_smtp.php");

$sResult = 0;
$msg = "系統發生錯誤!請聯絡系統管理員";

$email = post("email", 1);
$content = post("content", 1);

$sResult=isNull($email, "email", 1, 30);
if ($sResult){
	
	$db = new Database($HS, $ID, $PW, $DB);
	$db -> connect();	

		$data["contact_email"] = $email;
		$data["contact_message"] = $content;
		$data["contact_create_time"] = request_cd();
		$data["contact_update_time"] = request_cd();
		$data["contact_ind"] = getMaxInd_web($table_contact, 'contact_ind', "");
		
		$id = $db -> query_insert($table_contact, $data);
		if($id>0){
			$fr_em = $email;
			$fr_na = "網站客戶";
			$to_em = $sys_email;
			$to_na = $sys_name;
			$subject = "聯絡我訊息";
			$body = $content;
			
			sendMail($fr_em, $fr_na, $to_em, $to_na, $subject, $body);
			
			$sResult = 1;
			$msg = "您的訊息已送出,客服人員會盡快處理!謝謝!";
		}else{
			$sResult = 0;
			$msg = "資料送出失敗,請再試一次或聯絡系統管理員!";
		}
	
	$db->close();
	
}else{
	$msg = $str_message;
}
$re["sResult"] = $sResult==1 ? true : false;
$re["msg"] = $msg;
echo json_encode($re);
?>