<?php
include_once("_config.php");
include_once($inc_path."_imgupload.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$id = post("cid");

$data["admin_account"] = post("account", 1);
$new_pwd = post("new_password", 1);
if($new_pwd != ""){
	$data["admin_password"] = md5($new_pwd) ;
}

$data["admin_update_time"] = request_cd();

$db -> query_update($table_admin, $data, "$id_column = $id");
$db -> close();

script("修改完成!", "edit.php?id=".$id."&".$query_str);
/*End PHP*/

