<?php
include_once("_config.php");
include_once($inc_path."_imgupload.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$id = post("cid");
$data["promo_name"] = post("name",1);
$data["promo_code"] = post("code",1);
$data["promo_enable"] = post("enable");
$data["promo_money"] = post("money");
$data["promo_discount"] = post("discount");
$data["promo_start_time"] = UIdate_change(post("start_time",1));
$data["promo_end_time"] = UIdate_change(post("end_time",1));

$data["promo_create_time"] = request_cd();
$data["promo_update_time"] = request_cd();


$db -> query_update($table_promo, $data, "$id_column = $id");

$db -> close();

script("修改完成!", "edit.php?id=".$id);

/*End PHP*/



