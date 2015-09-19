<?php
include_once("_config.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$data["admin_account"] = post("account", 1);
$data["admin_password"] = md5(post("password", 1));
$data["admin_create_time"] = request_cd();
$data["admin_update_time"] = request_cd();
$data["admin_ind"] = getMaxInd($table_admin, $ind_column, "");

$db -> query_insert($table_admin, $data);
$db -> close();

script("新增成功!", "index.php");

/*END PHP*/
