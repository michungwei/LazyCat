<?php
include_once("_config.php");
include_once($inc_path."_imgupload.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$data["online_type_id"] = post("type");
$data["online_title"] = post("title", 1);
$data["online_price"] = post("price", 1);
$data["online_link"] = post("link", 1);
$data["online_content"] = request_str("content");
$data["online_ind"] = getMaxInd($table_online, $ind_column, "");
$data["online_is_show"] = post("is_show");
$data["online_create_time"] = request_cd();


$db -> query_insert($table_online, $data);
$db -> close();

script("新增成功!", "index.php");

/*End PHP*/



