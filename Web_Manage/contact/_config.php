<?php
$inc_path = "../../inc/";
$manage_path = "../";
include_once('../_config.php');

$id_column = "contact_id";
$ind_column = "contact_ind";


$page = request_pag("page");
$is_reply = get("is_reply", 1);
$keyword = get("keyword", 1);
$type = get("type", 1);
$query_str = "type=".$type."&keyword=".$keyword."&is_reply=".$is_reply."&page=".$page;
$mtitle = "<a href='index.php?".$query_str."'> 聯絡我管理 </a>";

/*End PHP*/