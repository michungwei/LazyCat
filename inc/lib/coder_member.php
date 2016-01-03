<?php
	
class CoderMemberItem{
	public $account = "";
	public $password = "";
	public $name = "";
	public $email = "";
	public $sex = "";
	public $birthday = "";
	public $mobile = "";
	public $mobile_national_number = "";
	public $address = "";
	public $create_time = "";
	public $update_time = "";
}


class CoderMember{
	static $table = "lc_member";
	static $table_dis = "lc_discharge";

	public function __construct(){
	}
	//會員新增
	public function Insert($member){ 
		global $db, $memberid;
		if($this -> isExisit($member -> account)){
			throw new Exception('會員帳號重覆,請重新輸入');
		}else{
			$data["member_account"] = $member -> account;
			$data["member_password"]=md5($member -> password);
			$data["member_name"] = $member -> name;
			$data["member_email"] = $member -> email;
			$data["member_sex"] = $member -> sex;
			$data["member_birthday"] = $member -> birthday;
			$data["member_address"] = $member -> address;
			$data["member_mobile_nation"] = $member -> mobile_national_number;
			$data["member_mobile"] = $member -> mobile;
			$data["member_create_time"] = request_cd();
			$data["member_update_time"] = request_cd();
			
			$memberid = $db -> query_insert(CoderMember::$table, $data);
		}
	}
	//會員修改
	public function Update(CoderMemberItem $member){ 
		global $db;
		if(!isset($_SESSION["session_acc"]) || !isset($_SESSION["session_name"]) || !isset($_SESSION["session_id"])){
			throw new Exception('您已經登出系統，請重新登入');
		}else{
			$data["member_name"] = $member -> name;
			$data["member_email"] = $member -> email;
			$data["member_birthday"] = $member -> birthday;
			$data["member_address"] = $member -> address;
			$data["member_mobile_nation"] = $member -> mobile_national_number;
			$data["member_mobile"] = $member -> mobile;
			$data["member_update_time"] = request_cd();
			$db -> query_update(CoderMember::$table, $data, "member_id = ".$_SESSION["session_id"]);
		}
	}
	//會員修改密碼
	public function changePassword($mid, $old_password, $password){ 
		global $db;
		if(!isset($_SESSION["session_acc"]) || !isset($_SESSION["session_name"]) || !isset($_SESSION["session_id"])){
			throw new Exception('您已經登出系統，請重新登入');
		}else{
			$data["member_password"] = md5($password);
			$data["member_update_time"] = request_cd();
			$db -> query_update(CoderMember::$table, $data, "member_id = '$mid'");
		}
	}
	//檢查帳號是否存在
	static function isExisit($acc){
		global $db;
		if($db -> query_first("SELECT member_id FROM ".CoderMember::$table." WHERE member_account = '$acc'")){
			return true;
		}else{
			return false;
		}
	}
	//檢查帳號是否重覆
	public function chkAcc(CoderMemberItem $member){
		global $db;
		if($this -> isExisit($member -> acc)){
			throw new Exception('會員帳號重覆,請重新輸入');
		}
	}
	//檢查email是否存在
	static function isExisit_email($email){
		global $db;
		$sql_str = "";
		
		if(isLogin()){
			$member_id = $_SESSION["session_id"];
			$sql_str = " AND member_id <> '$member_id'";
		}
		if($db -> query_first("SELECT member_id FROM ".CoderMember::$table." WHERE member_email = '$email'".$sql_str)){
			return true;
		}else{
			return false;
		}
	}
	//檢查email是否重覆
	public function chkEmail(CoderMemberItem $member){
		global $db;
		if($this -> isExisit_email($member -> email)){
			throw new Exception('會員email重覆,請重新輸入');
		}
	}
	//會員登入
	public function MemberLogin(CoderMemberItem $member){ 
		global $db;
		$sql = "SELECT * FROM ".CoderMember::$table." WHERE member_account = '".$member -> acc."' AND member_password = '".md5($member -> pwd)."'";
		$row = $db -> query_first($sql);
		if(!$row){
			throw new Exception('登入失敗,帳號或密碼不正確');
		}
		else{
			$_SESSION["session_acc"] = $row["member_account"];
	 		$_SESSION["session_name"] = $row["member_name"];
			$_SESSION["session_id"] = $row["member_id"];
		}
	}
	

	
	//設定Cookie
	public function setCookie(CoderMemberItem $member){
		global $db;
		return $db -> query_first("SELECT * FROM ".CoderMember::$table." WHERE member_id = '".$member -> id."'");
	}
	
	//列出會員資料
	public static function getList($member_id){
		global $db;
		return $db -> query_first("SELECT * FROM ".CoderMember::$table." WHERE member_id = '$member_id'");
	}

	//取得抵用金名稱
	public static function getDischarge($discharge_id){
		global $db;
		return $db -> query_first("SELECT * FROM ".CoderMember::$table_dis." WHERE discharge_id = '$discharge_id' AND discharge_enable = 1 AND ((discharge_start_time <= NOW() AND discharge_end_time >= NOW()) OR discharge_forever = 1)");
	}
	//取得抵用金(列表用)
	public static function getDischargeList($discharge_id){
		global $db;
		return $db -> query_first("SELECT * FROM ".CoderMember::$table_dis." WHERE discharge_id = '$discharge_id' AND discharge_enable = 1");	
	}

	//扣除抵用金
	public static function resetDischarge($mid, $dischargeStr){
		global $db;
		$data["member_discharge_amount"] = $dischargeStr;
		$db -> query_update(CoderMember::$table, $data, "member_id = ".$mid);
	}

	
	public function chkCode(coderMemberObj $member){
		if($this->isChkCode($member->code)){
			throw new Exception('驗證碼錯誤');
		}

	}
	public function getMaxInd($table,$field,$where){
		global $db;
		$row=$db->query_first("select max($field) as max from $table $where","max");
		$maxind=intval($row["max"]);
		
		if ($maxind==0){
			$maxind=1;
		}
		else{
			$maxind+=5;
		}
		return $maxind;
	}
	
	
	public static function isChkCode($code){
		if(strtolower($code)!=strtolower($_SESSION['Xrandcode'])){
			return true;
		}else{
			return false;
		}
	}
	
}

