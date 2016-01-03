<?php
$inc_path = "../../inc/";
$manage_path = "../";
include_once('../_config.php');

$id_column = "promo_id";
$ind_column = "promo_ind";

$page = request_pag("page");

$keyword = get("keyword", 1);
$s_price = get("s_price", 1);
$e_price = get("e_price", 1);
$s_date = get("s_date", 1);
$e_date = get("e_date", 1);

$query_str = "&keyword=".$keyword."&s_price=".$s_price."&e_price=".$e_price."&s_date=".$s_date."&e_date=".$e_date."&page=".$page;
$mtitle = "<a href='index.php?".$query_str."'> 折扣碼 </a>";

/*END PHP*/