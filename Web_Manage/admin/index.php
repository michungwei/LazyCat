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
		getOrder($reind, $table_admin, $id_column, $ind_column, $nid, "");
	}
}
//刪除
if(get("isdel", 1) == 'y'){
	$did = get("did");
  	$db -> query("DELETE FROM $table_admin WHERE $id_column = '$did'");
	script("刪除成功");
}

$count = 0;
$sql_str = "";

if($keyword != ""){
	$sql_str .= " AND admin_account LIKE '%$keyword%' ";
}
//if($is_show != ""){
//	$sql_str .= " AND admin_is_show = $is_show";
//}
$sql = "SELECT * 
		FROM $table_admin 
		WHERE 1 $sql_str AND admin_level <> 1
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
            關鍵字:
            <input name="keyword" type="text" size="20" value="<?php echo $keyword; ?>">
            <?php /*?>&nbsp;&nbsp;
            顯示:
            <select name="isshow" id="isshow">
                <option value="" <?php echo ($is_show == "") ? "selected" : ""; ?>>不限</option>
                <option value="1" <?php echo ($is_show == "1") ? "selected" : ""; ?>>顯示</option>
                <option value="0" <?php echo ($is_show == "0") ? "selected" : ""; ?>>隱藏</option>
            </select><?php */?>
            &nbsp;&nbsp;
            <input name="" type="submit" value="搜尋" />
            &nbsp;&nbsp;共<?php echo $count; ?>筆資料
        </form>
        <div class="accordion">
            <table width="100%" cellspacing="0" class="list-table">
                <thead>
                    <tr>
                        <th width="60" align="center" >ID</th>
                        <th width="100" align="center" >帳號</th>
                        <th align="left" ></th>
                        <th width="120" align="center" >最後更新時間</th>
                        <th width="120" align="center" >建立時間</th>
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
                        <th align="center"><?php echo $row["admin_id"]; ?></th>
                        <td align="center" style="word-wrap:break-word;overflow:hidden;"><?php echo $row["admin_account"]; ?></td>
                        <td align="left" ></td>
                        <td align="center" ><?php echo $row["admin_update_time"]; ?></td>
                        <td align="center" ><?php echo $row["admin_create_time"]; ?></td>
                        <td align="center"><a href="?remove=up&nid=<?php echo $row["admin_id"]."&".$query_str; ?>">上移</a></td>
                        <td align="center"><a href="?remove=down&nid=<?php echo $row["admin_id"]."&".$query_str; ?>">下移</a></td>
                        <td align="center"><a href="edit.php?id=<?php echo $row["admin_id"]."&".$query_str; ?>">修改</a></td>
                        <td align="center" ><a href="index.php?isdel=y&did=<?php echo $row["admin_id"]."&".$query_str; ?>" onClick="return confirm('您確定要刪除這筆記錄?')">刪除</a></td>
                    </tr>
                    <?php
						}
						$db -> close();
					?>
                </tbody>
                <tfoot>
                    <tr>
                        <th height="30" colspan="9" align="right"  class="tfoot" scope="col"><?=showPage()?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</body>
</html>