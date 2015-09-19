<?php 
include_once("_config.php");
include_once($inc_path."_getpage.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();
//排序
/*$reind = trim(get("remove", 1));
if($reind != ""){
	$nid = get("nid");
	if($nid > 0){
		getOrder($reind, $table_product, $id_column, $ind_column, $nid, "");
	}
} */
//刪除
/*if(get("is_del", 1) == 'y'){
	$did = get("did");
	if(chkOrder($did)){
		script("此商品有會員訂購,請勿刪除!");
	}else{
	  	$db -> query("DELETE FROM $table_product WHERE $id_column = $did");
		script("刪除成功");
	}
}*/

$count = 0;
$sql_str = "";

if($type != "" && $keyword != ""){
	switch($type){
		case "1" :
			$sql_str .= " AND product_sno LIKE '%$keyword%'";
			break;
		case "2" :
			$sql_str .= " AND (product_name_tw LIKE '%$keyword%' OR product_name_en LIKE '%$keyword%')";
			break;
		default :
			break;	
	}
}

$sql = "SELECT wish_id, wish_member_id, wish_product_id, wish_create_time, product_id, product_ind, product_sno, product_name_tw, product_name_en, product_pic1, COUNT(wish_member_id) AS amount
		FROM $table_wish 
		LEFT JOIN $table_product ON wish_product_id = product_id 
		WHERE 1 $sql_str
		GROUP BY product_sno
		ORDER BY product_ind DESC";
getsql($sql, 15, $query_str);
$rows = $db -> fetch_all_array($sql);

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
    <p class="slide"> <?php /*?><a href="add.php?<?php echo $query_str; ?>" class="btn-slideNo">新增</a><?php */?> </p>
    <div id="adminlist">
        <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle; ?> >&nbsp;&nbsp;列表</h2>
        <br>
        <form method="get" action="index.php">
            項目:
            <select name="type" id="type">
                <option value="" <?php echo ($type == "") ? "selected" : ""; ?>>不限</option>
				<option value="1" <?php echo ($type == "1") ? "selected" : ""; ?>>商品編號</option>
                <option value="2" <?php echo ($type == "2") ? "selected" : ""; ?>>商品名稱</option>
            </select>
            &nbsp;&nbsp;
            <?php /*?>系列:
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
            &nbsp;&nbsp;<?php */?>
            關鍵字:
            <input name="keyword" type="text" size="20" value="<?php echo $keyword; ?>">
            <?php /*?>&nbsp;&nbsp;
            是否顯示:
            <select name="is_show" id="is_show">
                <option value="" <?php echo ($is_show == "") ? "selected" : ""; ?>>不限</option>
                <option value="1" <?php echo ($is_show == "1") ? "selected" : ""; ?>>顯示</option>
                <option value="0" <?php echo ($is_show == "0") ? "selected" : ""; ?>>不顯示</option>
            </select><?php */?>
            &nbsp;&nbsp;
            <input name="" type="submit" value="搜尋" />
            &nbsp;&nbsp;共<?php echo $count; ?>筆資料
        </form>
        <div class="accordion">
            <table width="100%" cellspacing="0" class="list-table">
                <thead>
                    <tr>
                        <th width="80" align="center" >商品編號</th>
                        <th width="120" align="center" >圖片</th>
                        <th align="left" >商品名稱</th>
                        <th width="120" align="center">選取會員</th>
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
                        <td align="center"><a class="items fancybox.iframe" href="log_list.php?product_id=<?php echo $row["product_id"]; ?>" style="width:50px;" caption="<?php echo $row["product_name_tw"]."/".$row["product_name_en"].")"; ?>"><?php echo $row["amount"]; ?></a></td>
                    </tr>
                    <?php
						}
						$db -> close();
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