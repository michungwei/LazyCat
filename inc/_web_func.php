<?php
function escapeJsonString($string) { 
    $replacements = array(
        '@[\\\\"]@'        => '',                        
        '@\n@'             => '',                            
        '@\r@'             => '',                            
        '@\t@'             => '',                           
        '@[[:cntrl:]]@e'   => ''                        
    );
    $result = preg_replace(array_keys($replacements), array_values($replacements), $string);
 
    return $result;
}

function isLogin(){
	return (isset($_SESSION["session_acc"]) && trim($_SESSION["session_acc"]) != "" && isset($_SESSION["session_name"]) && trim($_SESSION["session_name"]) != "" && isset($_SESSION["session_id"]) && trim($_SESSION["session_id"] != ""));
}

function chkCookie(){
	global $db, $table_member;
	$result = false;
	$m_id = getCookie("m_id");
	
	if($m_id != ""){
		$sql = "SELECT * FROM $table_member WHERE member_token = '$m_id'";
		$row = $db -> query_first($sql);
		if($row){
			$_SESSION["session_acc"] = $row["member_account"];
			$_SESSION["session_name"] = $row["member_name"];
			$_SESSION["session_id"] = $row["member_id"];
			$result = true;
		}else{
			$result = false;	
		}
	}else{
		$result = false;
	}
	return $result;
}

function getMaxInd_web($table, $field, $where){
	global $db;
	$row = $db -> query_first("SELECT max($field) AS max FROM $table $where","max");
	$maxind = intval($row["max"]);
	if($maxind == 0){
		$maxind = 1;
	}else{
		$maxind += 5;
	}
	return $maxind;
}

/*function getMemberByUEmail($email){
	global $db, $table_member;
	return $db -> query_first("SELECT * FROM $table_member WHERE member_account = '$email' ORDER BY member_id DESC");
}*/


function generatorPassword(){
    $password_len = 8;
    $password = '';

    $word = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
    $len = strlen($word);

    for ($i = 0; $i < $password_len; $i++){
        $password .= $word[rand() % $len];
    }
    return $password;
}

/*End PHP*/