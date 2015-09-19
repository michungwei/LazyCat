<?php
include_once("_config.php");
ob_start(); 

$db = new Database($HS, $ID, $PW, $DB);
$db->connect();

$sql_str="";

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
		ORDER BY $ind_column DESC";
$rows = $db -> fetch_all_array($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>excel</title>
</head>
<body>
<table width="100%" border="1" cellspacing="0" class="list-table">
    <thead>
        <tr>
            <th width="50" align="center" >ID</th>
            <th width="100" align="center" >帳號</th>
            <th width="100" align="center" >姓名</th>
            <th width="100" align="center" >手機</th>
            <th width="150" align="center" >Email</th>
            <th align="left" >地址</th>
            <th width="120" align="center" >最後修改時間</th>
        </tr>
    </thead>
    <tbody id="the-list" class="list:cat">
        <?php
			foreach($rows as $row){
		?>
        <tr>
            <th align="center"><?php echo $row["member_id"]; ?></th>
            <td align="center"><?php echo $row["member_account"]; ?></td>
            <td align="center"><?php echo $row["member_name"]; ?></td>
            <td align="center"><?php echo $row["member_mobile"]; ?></td>
            <td align="center"><?php echo $row["member_email"]; ?></td>
            <td align="left"><?php echo $row["member_address_city"].$row["member_address_area"].$row["member_address_location"]; ?></td>
            <td align="center" ><?php echo $row["member_createtime"]; ?></td>
        </tr>
        <?php
			}
			$db -> close();
		?>
    </tbody>
    <tfoot>
    </tfoot>
</table>
</body>
</html>
<?php 
$outStr=ob_get_contents(); 
ob_end_clean(); 
	
header("Content-type:application/vnd.ms-excel");
Header("Accept-Ranges: bytes"); 
Header("Accept-Length: ".strlen($outStr)); 
		
Header("Content-Disposition: attachment; filename=menber.xls"); 
// 輸出文件內容          
echo $outStr;
?>
