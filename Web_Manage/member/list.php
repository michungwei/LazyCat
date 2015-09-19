<?php 
include_once("_config.php");
include_once($inc_path."_getpage.php");

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
function selectmenber(mid,mname,memail,mcell,mtel,maddress){
	parent.getmenber(mid,mname,memail,mcell,mtel,maddress);
	parent.$.fancybox.close();
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
</script>
</head>

<body>
<div id="mgbody-content">
  <div id="panel"> <br />
  </div>
  <div id="adminlist">
    <h2> <img src="../images/admintitle.png" />會員選擇列表</h2>
    <br>
    <form method="get" action="list.php">
      <select name="type" id="type">
        <option value="">-- 請選擇 --</option>
        <option value="1" <?php echo ($type=="1") ? "selected" : ""?>>姓名</option>
        <option value="2" <?php echo ($type=="2") ? "selected" : ""?>>電話</option>
        <option value="3" <?php echo ($type=="3") ? "selected" : ""?>>手機</option>
        <option value="4" <?php echo ($type=="4") ? "selected" : ""?>>Email</option>
      </select>
      關鍵字:
      <input name="keyword" type="text" size="20" value="<?php echo $keyword?>">
      <input name="" type="submit" value="搜尋" class="searchbtu" />
    </form>
    <div class="accordion">
      <table width="100%" cellspacing="0" class="list-table">
        <thead>
          <tr>
            <th width="50" align="center" >ID</th>
            <th width="150" align="center" >帳號</th>
            <th width="100" align="center" >姓名</th>
            <th width="50" align="center" >性別</th>
<!--            <th width="100" align="center" >生日</th>
-->            <th width="150" align="center" >Email</th>
            <th width="100" align="center" >電話</th>
            <th width="100" align="center" >手機</th>
            <th  width="200" align="left" >地址</th>
            <th width="50" align="center" >狀態</th>
            <th  align="center" >選擇會員</th>
          </tr>
        </thead>
        <tbody id="the-list" class="list:cat">
          <?php
						$sqlstr="";

						if ($type!="" && $keyword!=""){
							switch($type){
								case 1:
									$sqlstr.=" and $table_menber.name like '%$keyword%' ";
									break;
								case 2:
									$sqlstr.=" and $table_menber.tel like '%$keyword%' ";
									break;
								case 3:
									$sqlstr.=" and $table_menber.mobile like '%$keyword%' ";
									break;
								case 4:
									$sqlstr.=" and $table_menber.email like '%$keyword%' ";
									break;
								default:
									break;
							}	
						}
						if ($isshow!=""){
							$sqlstr.=" and isshow=$isshow";
						}
						$sql="select * from  $table_menber where 1 $sqlstr order by id desc";
						getsql($sql,15,$querystr);
						$rows = $db->fetch_all_array($sql);
						foreach($rows as $row){
					?>
          <tr>
            <th align="center"><?php echo $row["id"] ?></th>
            <td align="center"><?php echo $row["acc"]?></td>
            <td align="center"><?php echo $row["name"]?></td>
            <td align="center"><?php echo $arySex[$row["sex"]]; ?></td>
<!--            <td align="center"><?php echo $row["birthday"]; ?></td>
-->            <td align="center"><?php echo $row["email"]?></td>
            <td align="center"><?php echo $row["tel"]?></td>
            <td align="center"><?php echo $row["mobile"]?></td>
            <td align="left"><?php echo $row["address_zcode"].$row["address_city"].$row["address_area"].$row["address_location"]?></td>
            <td align="center"><?php echo $aryMemberStatus[$row["status"]] ?></td>
            <th  align="center" ><a href="#" onclick="selectmenber('<?php echo $row["id"] ?>','<?php echo $row["name"]?>','<?php echo $row["acc"] ?>','<?php echo $row["mobile"]?>','<?php echo $row["tel"]?>','<?php echo $row["address_city"].$row["address_area"].$row["address_location"]?>')">確定</a></th>
          </tr>
          <?php
				}
				$db->close();
			?>
        </tbody>
        <tfoot>
          <tr>
            <th height="30" colspan="11" align="right"  class="tfoot" scope="col"><?=showpage()?></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
</body>
</html>