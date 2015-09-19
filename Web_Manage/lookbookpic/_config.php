<?php
$inc_path = "../../inc/";
$manage_path = "../";
include_once('../_config.php');

$id_column = "lookbookpic_id";

$file_path = $admin_path_lookbook;

$page = request_pag("page");
$is_show = get("is_show", 1);
$keyword = get("keyword", 1);
$type = get("type", 1);
$serial = get("serial", 1);

$query_str = "type=".$type."&serial=".$serial."&keyword=".$keyword."&is_show=".$is_show."&page=".$page;
$mtitle = "<a href='index.php?".$query_str."'> 宣傳冊圖片管理 </a>";

/*End PHP*/