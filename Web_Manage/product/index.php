<?php 
include_once("_config.php");
include_once($inc_path."_getpage.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();
//排序
$reind = trim(get("remove", 1));
if($reind != ""){
	$nid = get("nid");
	if($nid > 0){
		getOrder($reind, $table_product, $id_column, $ind_column, $nid, "");
	}
} 
//刪除
if(get("is_del", 1) == 'y'){
	$did = get("did");
	if(chkOrder($did)){
		script("此商品有會員訂購,請勿刪除!");
	}else{
	  	$db -> query("DELETE FROM $table_product WHERE $id_column = $did");
		script("刪除成功");
	}
}

function chkOrder($did){
	global $table_orderdetail, $db;
	$result = false;
	$sql = "SELECT * FROM $table_orderdetail WHERE orderdetail_product_id = '$did'";
	$row = $db -> query_first($sql);
	if($row){
		$result = true;
	}
	return $result;
}

$count = 0;
$sql_str = "";

if($type != ""){
	$sql_str .= " AND product_type_id = $type";
}
if($serial != ""){
	$sql_str .= " AND product_serial_id = $serial";
}
if($keyword != ""){
	$sql_str .= " AND (product_name_tw LIKE '%$keyword%' OR product_name_en LIKE '%$keyword%')";
}
if($is_show != ""){
	$sql_str .= " AND product_is_show = $is_show";
}

$sql = "SELECT product_id, product_sno, product_type_id, product_serial_id, product_name_tw, product_name_en, product_sell_price, product_special_price, product_weekly, product_hot, product_pic1, product_stock, product_is_show, product_update_time, productserial_name, (SELECT COUNT(storelog_id) FROM $table_storelog WHERE storelog_product_id = $table_product.product_id) AS amount
		FROM $table_product 
		LEFT JOIN $table_productserial ON product_serial_id = productserial_id 
		WHERE 1 $sql_str
		ORDER BY $ind_column DESC";
getsql($sql, 15, $query_str);
$rows = $db -> fetch_all_array($sql);

$sql_serial = "SELECT *
				FROM $table_productserial 
				WHERE productserial_is_show = 1 AND productserial_type_id = '$type'
				ORDER BY productserial_ind DESC";
$rows_serial = $db -> fetch_all_array($sql_serial);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Untitled Document</title>
<link href="../css/admin_style_gray.css" rel="stylesheet" />
<link href="../../ui/fancybox/jquery.fancybox.css" rel="stylesheet">
<script src="../../scripts/jquery-1.6.1rc1.min.js"></script>
<script src="../../ui/fancybox/jquery.fancybox.pack.js"></script>
<script>
$(document).ready(function(e) {
	$("#type").change(function(e) {
        var val = $(this).val();
		if(val != ""){
			$.ajax({
				url : "get_serial.php",
				data : {type : val, action : 'index'},
				type : "post",
				dataType : "json",
				async : false,
				success : function(data){
					if(data.result){
						$("#serial").html(data.list);
					}else{
						alert(data.list);
					}
				}
			});
		}else{
			$("#serial").html('<option value="" <?php echo ($serial == "") ? "selected" : ""; ?>>不限</option>');
		}
    });
	
	$(".items").fancybox({
		beforeLoad: function() {
				this.title = $(this.element).attr('caption');
			},
		width		: '90%',
		height		: '90%',
		autoSize	: false
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
            分類:
            <select name="type" id="type">
                <option value="" <?php echo ($type == "") ? "selected" : ""; ?>>不限</option>
                <?php 
				foreach($ary_product_type as $key => $val){
				?>
                <option value="<?php echo $key; ?>" <?php echo ($type == $key) ? "selected" : ""; ?>><?php echo $val; ?></option>
                <?php
					}
				?>
            </select>
            &nbsp;&nbsp;
            系列:
            <select name="serial" id="serial">
                <option value="" <?php echo ($serial == "") ? "selected" : ""; ?>>不限</option>
                <?php
				foreach($rows_serial as $row_serial){
				?>
                	<option value="<?php echo $row_serial["productserial_id"]; ?>" <?php echo ($serial == $row_serial["productserial_id"]) ? "selected" : ""; ?>><?php echo $row_serial["productserial_name"]; ?></option>
                <?php
                }
				?>
            </select>
            &nbsp;&nbsp;
            名稱:
            <input name="keyword" type="text" size="20" value="<?php echo $keyword; ?>">
            &nbsp;&nbsp;
            是否顯示:
            <select name="is_show" id="is_show">
                <option value="" <?php echo ($is_show == "") ? "selected" : ""; ?>>不限</option>
                <option value="1" <?php echo ($is_show == "1") ? "selected" : ""; ?>>顯示</option>
                <option value="0" <?php echo ($is_show == "0") ? "selected" : ""; ?>>不顯示</option>
            </select>
            &nbsp;&nbsp;
            <input name="" type="submit" value="搜尋" />
            &nbsp;&nbsp;共<?php echo $count; ?>筆資料
        </form>
        <div class="accordion">
            <table width="100%" cellspacing="0" class="list-table">
                <thead>
                    <tr>
                        <th width="80" align="center" >商品編號</th>
                        <th width="120" align="center" >分類</th>
                        <th width="120" align="center" >系列</th>
                        <th width="120" align="center" >圖片</th>
                        <th align="left" >名稱</th>
                        <th width="60" align="center" >庫存</th>
                        <th width="120" align="center">庫存變動</th>
                        <th width="60" align="center" >每周最新</th>
                        <th width="60" align="center" >熱門商品</th>
                        <th width="120" align="center">最後更新時間</th>
                        <th width="60" align="center" >是否顯示</th>
                        <th width="60" height="28" align="center" >上移</th>
                        <th width="60" height="28" align="center" >下移</th>
                        <th width="60" height="28" align="center" >修改</th>
                        <th width="60" align="center" >刪除</th> 
                    </tr>
                </thead>
                <tbody id="the-list" class="list:cat">
                    <?php
						foreach($rows as $row){
					?>
                    <tr>
                        <th align="center"><?php echo $row["product_sno"]; ?></th>
                        <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $ary_product_type[$row["product_type_id"]]; ?></td>
                        <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["productserial_name"]; ?></td>
                        <td align="center" style="word-wrap: break-word; word-break: break-all;"><img src="<?php echo $file_path.'m'.$row["product_pic1"]; ?>" width="100" onerror="javascript:this.src='../images/nopic.jpg'"></td>
                        <td align="left" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["product_name_tw"]." / ".$row["product_name_en"]; ?></td>
                        <td align="center"><?php echo $row["product_stock"]; ?></td>
                        <th align="center"><a class="items fancybox.iframe" href="log_list.php?product_id=<?php echo $row["product_id"]; ?>" style="width:50px;" caption="<?php echo $row["product_name_tw"]."/".$row["product_name_en"].")"; ?>"><?php echo $row["amount"]; ?></a></th>
                        <td align="center"><?php echo $ary_yn[$row["product_weekly"]]; ?></td>
                        <td align="center"><?php echo $ary_yn[$row["product_hot"]]; ?></td>
                        <td align="center"><?php echo $row["product_update_time"]; ?></td>
                        <td align="center"><?php echo $ary_yn[$row["product_is_show"]]; ?></td>
                        <td align="center"><a href="?remove=up&nid=<?php echo $row["product_id"].'&'.$query_str; ?>">上移</a></td>
                        <td align="center"><a href="?remove=down&nid=<?php echo $row["product_id"].'&'.$query_str; ?>">下移</a></td>
                        <td align="center"><a href="edit.php?id=<?php echo $row["product_id"].'&'.$query_str; ?>">修改</a></td>
                        <td align="center" ><a href="index.php?is_del=y&did=<?php echo $row["product_id"].'&'.$query_str; ?>" onClick="return confirm('您確定要刪除這筆記錄?')">刪除</a></td>
                    </tr>
                    <?php
						}
						$db -> close();
					?>
                </tbody>
                <tfoot>
                    <tr>
                        <th height="30" colspan="16" align="right"  class="tfoot" scope="col"><?=showpage()?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</body>
</html>