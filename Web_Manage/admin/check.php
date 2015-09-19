<?php
include_once("_config.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$id = post("id");
$wherestr = "";
if($id > 0){
	$wherestr = "AND $id_column <> '$id'";
}
$account = post("name", 1);
$result = 0;
if($account != ""){
	$sql = "SELECT $id_column 
			FROM $table_admin 
			WHERE $check_field = '$account' $wherestr";
	$row = $db -> query_first($sql);
	
	if($row){
		$result = 0;
	}else{
		$result = 1;
	}
}
$db -> close();
echo $result;
/*End PHP*/