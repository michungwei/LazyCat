<?php 
include_once("_config.php");
include_once($inc_path."_getpage.php");

/*if(get("output",1) != ""){
	header("Location:savetoexcel.php?$querystr");
}*/

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

//排序
$reind = trim(get("remove", 1));
if($reind != ""){
	$nid = get("nid", 0);
	if($nid > 0){
		getOrder($reind, $table_member, $id_column, $ind_column, $nid, "");
	}
}
//刪除
if(get("isdel", 1) == 'y'){
	$did = get("did");
	$db -> query("DELETE FROM $table_member WHERE $id_column = '$did'");
	script("刪除成功");
}

$count = 0;
$sql_str = "";

if($type != "" && $keyword != ""){
	switch($type){
		case 1 :
			$sql_str .= " AND member_name LIKE '%$keyword%'";
			break;
		case 2 :
			$sql_str .= " AND member_account LIKE '%$keyword%'";
			break;
		case 3 :
			$sql_str .= " AND member_mobile LIKE '%$keyword%'";
			break;
		case 4 :
			$sql_str .= " AND member_email LIKE '%$keyword%'";
			break;
		default :
			break;
	}	
}

$sql = "SELECT * 
		FROM  $table_member 
		WHERE 1 $sql_str 
		ORDER BY $id_column DESC";
getsql($sql, 15, $query_str);
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
    <div id="panel"> <br />
    </div>
    <p class="slide"> <a href="add.php?<?php echo $query_str; ?>" class="btn-slide">新增</a></p>
    <div id="adminlist">
        <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle; ?> >&nbsp;&nbsp;列表</h2>
        <br>
        <form method="get" action="index.php">
            <select name="type" id="type">
                <option value="">-- 請選擇 --</option>
                <option value="1" <?php echo ($type == "1") ? "selected" : ""; ?>>姓名</option>
                <option value="2" <?php echo ($type == "2") ? "selected" : ""; ?>>帳號</option>
                <option value="3" <?php echo ($type == "3") ? "selected" : ""; ?>>手機</option>
                <option value="4" <?php echo ($type == "4") ? "selected" : ""; ?>>email</option>
            </select>
            &nbsp;&nbsp;
            關鍵字:
            <input name="keyword" type="text" size="20" value="<?php echo $keyword; ?>">
            &nbsp;&nbsp;
            <?php /*?>顯示:
            <select name="isshow" id="isshow">
                <option value="" <?php echo ($is_show=="") ? "selected" : ""?>>不限</option>
                <option value="1" <?php echo ($is_show=="1") ? "selected" : ""?>>顯示</option>
                <option value="0" <?php echo ($is_show=="0") ? "selected" : ""?>>隱藏</option>
            </select>
            &nbsp;&nbsp;<?php */?>
            <input name="" type="submit" value="搜尋" class="searchbtu" />
            &nbsp;&nbsp; 
            <!--<input name="output" type="submit" value="匯出Excel" />--> 
            &nbsp;&nbsp;共<?php echo $count; ?>筆資料
        </form>
        <div class="accordion">
            <table width="100%" cellspacing="0" class="list-table">
                <thead>
                    <tr>
                        <th width="50" align="center" >ID</th>
                        <th width="100" align="center" >帳號</th>
                        <th width="100" align="center" >姓名</th>
                        <th width="150" align="center" >email</th>
                        <th width="150" align="center" >手機</th>
                        <th align="left" ></th>
                        <!--<th width="50" align="center" >顯示</th>-->
                        <th width="120" align="center" >最後更新時間</th>
                        <th width="120" align="center" >建立時間</th>
                        <!--<th width="50" height="28" align="center" >上移</th>
                        <th width="50" height="28" align="center" >下移</th>-->
                        <th width="50" height="28" align="center" >修改</th>
                        <th width="50" align="center" >刪除</th>
                    </tr>
                </thead>
                <tbody id="the-list" class="list:cat">
                    <?php
						$rows = $db->fetch_all_array($sql);
						foreach($rows as $row){
					?>
                    <tr>
                        <th align="center"><?php echo $row["member_id"]; ?></th>
                        <td align="center"><?php echo $row["member_account"]; ?></td>
                        <td align="center"><?php echo $row["member_name"]; ?></td>
                        <td align="center"><?php echo $row["member_email"]; ?></td>
                        <td align="center"><?php echo $row["member_mobile_nation"]."+".$row["member_mobile"]; ?></td>
                        <td align="left"></td>
                        <?php /*?><td align="center" ><?php echo $ary_yn[$row["isshow"]]; ?></td><?php */?>
                        <td align="center" ><?php echo $row["member_update_time"]; ?></td>
                        <td align="center" ><?php echo $row["member_create_time"]; ?></td>
                        <?php /*?><td align="center"><a href="?remove=up&nid=<?php echo $row["member_id"]."&".$query_str; ?>">上移</a></td>
                        <td align="center"><a href="?remove=down&nid=<?php echo $row["member_id"]."&".$query_str; ?>">下移</a></td><?php */?>
                        <td align="center"><a href="edit.php?id=<?php echo $row["member_id"]."&".$query_str; ?>">修改</a></td>
                        <td align="center" ><a href="index.php?isdel=y&did=<?php echo $row["member_id"]."&".$query_str; ?>" onClick="return confirm('您確定要刪除這筆記錄?')">刪除</a></td>
                    </tr>
                    <?php
					}
				$db -> close();
				?>
                </tbody>
                <tfoot>
                    <tr>
                        <th height="30" colspan="10" align="right"  class="tfoot" scope="col"><?=showpage()?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</body>
</html>