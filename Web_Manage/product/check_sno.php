<?php
include("_config.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$sno = post("sno",1);
$result = 0;
if($sno != ""){
	$sql = "select product_id from $table_product where product_sno ='$sno'";
	$row = $db->query_first($sql);
	
	if($row){
		$result = 0;
	}else{
		$result = 1;
	}
	
	$db->close();
}
echo $result;
?>