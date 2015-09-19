<?php
$inc_path="../../inc/";
$manage_path = "../";
include_once("../_config.php");

$id_column = "subbanner_id";

$file_path = $admin_path_subbanner;

$page = request_pag("page");

$query_str = "page=".$page;
$mtitle = "<a href='index.php?".$query_str."'> 內頁BANNER管理 </a>";

/*End PHP*/