<?php
include_once("_config.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$acc = post("acc", 1);
$result = 0;
if($acc != ""){
	$sql = "SELECT member_id 
			FROM $table_member 
			WHERE member_account = '$acc'";
	$row = $db -> query_first($sql);
	
	if($row){
		$result = 0;
	}else{
		$result = 1;
	}
	
	$db -> close();
}
echo $result;


/*End PHP*/