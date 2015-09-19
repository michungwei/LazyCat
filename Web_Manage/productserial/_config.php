<?php
$inc_path="../../inc/";
$manage_path = "../";
include_once("../_config.php");

$id_column = "productserial_id";
$ind_column = "productserial_ind";
$file_path = $admin_path_productserial;

$page = request_pag("page");
$type = get("type", 1);
$keyword = get("keyword", 1);
$is_show = get("is_show", 1);

$query_str = "is_show=".$is_show."&keyword=".$keyword."&type=".$type."&page=".$page;
$mtitle = "<a href='index.php?".$query_str."'> 商品系列管理 </a>";

/*End PHP*/