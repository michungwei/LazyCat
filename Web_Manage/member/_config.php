<?php
$inc_path = "../../inc/";
$manage_path = "../";
include_once('../_config.php');

$id_column = "member_id";
$ind_column = "member_ind";

$type = get("type", 1);
$keyword = get("keyword", 1);
//$is_show=get("isshow",1);
//$status=get("status",1);
$page = request_pag("page");
$query_str = "type=".$type."&keyword=".$keyword."&page=".$page;
$mtitle = "<a href='index.php?".$query_str."'>會員管理</a>";

/*End PHP*/