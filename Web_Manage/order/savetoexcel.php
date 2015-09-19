<?php
include_once("_config.php");
ob_start(); 

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$sql_str = "";

if($type != "" && $keyword != ""){
	switch($type){
		case 1 :
			$sql_str .= " AND order_sno LIKE '%$keyword%'";
			break;
		case 2 :
			$sql_str .= " AND member_name LIKE '%$keyword%'";
			break;
		case 3 :
			$sql_str .= " AND order_recipient_name LIKE '%$keyword%'";
			break;
		default :
			break;	
	}
	
}
if($is_pay != ""){
	$sql_str .= " AND order_payment_state = '$is_pay'";
}
if($order_state != ""){
	$sql_str .= " AND order_state = '$order_state'";
}
if($s_date!=""){
	$sqlstr.=" AND DATE_FORMAT(order_create_time, '%Y-%m-%d') >= '$s_date'";
}
if($e_date!=""){
	$sqlstr.=" AND DATE_FORMAT(order_create_time, '%Y-%m-%d') <= '$e_date'";
}
if($s_price != ""){
	  $sqlstr.=" AND order_total_price >= '$s_price'";
}
if($e_price != ""){
  $sqlstr.=" AND order_total_price <= '$e_price'";
}	

$sql = "SELECT order_id, order_sno, order_member_id, order_payment_type, order_total_price, order_payment_state, order_state, order_recipient_name, order_create_time, order_update_time, member_id, member_name
		FROM $table_order 
		LEFT JOIN $table_member ON order_member_id = member_id 
		WHERE 1 $sql_str
		ORDER BY $ind_column DESC";
		
$rows = $db -> fetch_all_array($sql);

$db->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>excel</title>
</head>
<body>
<table width="100%" border="1" cellspacing="0" class="list-table">
    <thead>
        <tr>
            <th width="60" align="center" >ID</th>
            <th width="120" align="center">訂單編號</th>
            <th width="80" align="center" >付款方式</th>
            <th width="80" align="center" >付款狀態</th>
            <th width="80" align="center" >訂單狀態</th>
            <th width="100" align="center" >訂單金額</th>
            <th width="100" align="center">購買會員</th>
            <th width="100" align="center">收件人</th>
            <th width="120" align="center">最後更新時間</th>
            <th width="120" align="center">訂單建立時間</th>
        </tr>
    </thead>
    <tbody id="the-list" class="list:cat">
        <?php
			foreach($rows as $row){
		?>
        <tr>
            <th align="center"><?php echo $row["order_id"]; ?></th>
            <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["order_sno"]; ?></td>
            <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $ary_payment_type[$row["order_payment_type"]]; ?></td>
            <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $ary_payment_state[$row["order_payment_state"]]; ?></td>
            <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $ary_order_state[$row["order_state"]]; ?></td>
            <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["order_total_price"]; ?></td>
            <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["member_name"]; ?></td>
            <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["order_recipient_name"]; ?></td>
            <td align="center"><?php echo $row["order_update_time"]; ?></td>
            <td align="center"><?php echo $row["order_create_time"]; ?></td>
        </tr>
        <?php
			}
		?>
    </tbody>
    <tfoot>
    </tfoot>
</table>
</body>
</html>
<?php 
$outStr = ob_get_contents(); 
ob_end_clean(); 
	
header("Content-type:application/vnd.ms-excel");
header("Accept-Ranges: bytes"); 
header("Accept-Length: ".strlen($outStr)); 
		
header("Content-Disposition: attachment; filename=order.xls"); 
// 輸出文件內容          
echo $outStr;
?>
