<?php
include_once("_config.php");
include_once($inc_path."lib/coder_member.php");
include_once($inc_path."_func_smtp.php");
include_once($inc_path.'lib/_shoppingcar.php');

$actiontype = post("actiontype", 1);
$sResult = false;
$sMsg = "";

$memberid = 0;

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

	
//會員新增 	
if($actiontype == "insert"){	

	$account = post("account", 1);
	$password = post("password", 1); 
	$name = post("name", 1);
	$email = post("email", 1);
	$birthday = post("birthday", 1);
	$mobile = post("mobile", 1);
	$mobile_national_number = post("mobile_national_number", 1);
	$address = post("address", 1);
	
	$sResult = isNull($account, "帳號", 1, 15);
	if($sResult){$sResult = isNull($password, "密碼", 6, 35);}
	if($sResult){$sResult = isNull($name, "姓名", 2, 30);}
	if($sResult){$sResult = isEmail2($email, "電子郵件");}
	if($sResult){$sResult = isNull($birthday, "生日", 1, 15);}
	if($sResult){$sResult = isNull($mobile, "手機", 1, 20);}
	//if($sResult){$sResult = isNull($address, "地址", 1, 255);}
	
	if($sResult){	
		try{
			$myMember = new CoderMember();
			$data = new CoderMemberItem();
			$data -> account = $account;
			$data -> password = $password;
			$data -> name = $name;
			$data -> email = $email;
			//$data -> gender = $gender; 
			$data -> birthday = $birthday;
			$data -> mobile = $mobile;
			$data -> mobile_national_number = $mobile_national_number;
			$data -> address = $address;
			
			$memberid = 0;
			$myMember -> Insert($data);
			
			//通知信
			/*if($memberid > 0){
			
				$fr_em = $sys_email;
				$fr_na = $sys_name;
				//$to_em = $email;
				$to_em = "bill@coder.com.tw";
				$to_na = $uname;
				$subject = "帳號開通通知";
				$msg = "";
				$msg .= "親愛的會員".$name."您好:<br />";
				$msg .= "歡迎您加入lazycat,如有任何問題請聯絡客服人員!";
				
				sendMail($fr_em, $fr_na, $to_em, $to_na, $subject, $msg);
				
			}*/
			
			$sResult = 1;
			$sMsg = 'OK';
			//if(isset($_SESSION["session_uname"])) unset($_SESSION["session_uname"]);
			//if(isset($_SESSION["session_uemail"])) unset($_SESSION["session_uemail"]);
			//if(isset($_SESSION["session_uid"])) unset($_SESSION["session_uid"]);
			//session_unset();
			//session_destroy();
		}catch(Exception $e){
			$sResult = 0;
			$sMsg = $e -> getMessage();
		}
	}else{
		$sMsg = $str_message;
	}

	$re["result"] = $sResult==1 ? true : false;
	$re["memberid"] = $memberid;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}
//會員修改
if($actiontype == "update"){
	//$member_id = post("member_id", 1);
	$name = post("name", 1);
	$email = post("email", 1);
	$birthday = post("birthday", 1);
	$mobile_national_number = post("mobile_national_number", 1);
	$mobile = post("mobile", 1);
	$address = post("address", 1);
	
	$sResult = isNull($name, "姓名", 1, 30);
	if($sResult){$sResult = isEmail2($email, "電子郵件");}
	if($sResult){$sResult = isNull($birthday, "生日", 1, 15);}
	if($sResult){$sResult = isNull($mobile, "手機", 1, 20);}
	if($sResult){$sResult = isNull($address, "地址", 1, 255);}
	if($sResult){	
		try{
			$myMember = new CoderMember();
			$data = new CoderMemberItem();
			
			$data -> name = $name;
			$data -> email = $email;
			$data -> birthday = $birthday;
			$data -> mobile = $mobile;
			$data -> mobile_national_number = $mobile_national_number;
			$data -> address = $address;
			
			$myMember -> Update($data);
			$sResult = 1;
			$sMsg = 'OK';
		}catch(Exception $e){
			$sResult = false;
			$sMsg = $e -> getMessage();
		}
	}else{
		$sMsg = $str_message;
	}
	$re["result"] = $sResult==1 ? true : false;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}
//密碼修改
if($actiontype == "changePassword"){
	if(isLogin()){
		$mid = $_SESSION["session_id"];
		$old_password = post('oldpassword', 1);
		$password = post('password', 1);
		
		$sResult = isNull($old_password, "舊密碼", 6, 35);
		if($sResult){$sResult = isNull($password, "新密碼", 6, 35);}
		
		if($sResult){
			$old_password2 = md5($old_password);
			//echo('mid:'.$mid.'/old:'.$old_password.'/old2:'.$old_password2.'/new:'.$password);
			$row = $db -> query_first("SELECT * FROM $table_member WHERE member_id = '$mid' AND member_password = '$old_password2'");
			//print_r($row);
			if($row){	
				try{
					$myMember = new CoderMember();
							
					$myMember -> changePassword($mid, $old_password, $password);
					$sResult = 1;
					$sMsg = 'OK';
				}catch(Exception $e){
					$sResult = 0;
					$sMsg = $e -> getMessage();
				}
			}else{
				$sResult = 0;
				$sMsg = '舊密碼輸入錯誤';
			}
		}else{
			$sResult = 0;
			$sMsg = $str_message;
		}
		
	}else{
		$sMsg ="您已經登出系統，請重新登入!";
	}
	
	$re["result"] = $sResult == 1 ? true : false;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}
//忘記密碼
if($actiontype == "passwordMail"){

		$email = post('email', 1);
		
		$sResult = isNull($email, "email", 1, 50);
		
		if($sResult){

			$row = $db -> query_first("SELECT * FROM $table_member WHERE member_email = '$email'");
			//print_r($row);
			if($row){	
				try{
					$newpwd = generatorPassword();
					$member_id = $row["member_id"];
					$member_name = $row["member_name"];
					$member_email = $row["member_email"];
					$subject = "新密碼";
					$content = "您的新密碼為:".$newpwd.",登入後,請至會員中心設定您慣用的密碼!";
					
					$md5_newpwd = md5($newpwd);
					
					//echo(md5($newpwd));
		
					//$db -> query_update($table_member, $data, "member_id='$member_id'");
					$db -> query("update $table_member set member_password = '$md5_newpwd' where member_id = '$member_id'");
					
					$fr_em = $sys_email;
					$fr_na = $sys_name;
					$to_em = $member_email;
					$to_na = $member_name;
					$subject = $subject;
					$msg = $content;
					
					sendMail($fr_em, $fr_na, $to_em, $to_na, $subject, $msg);
					
					$sResult = 1;
					$sMsg = '新密碼已寄出,請去您的信箱接收!';
					
				}catch(Exception $e){
					$sResult = 0;
					$sMsg = $e -> getMessage();
				}
			}else{
				$sResult = 0;
				$sMsg = '查無此email,請再輸入一次!';
			}
		}else{
			$sResult = 0;
			$sMsg = $str_message;
		}
	
	$re["result"] = $sResult == 1 ? true : false;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}
//檢查帳號是否重覆
if($actiontype == "chkAcc"){ 
	try{
		$myMember = new CoderMember();
		$data = new CoderMemberItem();
		
		$data -> acc = post("acc", 1);

		$myMember -> chkAcc($data);
		$sResult = 1;
		$sMsg = 'OK';
	}catch(Exception $e){
		$sMsg = $e -> getMessage();
	}
	$re["result"] = ($sResult == 1) ? true : false;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}
//檢查email是否重覆
if($actiontype == "chkEmail"){ 
	try{
		$myMember = new CoderMember();
		$data = new CoderMemberItem();
		
		$data -> email = post("email", 1);

		$myMember -> chkEmail($data);
		$sResult = 1;
		$sMsg = 'OK';
	}catch(Exception $e){
		$sMsg = $e -> getMessage();
	}
	$re["result"] = ($sResult == 1) ? true : false;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}
//會員登入
if($actiontype == "MemberLogin"){
	$member_name = "";
	$member_id = "";
	$acc = post("acc", 1);
	$pwd = post("pwd", 1);
	//$uid=isset($_SESSION["session_uid"]) ? $_SESSION["session_uid"] : '';
	$sResult = isNull($acc, "帳號", 1, 30);
	if($sResult){$sResult = isNull($pwd, "密碼", 1, 35);}
	//if ($sResult){$sResult=isNull($uid,"FBID", 1, 30);}
	if($sResult){	
		try{
			$myMember = new CoderMember();
			$data = new CoderMemberItem();
			$data -> acc = $acc;
			$data -> pwd = $pwd;
			//$data->uid=$uid;
			$myMember -> MemberLogin($data);
			$member_name = $_SESSION["session_name"];
			$member_id = $_SESSION["session_id"];
			$sResult = 1;
			$sMsg = 'OK';
		}catch(Exception $e){
			$sResult = 0;
			$sMsg = $e -> getMessage();
		}
	}else{
		$sMsg = $str_message;
	}
	$re["result"] = ($sResult == 1) ? true : false;
	$re["msg"] = $sMsg;
	$re["member_name"] = $member_name;
	$re["member_id"] = $member_id;
	echo json_encode($re);
}
//會員登出
if($actiontype == "MemberLogout"){
	unset($_SESSION["session_acc"]);
	unset($_SESSION["session_name"]);
	unset($_SESSION["session_id"]);
	//session_unset();
	//session_destroy();
	
	$car = new shoppingCar();
	$car -> clear();
	
	$sResult = 1;
	$sMsg = 'OK';
	$re["result"] = $sResult==1 ? true : false;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}

if($actiontype == "chkToken"){ //FB登入取資料
	require("../inc/facebook.php");
	$sMsg = "系統發生錯誤!請聯絡系統管理員";
	$name = "";
	$email = "";
	$mobile = "";
	$member_id = "";
	$isMember = false;
	$success = false;//驗證是否有過
	$token = post("accesstoken", 1);
	//$isBonus = 0;
	
	$facebook = new Facebook(array(
	  'appId'  => $fb_appid,
	  'secret' => $fb_appsecrect,
	));
	
	try {
		$user_profile = $facebook -> api('/me', array('access_token' => $token));
		$sResult = 1;
	} catch (FacebookApiException $e) {
		$e = $e -> getResult();
		$sMsg = $e["error"]["message"];
	}
	
	if($sResult){
		//print_r($user_profile);
		$uid = $user_profile["id"];
		$uemail = isset($user_profile["email"]) ? $user_profile["email"] : "";
		$ubirthday = isset($user_profile["birthday"]) ? $user_profile["birthday"] : "";
		$ugender = isset($user_profile["gender"]) ? $user_profile["gender"] : "";
		$ulocale = isset($user_profile["locale"]) ? $user_profile["locale"] : "";
		$uname = $user_profile["name"];
		$sResult = isNull($uid, "FBID", 1, 30);
		//if ($sResult){$sResult = isEmail2($uemail, "FB Email");}
		if ($sResult){
			$row = getMemberByUID($uid);//DB有否FB_id 
			if(!$row){
				$row = getMemberByUEmail($uemail);//已註冊過 email跟FBmail依樣
				if(!$row){
					$sResult = 0;
					$sMsg = "會員不存在";
					$_SESSION["session_uname"] = $uname;
					$_SESSION["session_uemail"] = $uemail;
					$_SESSION["session_ubirthday"] = $ubirthday;
					$_SESSION["session_ugender"] = $ugender;
					$_SESSION["session_ulocale"] = $ulocale;
					$isMember = false;
				}else{
					$db -> query("update $table_member set member_fb_id = '$uid' where member_id = ".$row["member_id"]);
					$sResult = 1;
					$sMsg = "您的會員帳號已存在";
					$isMember = true;
				}		
				$success = true;						
			}else{
				$sMsg = "會員已存在";
				$isMember = true;
				$success = true;
				$sResult = 1;

			}
			if($sResult==1){
				//$row = getMemberByUID($uid);
				if($row){
					$name = $row["member_name"];
					$email = $row["member_account"];
					$member_id = $row["member_id"];
					$_SESSION["session_acc"] = $email;
					$_SESSION["session_name"] = $name;
					$_SESSION["session_id"] = $member_id;
				}else{
					$sResult = 0;
					$sMsg = "資料傳輸錯誤!";
				}
			}
			$_SESSION["session_uid"] = $uid;
		}else{
			$sMsg = $str_message;
		}
	}

	$re["result"] = $sResult==1 ? true : false;
	$re["isMember"] = $isMember;
	$re["member_id"] = $member_id != "" ? $member_id : "";
	$re["success"] = $success;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}

//***lazycat used }

//設定cookie
if($actiontype == "setCookie"){ //檢查驗證碼
	try{
		$myMember = new coderMember();
		$data = new CoderMemberItem();
		$data -> id = post("m_id", 1);
		$myMember -> setCookie($data);
		$sResult = 1;
		$sMsg= 'OK';
	}
	catch(Exception $e){
		$sMsg= $e->getMessage();
	}
	$re["result"] = $sResult==1 ? true : false;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}


//列出會員資料
if($actiontype == "getlist"){
	$id = post("id");
	if($id > 0){
		$myMember = new CoderMember();
		$data = new CoderMemberItem();
		$data -> id = $id;
		$list = $myMember -> getList($data);
		$sResult = 1;
		$sMsg = 'OK';
	}else{
		$sMsg = '資料傳輸錯誤!';	
	}
	$re["result"] = $sResult==1 ? true : false;
	$re["list"] = $list;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}



if($actiontype == "chkCode"){ //檢查驗證碼
	try{
		$myMember=new coderMember();
		$data=new CoderMemberItem();
		$data->code=post("code",1);
		$myMember->chkCode($data);
		$sResult=true;
		$sMsg= 'OK';
	}
	catch(Exception $e){
		$sMsg= $e->getMessage();
	}
	$re["result"] = $sResult==1 ? true : false;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}




if($actiontype=="UnsetFB"){ //清除FB SESSION
	if(isset($_SESSION["session_uname"])) unset($_SESSION["session_uname"]);
	if(isset($_SESSION["session_uemail"])) unset($_SESSION["session_uemail"]);
	if(isset($_SESSION["session_uid"])) unset($_SESSION["session_uid"]);
	$sResult=1;
	$sMsg= 'OK';
	$re["result"] = $sResult==1 ? true : false;
	$re["msg"] = $sMsg;
	echo json_encode($re);
}
$db->close();

?>