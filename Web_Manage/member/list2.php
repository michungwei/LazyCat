<?php 
include("_config.php");
include($inc_path."_getpage.php");

$db = new Database($HS, $ID, $PW, $DB);
$db->connect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/admin_style_gray.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../scripts/jquery-1.6.1rc1.min.js"></script>
<script type="text/javascript">
function SetFrameHeight(){
	parent.document.getElementById('ifrmmember').height = (document.body.scrollHeight+10);
}

$(document).ready(function(){
	
	$(".accordion .tableheader:first").addClass("active");
	
	$(".accordion .tableheader").toggle(function(){
		$(this).next().slideDown("fast");
	},function(){
	  $(this).next().slideUp("fast");
	  $(this).siblings("tableheader").removeClass("active");
	});

});
window.onload=function(){
	SetFrameHeight();
};
</script>
</head>

<body>
<div id="mgbody-content">
  <div id="adminlist">
    <div class="accordion">
      <table width="100%" cellspacing="0" class="list-table">
        <thead>
          <tr>
            <th width="50" align="center" >ID</th>
            <th width="100" align="center" >姓名</th>
            <th width="50" align="center" >性別</th>
<!--            <th width="100" align="center" >生日</th>
-->            <th width="100" align="center" >電話</th>
            <th width="100" align="center" >手機</th>
            <th width="200" align="left" >地址</th>
            <th width="180" align="left" >Email</th>
            <th align="left" >FB_ID</th>
            <th width="50" align="center" >狀態</th>
            <th width="50" align="center" >顯示</th>
            <th width="120" align="center" >最後修改時間</th>
            <th width="60" height="28" align="center">明細/修改</th>
          </tr>
        </thead>
        <tbody id="the-list" class="list:cat">
					<?php
						$sql="select * from  $table_menber where status=0 order by id desc";
						getsql($sql,10,$querystr);
						$rows = $db->fetch_all_array($sql);
						foreach($rows as $row){
					?>
          <tr>
            <th align="center"><?php echo $row["id"] ?></th>
            <td align="center"><?php echo $row["name"]?></td>
            <td align="center"><?php echo $arySex[$row["sex"]]; ?></td>
<?php /*?>            <td align="center"><?php echo $row["birthday"]; ?></td>
<?php */?>            <td align="center"><?php echo $row["tel"]?></td>
            <td align="center"><?php echo $row["mobile"]?></td>
            <td align="left"><?php echo $row["address_zcode"].$row["address_city"].$row["address_area"].$row["address_location"]?></td>
            <td align="left"><?php echo $row["email"]?></td>
            <td align="left"><?php echo $row["fb"]?></td>
            <td align="center"><?php echo $aryMemberStatus[$row["status"]] ?></td>
            <td align="center" ><?php echo $aryYN[$row["isshow"]]?></td>
            <td align="center" ><?php echo $row["createtime"]?></td>
            <td align="center"><a href="edit.php?id=<?php echo $row["id"]."&".$querystr ?>" target="_blank">明細/修改</a></td>
          </tr>
          <?php
						}
						$db->close();
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