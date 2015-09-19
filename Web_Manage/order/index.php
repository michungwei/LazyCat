<?php 
include_once("_config.php");
include_once($inc_path."_getpage.php");

if( get("output",1) !=""){
	header("Location:savetoexcel.php?$query_str");
}

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();
//排序
$reind = trim(get("remove", 1));
if($reind != ""){
	$nid = get("nid", 0);
	if($nid > 0){
		getOrder($reind, $table_order, $id_column, $ind_column, $nid, "");
	}
} 

//刪除
if(get("is_del", 1) == 'y'){
	$did = get("did");
  	$db -> query("DELETE FROM $table_order WHERE $id_column = $did");
	script("刪除成功");
}

$count = 0;
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
	$sql_str.=" AND DATE_FORMAT(order_create_time, '%Y-%m-%d') >= '$s_date'";
}
if($e_date!=""){
	$sql_str.=" AND DATE_FORMAT(order_create_time, '%Y-%m-%d') <= '$e_date'";
}
if($s_price != ""){
	  $sql_str.=" AND order_total_price >= '$s_price'";
}
if($e_price != ""){
  $sql_str.=" AND order_total_price <= '$e_price'";
}	

$sql = "SELECT order_id, order_sno, order_member_id, order_payment_type, order_total_price, order_payment_state, order_state, order_recipient_name, order_create_time, order_update_time, member_id, member_name
		FROM $table_order 
		LEFT JOIN $table_member ON order_member_id = member_id 
		WHERE 1 $sql_str
		ORDER BY $ind_column DESC";
		
getsql($sql, 15, $query_str);
$rows = $db -> fetch_all_array($sql);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Untitled Document</title>
<link href="../css/admin_style_gray.css" rel="stylesheet" />
<link href="../../ui/jquery-ui-1.10.3.custom/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" />
<script src="../../scripts/jquery-1.6.1rc1.min.js"></script>
<script src="../../ui/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../../ui/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.datepicker.js"></script>
<script>
$(document).ready(function(e) {
    $( "#s_date" ).datepicker({ 
		showOn: "both",
  		buttonImageOnly: true,
  		buttonImage: "../images/calendar.gif",
  		buttonText: "請選日期",
		dateFormat: "yy-mm-dd" 
	});	
	$( "#e_date" ).datepicker({ 
		showOn: "both",
  		buttonImageOnly: true,
  		buttonImage: "../images/calendar.gif",
  		buttonText: "請選日期",
		dateFormat: "yy-mm-dd" 
	});
});
</script>
</head>

<body>
<div id="mgbody-content">
    <div id="panel"> </div>
    <p class="slide">
        <?php /*?><a href="add.php?<?php echo $query_str; ?>" class="btn-slideNo">新增</a><?php */?>
    </p>
    <div id="adminlist">
        <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle; ?> >&nbsp;&nbsp;列表</h2>
        <br>
        <form method="get" action="index.php">
            <select name="type" id="type">
                <option value="" <?php echo ($type == "") ? "selected" : ""; ?>>--請選擇--</option>
                <option value="1" <?php echo ($type == 1) ? "selected" : ""; ?>>訂單編號</option>
                <option value="2" <?php echo ($type == 2) ? "selected" : ""; ?>>會員</option>
                <option value="3" <?php echo ($type == 3) ? "selected" : ""; ?>>收件人</option>
            </select>
            &nbsp;&nbsp;
            關鍵字:
            <input name="keyword" type="text" size="20" value="<?php echo $keyword; ?>">
            &nbsp;&nbsp;
            付款狀態:
            <select name="is_pay" id="is_pay">
                <option value="" <?php echo ($is_pay == "") ? "selected" : ""; ?>>不限</option>
                <option value="0" <?php echo ($is_pay == "0") ? "selected" : ""; ?>>未付款</option>
                <option value="1" <?php echo ($is_pay == "1") ? "selected" : ""; ?>>已付款</option>
            </select>
            &nbsp;&nbsp;
            訂單狀態:
            <select name="order_state" id="order_state">
                <option value="" <?php echo ($order_state == "") ? "selected" : ""; ?>>不限</option>
                <option value="0" <?php echo ($order_state == "0") ? "selected" : ""; ?>>處理中</option>
                <option value="1" <?php echo ($order_state == "1") ? "selected" : ""; ?>>可出貨</option>
                <option value="2" <?php echo ($order_state == "2") ? "selected" : ""; ?>>己出貨</option>
                <option value="3" <?php echo ($order_state == "3") ? "selected" : ""; ?>>交易完成</option>
                <option value="10" <?php echo ($order_state == "10") ? "selected" : ""; ?>>退貨</option>
                <option value="11" <?php echo ($order_state == "11") ? "selected" : ""; ?>>交易取消</option>
            </select>
            &nbsp;&nbsp;
            <br /><br />
            訂單日期:
            <input name="s_date" type="text" id="s_date" value="<?php echo $s_date?>" size="12" maxlength="12" style=" width:80px"/>
            ~
            <input name="e_date" type="text" id="e_date" value="<?php echo $e_date?>" size="12" maxlength="12" style=" width:80px"/>
            &nbsp;&nbsp;
            訂單金額:
            <input name="s_price" type="text" id="s_price"  value="<?php echo $s_price?>" size="12" maxlength="12" style=" width:80px"/>
            ~
            <input name="e_price" type="text" id="e_price"  value="<?php echo $e_price?>" size="12" maxlength="12" style=" width:80px"/>
            &nbsp;&nbsp;
            <input name="" type="submit" value="搜尋" />
            &nbsp;&nbsp;
            <input name="output" type="submit" value="匯出Excel" />
            &nbsp;&nbsp;共<?php echo $count; ?>筆資料
        </form>
        <div class="accordion">
            <table width="100%" cellspacing="0" class="list-table">
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
                        <th align="left"></th>
                        <th width="120" align="center">最後更新時間</th>
                        <th width="120" align="center">訂單建立時間</th>
                        <th width="60" height="28" align="center" >修改</th>
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
                        <td align="left"></td>
                        <td align="center"><?php echo $row["order_update_time"]; ?></td>
                        <td align="center"><?php echo $row["order_create_time"]; ?></td>
                        <td align="center"><a href="edit.php?id=<?php echo $row["order_id"].'&'.$query_str; ?>">修改</a></td>
                    </tr>
                    <?php
						}
						$db -> close();
					?>
                </tbody>
                <tfoot>
                    <tr>
                        <th height="30" colspan="12" align="right"  class="tfoot" scope="col"><?=showpage()?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</body>
</html>