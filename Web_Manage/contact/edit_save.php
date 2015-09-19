<?php
include_once("_config.php");
include_once($inc_path."_imgupload.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$id = post("cid");

$data["contact_reply"] = post("is_reply", 1);
$data["contact_update_time"] = request_cd();


$db -> query_update($table_contact, $data, "$id_column = $id");
$db -> close();

script("修改完成!", "edit.php?id=".$id);

/*End PHP*/



