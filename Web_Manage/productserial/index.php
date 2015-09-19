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
		getOrder($reind, $table_productserial, $id_column, $ind_column, $nid, "");
	}
}
//刪除
if(get("isdel", 1) == 'y'){
	$did = get("did");
	if(chkType($did)){
		script("此系列已被使用!請勿刪除!!");
	}else{
		$db -> query("DELETE FROM $table_productserial WHERE $id_column = '$did'");
		script("刪除成功");
	}
}

function chkType($serial_id){
	global $db, $table_product; 
	$result = false;
	$row = $db ->query_first("SELECT * FROM $table_product WHERE product_serial_id = $serial_id");
	if($row){
		$result = true;
	}
	return $result;
}

$count = 0;
$sql_str = "";

if($type != ""){
	$sql_str .= " AND productserial_type_id = '$type%'";
}

if($keyword != ""){
	$sql_str .= " AND productserial_name LIKE '%$keyword%'";
}

if($is_show != ""){
	$sql_str .= " AND productserial_is_show = $is_show";
}

$sql = "SELECT *, (SELECT COUNT(product_id) FROM $table_product WHERE product_serial_id = productserial_id) AS all_count
		FROM $table_productserial 
		WHERE 1 $sql_str
		ORDER BY $ind_column DESC";
getSql($sql, 15, $query_str);
?>
<!doctype html>
<html>
<head>
<title>Untitled Document</title>
<meta charset="utf-8" />
<link href="../css/admin_style_gray.css" rel="stylesheet" />
<script src="../../scripts/jquery-1.6.1rc1.min.js"></script>
</head>

<body>
<div id="mgbody-content">
    <div id="panel"> <br />
    </div>
    <p class="slide"> <a href="add.php?<?php echo $query_str; ?>" class="btn-slideNo">新增</a></p>
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
            名稱:
            <input name="keyword" type="text" size="20" value="<?php echo $keyword; ?>">
            &nbsp;&nbsp;
            顯示:
            <select name="is_show" id="is_show">
                <option value="" <?php echo ($is_show == "") ? "selected" : ""; ?>>不限</option>
                <option value="1" <?php echo ($is_show == "1") ? "selected" : ""; ?>>顯示</option>
                <option value="0" <?php echo ($is_show == "0") ? "selected" : ""; ?>>隱藏</option>
            </select>
            &nbsp;&nbsp;
            <input name="" type="submit" value="搜尋" />
            &nbsp;&nbsp;共<?php echo $count; ?>筆資料
        </form>
        <div class="accordion">
            <table width="100%" cellspacing="0" class="list-table">
                <thead>
                    <tr>
                        <th width="60" align="center" >ID</th>
                        <th width="100" align="center" >分類</th>
                        <th width="120" align="center">圖片</th>
                        <th align="left" >名稱</th>
                        <th width="80" align="center">商品數</th>
                        <th width="60" align="center">是否顯示</th>
                        <th width="120" align="center" >最後更新時間</th>
                        <th width="60" height="28" align="center" >上移</th>
                        <th width="60" height="28" align="center" >下移</th>
                        <th width="60" height="28" align="center" >修改</th>
                        <th width="60" align="center" >刪除</th>
                    </tr>
                </thead>
                <tbody id="the-list" class="list:cat">
                    <?php
						$rows = $db -> fetch_all_array($sql);
						foreach($rows as $row){
					?>
                    <tr>
                        <td align="center"><?php echo $row["productserial_id"]; ?></td>
                        <td align="center" ><?php echo $ary_product_type[$row["productserial_type_id"]]; ?></td>
                        <td align="center" ><img src="<?php echo $file_path.'m'.$row["productserial_pic"]; ?>" width="100" onerror="javascript:this.src='../images/nopic.jpg'"></td>
                        <td align="left" ><?php echo $row["productserial_name"]; ?></td>
                        <td align="center"><a href="../product/index.php?type=<?php echo $row["productserial_type_id"]; ?>" class="items"><?php echo $row["all_count"]; ?></a></td>
                        <td align="center" ><?php echo $ary_yn[$row["productserial_is_show"]]; ?></td>
                        <td align="center" ><?php echo $row["productserial_update_time"]; ?></td>
                        <td align="center"><a href="?remove=up&nid=<?php echo $row["productserial_id"].'&'.$query_str; ?>">上移</a></td>
                        <td align="center"><a href="?remove=down&nid=<?php echo $row["productserial_id"].'&'.$query_str; ?>">下移</a></td>
                        <td align="center"><a href="edit.php?id=<?php echo $row["productserial_id"].'&'.$query_str; ?>">修改</a></td>
                        <td align="center" ><a href="index.php?isdel=y&did=<?php echo $row["productserial_id"].'&'.$query_str; ?>" onClick="return confirm('您確定要刪除這筆記錄?')">刪除</a></td>
                    </tr>
                    <?php
						}
						$db -> close();
					?>
                </tbody>
                <tfoot>
                    <tr>
                        <th height="30" colspan="11" align="right"  class="tfoot" scope="col"><?=showPage()?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</body>
</html>