<?php 
include_once("_config.php");
include_once($inc_path."_getpage.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();
//排序
$reind = trim(get("remove", 1));
if($reind != ""){
	$nid = get("nid", 0);
	if($nid > 0){
		getOrder($reind, $table_discharge, $id_column, $ind_column, $nid, "");
	}
} 

//刪除
if(get("is_del", 1) == 'y'){
	$did = get("did");
  	$db -> query("DELETE FROM $table_discharge WHERE $id_column = $did");
	script("刪除成功");
}

$count = 0;
$sql_str = "";

if($keyword != ""){
	$sql_str .= " AND promo_name LIKE '%$keyword%'";
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

$sql = "SELECT *
		FROM $table_discharge
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
    <p class="slide"> <a href="add.php?<?php echo $query_str; ?>" class="btn-slideNo">新增</a> </p>
    <div id="adminlist">
        <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle; ?> >&nbsp;&nbsp;列表</h2>
        <br>
        <form method="get" action="index.php">
            關鍵字:
            <input name="keyword" type="text" size="20" value="<?php echo $keyword; ?>">
            &nbsp;&nbsp;
            <br /><br />
            建立日期:
            <input name="s_date" type="text" id="s_date" value="<?php echo $s_date?>" size="12" maxlength="12" style=" width:80px"/>
            ~
            <input name="e_date" type="text" id="e_date" value="<?php echo $e_date?>" size="12" maxlength="12" style=" width:80px"/>
            &nbsp;&nbsp;
            抵用金金額:
            <input name="s_price" type="text" id="s_price"  value="<?php echo $s_price?>" size="12" maxlength="12" style=" width:80px"/>
            ~
            <input name="e_price" type="text" id="e_price"  value="<?php echo $e_price?>" size="12" maxlength="12" style=" width:80px"/>
            &nbsp;&nbsp;
            <input name="" type="submit" value="搜尋" />
            &nbsp;&nbsp;
            共<?php echo $count; ?>筆資料
        </form>
        <div class="accordion">
            <table width="100%" cellspacing="0" class="list-table">
                <thead>
                    <tr>
                        <th width="60" align="center" >ID</th>
                        <th width="120" align="center">名稱</th>
                        <th width="60" align="center">啟用</th>
                        <th width="60" align="center">永久</th>
                        <th width="120" align="center">開始時間</th>
                        <th width="120" align="center">結束時間</th>                        
                        <th align="left"></th>
                        <th width="120" align="center">最後更新時間</th>
                        <th width="120" align="center">建立時間</th>
                        <th width="60" height="28" align="center" >修改</th>
                        <th width="60" height="28" align="center" >刪除</th>
                    </tr>
                </thead>
                <tbody id="the-list" class="list:cat">
                    <?php
						foreach($rows as $row){
					?>
                    <tr>
                        <th align="center"><?php echo $row["discharge_id"]; ?></th>
                        <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["discharge_name"]; ?></td>
                        <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $ary_yn[$row["discharge_enable"]]; ?></td>
                        <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $ary_yn[$row["discharge_forever"]]; ?></td>
                        <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["discharge_start_time"]; ?></td>
                        <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["discharge_end_time"]; ?></td>
                        <td align="left"></td>
                        <td align="center"><?php echo $row["discharge_update_time"]; ?></td>
                        <td align="center"><?php echo $row["discharge_create_time"]; ?></td>
                        <td align="center"><a href="edit.php?id=<?php echo $row["discharge_id"].'&'.$query_str; ?>">修改</a></td>
                        <td align="center" ><a href="index.php?is_del=y&did=<?php echo $row["discharge_id"].'&'.$query_str; ?>" onClick="return confirm('您確定要刪除這筆記錄?')">刪除</a></td>
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