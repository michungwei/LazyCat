<?php
$inc_path="../../inc/";
$manage_path = "../";
include_once("../_config.php");

$id_column = "lookbook_id";
$ind_column = "lookbook_ind";
$file_path = $admin_path_lookbook;

$page = request_pag("page");

$keyword = get("keyword", 1);
$is_show = get("is_show", 1);

$query_str = "is_show=".$is_show."&keyword=".$keyword."&page=".$page;
$mtitle = "<a href='index.php?".$query_str."'> 宣傳冊管理 </a>";

/*End PHP*/