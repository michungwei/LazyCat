<?php
$inc_path = "../../inc/";
$manage_path = "../";
include_once('../_config.php');

$id_column = "order_id";
$ind_column = "order_ind";

$page = request_pag("page");

$is_pay = get("is_pay", 1);
$keyword = get("keyword", 1);
$type = get("type", 1);
$order_state = get("order_state", 1);
$s_price = get("s_price", 1);
$e_price = get("e_price", 1);
$s_date = get("s_date", 1);
$e_date = get("e_date", 1);

$query_str = "type=".$type."&keyword=".$keyword."&is_pay=".$is_pay."&order_state=".$order_state."&s_price=".$s_price."&e_price=".$e_price."&s_date=".$s_date."&e_date=".$e_date."&page=".$page;
$mtitle = "<a href='index.php?".$query_str."'> 訂單管理 </a>";

/*END PHP*/