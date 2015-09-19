<?php 
include_once("_config.php");
include_once($inc_path."_getpage.php");

$product_id = get("product_id", 1);

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();
//刪除
if(get("isdel", 1) == 'y'){
$did = get("did");
  	$db -> query("DELETE FROM $table_storelog WHERE storelog_id = '$did'");
	script("刪除成功", "index.php");
}


$sql = "SELECT *
		FROM $table_storelog
		WHERE storelog_product_id = '$product_id'
		ORDER BY storelog_create_time DESC";

getSql($sql, 20, $query_str);

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
    <div id="adminlist">
      <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle; ?> >&nbsp;&nbsp;列表</h2>
        <br>
        <div class="accordion">
            <table width="100%" cellspacing="0" class="list-table">
                <thead>
                    <tr>
                        <th width="60" align="center">ID</th>
                        <th width="150" align="center">帳號</th>
                        <th align="left">說明</th>
                        <th width="120" align="center" >修改時間</th>
                    </tr>
                </thead>
                <tbody id="the-list" class="list:cat">
                    <?php
						$rows = $db -> fetch_all_array($sql);
						foreach($rows as $row){
					?>
                    <tr>
                        <th align="center"><?php echo $row["storelog_id"]; ?></th>
                        <td align="center" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["storelog_acc"]; ?></td>
                        <td align="left" style="word-wrap: break-word; word-break: break-all;"><?php echo $row["storelog_comment"]; ?></td>
                        <td align="center" ><?php echo $row["storelog_create_time"]; ?></td>
                    </tr>
                    <?php
						}
						$db -> close();
					?>
                </tbody>
                <tfoot>
                    <tr>
                        <th height="30" colspan="4" align="right"  class="tfoot" scope="col"><?=showPage()?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</body>
</html>