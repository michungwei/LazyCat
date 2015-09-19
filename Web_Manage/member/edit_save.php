<?php
include_once("_config.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$id = post("cid");

//$data["isshow"] = post("isshow");
//$data["member_account"] = post("acc", 1);

$new_pwd = post("pwd", 1);
if($new_pwd != ""){
	$data["member_password"] = md5($new_pwd) ;
}

$data["member_name"] = post("name", 1);
$data["member_email"] = post("email", 1);
$data["member_mobile_nation"] = post("mobile_national_number", 1);
$data["member_mobile"] = post("mobile", 1);
$year = post("ymd_year");
$month = sprintf("%02d", post("ymd_month"));
$day = sprintf("%02d", post("ymd_day"));
$data["member_birthday"] = $year.$month.$day;
$data["member_address"] = post("address", 1);
$data["member_update_time"] = request_cd();

$db -> query_update($table_member, $data, "$id_column = $id");
$db -> close();

script("修改完成!", "edit.php?id=".$id);

/*PHP END*/
