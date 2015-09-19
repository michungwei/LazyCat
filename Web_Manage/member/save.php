<?php
include_once("_config.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$data["member_account"] = post("acc", 1);
$data["member_password"] = md5(post("pwd", 1));
$data["member_name"] = post("name", 1);
$data["member_email"] = post("email", 1);
$data["member_mobile_nation"] = post("mobile_national_number", 1);
$data["member_mobile"] = post("mobile", 1);
$year = post("ymd_year");
$month = sprintf("%02d", post("ymd_month"));
$day = sprintf("%02d", post("ymd_day"));
$data["member_birthday"] = $year.$month.$day;
$data["member_address"] = post("address", 1);
$data["member_create_time"] = request_cd();
$data["member_update_time"] = request_cd();
$data["member_ind"] = getMaxInd($table_member, $ind_column, "");

$id = $db -> query_insert($table_member, $data);
$db -> close();

script("新增成功!","index.php");

/*PHP END*/
