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
		getOrder($reind, $table_contact, $id_column, $ind_column, $nid, "");
	}
} 
//刪除
if(get("is_del", 1) == 'y'){
	$did = get("did");
  	$db -> query("DELETE FROM $table_contact WHERE $id_column = $did");
	script("刪除成功");
}

$count = 0;
$sql_str = "";

if($keyword != ""){
	$sql_str .= " AND contact_email LIKE '%$keyword%'";
}
if($is_reply != ""){
	$sql_str .= " AND contact_reply = '$is_reply'";
}

$sql = "SELECT *
		FROM $table_contact
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
<script src="../../scripts/jquery-1.6.1rc1.min.js"></script>
</head>

<body>
<div id="mgbody-content">
    <div id="panel"> </div>
    <p class="slide"> <?php /*?><a href="add.php?<?php echo $query_str; ?>" class="btn-slideNo">新增</a><?php */?> </p>
    <div id="adminlist">
        <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle; ?> >&nbsp;&nbsp;列表</h2>
        <br>
        <form method="get" action="index.php">
            留言信箱:
            <input name="keyword" type="text" size="20" value="<?php echo $keyword; ?>">
            &nbsp;&nbsp;
            是否回覆:
            <select name="is_reply" id="is_reply">
                <option value="" <?php echo ($is_reply == "") ? "selected" : ""; ?>>不限</option>
                <option value="1" <?php echo ($is_reply == "1") ? "selected" : ""; ?>>回覆</option>
                <option value="0" <?php echo ($is_reply == "0") ? "selected" : ""; ?>>未回覆</option>
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
                        <th align="left">留言信箱</th>
                        <th width="60" align="center">回覆</th>
                        <th width="120" align="center">最後更新時間</th>
                        <th width="120" align="center">留言時間</th>
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
                        <th align="center"><?php echo $row["contact_id"]; ?></th>
                        <td align="left" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["contact_email"]; ?></td>
                        <td align="center"><?php echo $ary_yn[$row["contact_reply"]]; ?></td>
                        <td align="center"><?php echo $row["contact_update_time"]; ?></td>
                        <td align="center"><?php echo $row["contact_create_time"]; ?></td>
                        <td align="center"><a href="?remove=up&nid=<?php echo $row["contact_id"].'&'.$query_str; ?>">上移</a></td>
                        <td align="center"><a href="?remove=down&nid=<?php echo $row["contact_id"].'&'.$query_str; ?>">下移</a></td>
                        <td align="center"><a href="edit.php?id=<?php echo $row["contact_id"].'&'.$query_str; ?>">修改</a></td>
                        <td align="center" ><a href="index.php?isdel=y&did=<?php echo $row["contact_id"].'&'.$query_str; ?>" onClick="return confirm('您確定要刪除這筆記錄?')">刪除</a></td>
                    </tr>
                    <?php
						}
						$db -> close();
					?>
                </tbody>
                <tfoot>
                    <tr>
                        <th height="30" colspan="9" align="right"  class="tfoot" scope="col"><?=showpage()?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</body>
</html>