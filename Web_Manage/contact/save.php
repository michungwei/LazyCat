<?php
include_once("_config.php");
include_once($inc_path."_imgupload.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();


$data["contact_email"] = post("email", 1);
$data["contact_message"] = request_str("message");
$data["contact_ind"] = getMaxInd($table_contact, $ind_column, "");
$data["contact_create_time"] = request_cd();
$data["contact_update_time"] = request_cd();


$db -> query_insert($table_contact, $data);
$db -> close();

script("新增成功!", "index.php");

/*End PHP*/



