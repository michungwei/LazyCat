<?php
include_once("_config.php");

$type = post("type", 1);
$action = post("action", 1);
$list = "";
$result = false;

if($type != ""){
	
	$db = new Database($HS, $ID, $PW, $DB);
	$db -> connect();
	
	$sql_type = "SELECT *
				 FROM $table_productserial 
				 WHERE productserial_type_id = '$type' AND productserial_is_show = 1
				 ORDER BY productserial_ind DESC";
	$rows_serial = $db -> fetch_all_array($sql_type);
	
	//$list .= '<select name="serial" id="serial">';
	
		if($action == "add"){
			foreach($rows_serial as $row_serial){
				$list .= '<option value="'.$row_serial["productserial_id"].'">'.$row_serial["productserial_name"].'</option>';
			}
		}else{
			$list .= '<option value="" '.($serial == "" ? "selected" : "").'>不限</option>';
			foreach($rows_serial as $row_serial){
				$list .= '<option value="'.$row_serial["productserial_id"].'"'.($serial == $row_serial["productserial_id"] ? "selected" : "").'>'.$row_serial["productserial_name"].'</option>';
			}
		}
	
	//$list .= '</select>';
	
	$result = true;
	
	$db -> close();
}else{
	
	$list .= "傳輸錯誤!再試一次!";	
}

$re["result"] = $result;
$re["list"] = $list;

echo json_encode($re);