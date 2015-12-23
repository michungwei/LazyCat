<?php
include_once("_config.php");
include_once($inc_path."_imgupload.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$id = post("cid");
$data["discharge_name"] = post("name",1);
$data["discharge_enable"] = post("enable");
$data["discharge_forever"] = post("forever");
$data["discharge_start_time"] = UIdate_change(post("start_time",1));
$data["discharge_end_time"] = UIdate_change(post("end_time",1));

$data["discharge_create_time"] = request_cd();
$data["discharge_update_time"] = request_cd();


$db -> query_update($table_discharge, $data, "$id_column = $id");

$db -> close();

script("修改完成!", "edit.php?id=".$id);

/*End PHP*/



