<?php
$inc_path="../../inc/";
$manage_path = "../";
include_once("../_config.php");

$id_column = "admin_id";
$ind_column = "admin_ind";
$check_field = "admin_account";

$keyword = get("keyword", 1);
$page = request_pag("page");
$query_str = "keyword=".$keyword."&page=".$page;
$mtitle = "<a href='index.php?".$query_str."'> 管理者管理 </a>";

$aryAdminAuth = array(0 => "一般", 1 =>"最高");
/*END PHP*/