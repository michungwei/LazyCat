<?php
$inc_path = "../../inc/";
$manage_path = "../";
include_once('../_config.php');

$id_column = "wish_id";
//$ind_column = "product_ind";

$file_path = $admin_path_product;

$page = request_pag("page");

$keyword = get("keyword", 1);
$type = get("type", 1);
$year = get("year", 1);
$month = get("month", 1);

$query_str = "type=".$type."&keyword=".$keyword."&year=".$year."&month=".$month."&page=".$page;

$mtitle = "<a href='index.php?".$query_str."'> WISHLIST管理 </a>";

/*****End PHP*****/