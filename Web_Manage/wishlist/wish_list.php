<?php 
include("_config.php");
include($inc_path."_getpage.php");

$db = new Database($HS, $ID, $PW, $DB);
$db->connect();

$today = getdate();
$today_year = $year != "" ? $year : $today["year"];
$today_month = $month != "" ? $month : $today["mon"];

$start_time = date_format(date_create($today_year."-".$today_month."-1"),'Y-m-d');
$end_time = date_format(date_create($today_year."-".$today_month."-31"),'Y-m-d');
/*
$reind=trim(get("remove",1));
if ($reind!=""){
	$nid=get("nid",0);
	if ($nid>0 ){
		getOrder($reind,$table_seo,"ind",$nid,"");
	}
}
//刪除用的
if (get("isdel",1)=='y'){
$did=get("did");
		$db->query("delete from $table_seo where id='$did'");
	script("刪除成功");
}
*/
$count=0;
$sql_str="";
	
if($start_time!=""){
	$sql_str.=" and DATE_FORMAT(wish_create_time,'%Y-%m-%d')>='$start_time' ";
}
if($end_time!=""){
	$sql_str.=" and DATE_FORMAT(wish_create_time,'%Y-%m-%d')<='$end_time' ";
}

$sql = "SELECT wish_id, wish_member_id, wish_product_id, wish_create_time, product_id, product_ind, product_sno, product_name_tw, product_name_en, product_pic1, COUNT(wish_member_id) AS amount
		FROM $table_wish 
		LEFT JOIN $table_product ON wish_product_id = product_id 
		WHERE 1 $sql_str
		GROUP BY product_sno
		ORDER BY product_ind DESC";
getsql($sql, 15, $query_str);
$rows = $db -> fetch_all_array($sql);

$db->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/admin_style_gray.css" rel="stylesheet" type="text/css" />
<script src="../../scripts/jquery-1.6.1rc1.min.js"></script>
<script>
$(document).ready(function(){
	
	$(".accordion .tableheader:first").addClass("active");
	
	$(".accordion .tableheader").toggle(function(){
		$(this).next().slideDown("fast");
	},function(){
	   $(this).next().slideUp("fast");
	   $(this).siblings("tableheader").removeClass("active");
	});
	
});
</script>
</head>

<body>
<div id="mgbody-content">
    <div id="panel"> <br />
    </div>
    <!--
  <p class="slide"> <a href="add.php?<?php //echo $query_str ?>" class="btn-slide"> 新增 </a></p>
  -->
    <div id="adminlist">
        <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle?> >&nbsp;&nbsp;WISHLIST統計</h2>
        <br>
        <form method="get" action="wish_list.php">
            訂單日期:
            <input name="year" type="text" id="year" size="6" value="<?php echo $today_year; ?>" />
            &nbsp;年
            &nbsp;&nbsp;
            <select name="month" id="month">
                <?php
					for($i = 1; $i <= 12; $i ++){
				?>
                <option value="<?php echo $i; ?>" <?php echo ($i == $today_month) ? 'selected' : ""; ?>><?php echo $i; ?></option>
                <?php
					}
				?>
            </select>
            &nbsp;月
            
            &nbsp;&nbsp;
            <input name="" type="submit" value="搜尋" />
            &nbsp;&nbsp;共<?php echo $count?>筆資料
        </form>
        <div class="accordion">
            <table width="100%" cellspacing="0" class="list-table" border="0">
                <thead>
                    <tr>
                        <th width="80" align="center" >商品編號</th>
                        <th width="120" align="center" >圖片</th>
                        <th align="left" >商品名稱</th>
                        <th width="120" align="center">數量</th>
                    </tr>
                </thead>
                <tbody id="the-list" class="list:cat">
                    <?php
						foreach($rows as $row){
					?>
                    <tr>
                        <th align="center"><?php echo $row["product_sno"]; ?></th>
                        <td align="center" style="word-wrap: break-word; word-break: break-all;"><img src="<?php echo $file_path.'m'.$row["product_pic1"]; ?>" width="100" onerror="javascript:this.src='../images/nopic.jpg'"></td>
                        <td align="left" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["product_name_tw"]." / ".$row["product_name_en"]; ?></td>
                        <td align="center"><?php echo $row["amount"]; ?></td>
                    </tr>
					<?php
						}
					?>
                </tbody>
                <tfoot>
                    <tr>
                        <th height="30" colspan="4" align="right"  class="tfoot" scope="col"><?=showpage()?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</body>
</html>