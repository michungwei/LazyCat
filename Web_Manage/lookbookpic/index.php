<?php 
include_once("_config.php");
include_once($inc_path."_getpage.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();
//排序
//$reind = trim(get("remove", 1));
//if($reind != ""){
//	$nid = get("nid");
//	if($nid > 0){
//		getOrder($reind, $table_lookbookpic, $id_column, $ind_column, $nid, "");
//	}
//} 
//刪除
if(get("is_del", 1) == 'y'){
	$did = get("did");

	$db -> query("DELETE FROM $table_lookbookpic WHERE $id_column = $did");
	script("刪除成功");
}

$count = 0;
$sql_str = "";

if($type != ""){
	$sql_str .= " AND lookbookpic_lookbook_id = $type";
}

if($is_show != ""){
	$sql_str .= " AND lookbookpic_is_show = $is_show";
}

$sql = "SELECT lookbookpic_id, lookbookpic_lookbook_id, lookbookpic_pic, lookbookpic_is_show, lookbookpic_update_time, lookbook_title
		FROM $table_lookbookpic LEFT JOIN $table_lookbook ON lookbookpic_lookbook_id = lookbook_id
		WHERE 1 $sql_str
		ORDER BY lookbookpic_lookbook_id DESC, $id_column DESC";
getsql($sql, 15, $query_str);
$rows = $db -> fetch_all_array($sql);

$sql_type = "SELECT *
			 FROM $table_lookbook 
			 WHERE lookbook_is_show = 1
			 ORDER BY lookbook_ind DESC";
$rows_type = $db -> fetch_all_array($sql_type);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Untitled Document</title>
<link href="../css/admin_style_gray.css" rel="stylesheet" />
<script src="../../scripts/jquery-1.6.1rc1.min.js"></script>

</head>

<body>
<div id="mgbody-content">
    <div id="panel"> </div>
    <p class="slide"> <a href="add.php?<?php echo $query_str; ?>" class="btn-slideNo">新增</a> </p>
    <div id="adminlist">
        <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle; ?> >&nbsp;&nbsp;列表</h2>
        <br>
        <form method="get" action="index.php">
            宣傳冊:
            <select name="type" id="type">
                <option value="" <?php echo ($type == "") ? "selected" : ""; ?>>不限</option>
                <?php 
				foreach($rows_type as $row_type){
				?>
                <option value="<?php echo $row_type["lookbook_id"]; ?>" <?php echo ($type == $row_type["lookbook_id"]) ? "selected" : ""; ?>><?php echo $row_type["lookbook_title"]; ?></option>
                <?php
					}
				?>
            </select>
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
                        <th width="80" align="center" >編號</th>
                        <th width="150" align="center" >圖片</th>
                        <th align="left" >宣傳冊名稱</th>
                        <th width="120" align="center">最後更新時間</th>
                        <th width="60" align="center" >是否顯示</th>
                        <!--<th width="60" height="28" align="center" >上移</th>
                        <th width="60" height="28" align="center" >下移</th>-->
                        <th width="60" height="28" align="center" >修改</th>
                        <th width="60" align="center" >刪除</th> 
                    </tr>
                </thead>
                <tbody id="the-list" class="list:cat">
                    <?php
						foreach($rows as $row){
					?>
                    <tr>
                        <th align="center"><?php echo $row["lookbookpic_id"]; ?></th>
                        <td align="center" style="word-wrap: break-word; word-break: break-all;"><img src="<?php echo $file_path.'m'.$row["lookbookpic_pic"]; ?>" width="120" onerror="javascript:this.src='../images/nopic.jpg'"></td>
                        <td align="left" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["lookbook_title"]; ?></td>
                        <td align="center"><?php echo $row["lookbookpic_update_time"]; ?></td>
                        <td align="center"><?php echo $ary_yn[$row["lookbookpic_is_show"]]; ?></td>
                        <?php /*?><td align="center"><a href="?remove=up&nid=<?php echo $row["product_id"].'&'.$query_str; ?>">上移</a></td>
                        <td align="center"><a href="?remove=down&nid=<?php echo $row["product_id"].'&'.$query_str; ?>">下移</a></td><?php */?>
                        <td align="center"><a href="edit.php?id=<?php echo $row["lookbookpic_id"].'&'.$query_str; ?>">修改</a></td>
                        <td align="center" ><a href="index.php?is_del=y&did=<?php echo $row["lookbookpic_id"].'&'.$query_str; ?>" onClick="return confirm('您確定要刪除這筆記錄?')">刪除</a></td>
                    </tr>
                    <?php
						}
						$db -> close();
					?>
                </tbody>
                <tfoot>
                    <tr>
                        <th height="30" colspan="7" align="right"  class="tfoot" scope="col"><?=showpage()?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</body>
</html>