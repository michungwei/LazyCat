<?php
$inc_path = "../inc/";
$manage_path = "";
include_once($inc_path."_config.php");

$account = post('acc', 1);
$password = md5(post('pwd', 1));

if($account != "" && $password != ""){
	$db = new Database($HS, $ID, $PW, $DB);
	$db -> connect();
	$sql = "SELECT * 
			FROM $table_admin 
			WHERE admin_account = '$account' AND admin_password = '$password'";
	$row = $db -> query_first($sql);
	if($row){
		$_SESSION["madmin"] = $row["admin_account"];
		$_SESSION["userid"] = $row["admin_id"];
		$_SESSION["mauth"] = $row["admin_level"];
		redirect("index.php");
	}else{
		script("登入失敗,帳號或密碼不正確!");
	}
	$db -> close();
}else{
	script("帳號或密碼不能為空!");
} 
/*END PHP*/
