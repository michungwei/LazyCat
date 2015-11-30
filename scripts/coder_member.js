//***ming_mem used {
	
/*function coderMemberItem(){
}
coderMemberItem.prototype = {
	id : '',
	acc : '',
	pwd : '',
	fb_id : '',
	username : '',
	nikename : '',
	gender : '',
	birthday : '',
	nationality : '',
	phone : '',
	create_time : '',
	accesstoken : ''
}*/


function coderMember(){
}
coderMember.prototype = {
	message : '',
	member_id : 0,
	member_name : '',
	//會員新增 
	insert : function(member, type){ 
		var parent = this;
		member.actiontype = type;
		success = false;
		$.ajaxSetup({ cache: false });
		$.ajax({
			url : "do/memberdo.php",
			async : false,
			type : "POST",
			data: { 
					account : member.account, 
					password : member.password, 
					name : member.name, 
					email : member.email, 
					birthday : member.birthday, 
					address : member.address, 
					mobile : member.mobile,
					mobile_national_number : member.mobile_national_number,
					checkCode: member.checkCode,   
					actiontype : "insert" 
			},
			dataType : "json",
			success : function(data){
				if(data){
					if(data.result == true){
						success = true;
						parent.memberid = data.memberid;
						//parent.acc = data.acc;
						//parent.pwd = data.pwd;
						//alert("hi");
					}else{
						parent.message = data.msg;
					}
				}else{
					parent.message = "回傳資料錯誤!";
				}
			},
			error : function(xhr, ajaxOptions, thrownError){
				parent.message = "讀取資料時發生錯誤,請梢候再試!"+thrownError;
			}
		});
		return success;
	},
	//檢查帳號是否重覆
	chkAcc : function(value){ 
	//console.log(value);
		var parent = this,
		success = false;  
		$.ajax({
			url : "do/memberdo.php",
			async : false,
			type : "POST",
			data : { acc : value, actiontype : "chkAcc" },
			dataType : "json",
			success : function(data){
				if(data){
					if(data.result == true){
						success = true;
					}else{
						parent.message = data.msg;
					}
				}else{
					parent.message = "回傳資料錯誤";
				}
			},
			error:function(xhr, ajaxOptions, thrownError){
				parent.message = "讀取資料時發生錯誤,請梢候再試"+thrownError;
			}
		});	
		return success;	 
	},
	//檢查email是否重覆
	chkEmail : function(value){ 
	//console.log(value);
		var parent = this,
		success = false;  
		$.ajax({
			url : "do/memberdo.php",
			async : false,
			type : "POST",
			data : { email : value, actiontype : "chkEmail" },
			dataType : "json",
			success : function(data){
				if(data){
					if(data.result == true){
						success = true;
					}else{
						parent.message = data.msg;
					}
				}else{
					parent.message = "回傳資料錯誤";
				}
			},
			error:function(xhr, ajaxOptions, thrownError){
				parent.message = "讀取資料時發生錯誤,請梢候再試"+thrownError;
			}
		});	
		return success;	 
	},
	//會員登入
	MemberLogin : function(member){ 
		var parent = this,
		success = false;
		$.ajax({
			url : "do/memberdo.php",
			async : false,
			type : "POST",
			data : { 
					acc : member.account, 
					pwd : member.password, 
					checkCode: member.checkCode,
					actiontype : "MemberLogin" 
			},
			dataType : "json",
			success  : function(data){
				if(data){					
					if(data.result == true){
						success = true;
						parent.message = data.msg;
						parent.member_name = data.member_name;
						parent.member_id = data.member_id;
					}else{
						parent.message = data.msg;
					}
				}else{
					parent.message = "回傳資料錯誤!";
				}
			},
			error : function(xhr, ajaxOptions, thrownError){
				parent.message = "讀取資料時發生錯誤,請梢候再試!"+thrownError;
			} 
		});	
		return success;	 
	},
	MemberLogout : function(){ //會員登出
		var parent = this,
		success = false;
		$.ajax({
			url : "do/memberdo.php",
			async : false,
			type : "POST",
			data : { actiontype : "MemberLogout" },
			dataType  :"json",
			success : function(data){
				if(data){					
					if(data.result == true){
						success = true;
					}else{
						parent.message = data.msg;
					}
				}else{
					parent.message = "回傳資料錯誤";
				}
			},
			error : function(xhr, ajaxOptions, thrownError){
				parent.message = "讀取資料時發生錯誤,請梢候再試"+thrownError;
			}
		});	
		return success;	 
	},
	
	//會員修改
	update : function(member){ 
		var parent = this;
		success = false;
		$.ajaxSetup({ cache : false });
		$.ajax({
			url : "do/memberdo.php",
			async : false,
			type : "POST",
			data : { 
					name : member.name, 
					email : member.email,
					mobile : member.mobile,
					mobile_national_number : member.mobile_national_number,
					birthday : member.birthday, 
					address : member.address, 
					actiontype : "update" 
			},
			dataType : "json",
			success : function(data){
				if(data){
					if(data.result == true){
						success = true;
					}else{
						parent.message = data.msg;
					}
				}else{
					parent.message = "回傳資料錯誤";
				}
			},
			error : function(xhr, ajaxOptions, thrownError){
				parent.message = "讀取資料時發生錯誤,請梢候再試"+thrownError;
			}
		});
		return success;
	},
	//會員修改密碼
	changePassword : function(old_password, new_password){ 
		var parent = this;
		success = false;
		$.ajaxSetup({ cache : false });
		$.ajax({
			url : "do/memberdo.php",
			async : false,
			type : "POST",
			data : { 
					oldpassword : old_password,
					password : new_password, 
					actiontype : "changePassword" 
			},
			dataType : "json",
			success : function(data){
				if(data){
					if(data.result){
						success = true;
					}else{
						parent.message = data.msg;
					}
				}else{
					parent.message = "回傳資料錯誤";
				}
			},
			error : function(xhr, ajaxOptions, thrownError){
				parent.message = "讀取資料時發生錯誤,請梢候再試"+thrownError;
			}
		});
		return success;
	},
	//忘記密碼
	passwordMail : function(email){
		var parent = this,
		success = false;
		$.ajax({
			url : "do/memberdo.php",
			async : false,
			type : "POST",
			data : {email : email, 
					actiontype : "passwordMail" 
			},
			dataType : "json",
			success : function(data){
				if(data){
					if(data.result){
						success = true;
						parent.message = data.msg;
					}else{
						parent.message = data.msg;
					}
				}else{
					parent.message = "回傳資料錯誤";
				}
			},
			error : function(xhr, ajaxOptions, thrownError){
				parent.message = "讀取資料時發生錯誤,請梢候再試"+thrownError;
			}
		});
		return success;		 
	},
	
	
	chkToken : function(member, path){
		var parent = this,
		success = false;
		$.ajax({
			url : "do/memberdo.php",
			async : false,
			type : "POST",
			data : { accesstoken : member.accesstoken, actiontype : "chkToken" },
			dataType : "json",
			success : function(data){
				if(data){					
					if(data.result == "1"){
						success = true;
					}else{
						parent.message = data.msg;
						parent.isMember = data.isMember;
						parent.success = data.success;
						parent.member_id = data.member_id;
					}
					parent.member_id = data.member_id;
				}else{
					parent.message = "回傳資料錯誤";
				}
			}
			,error : function(xhr, ajaxOptions, thrownError){
				parent.message = "讀取資料時發生錯誤,請梢候再試"+thrownError;
			}
		});	
		return success;	 
	 },
	
	//***lazycat used }
	
	
	getList : function(member){
		var parent = this,
		success = false;
		$.ajax({
			url : "php/memberdo.php",
			async : false,
			type : "POST",
			data : {id : member.id, actiontype : "getlist" },
			dataType : "json",
			success : function(data){
				if(data){
					if(data.result == true){
						success = true;
						parent.list = data.list;
					}else{
						parent.message = data.msg;
					}
				}else{
					parent.message = "回傳資料錯誤";
				}
			},
			error : function(xhr, ajaxOptions, thrownError){
				parent.message = "讀取資料時發生錯誤,請梢候再試"+thrownError;
			}
		});
		return success;		 
	},
	 
 
	 chkCode:function(member){
		var parent=this,
		success=false;
		$.ajax({
			url:"do/memberdo.php",
			async:false,
			type:"POST",
			data: { code: member.code, actiontype:"chkCode" },
			dataType:"json",
			success:function(data){
				if(data){
					if(data.result=="1"){
						success=true;
					}else{
						parent.message=data.msg;
					}
				}else{
					parent.message="回傳資料錯誤";
				}
			}
			,error:function(xhr, ajaxOptions, thrownError){
				parent.message="讀取資料時發生錯誤,請梢候再試"+thrownError;
			}
		});	
		return success;	 
	 },
	 
	 
	 
	 UnsetFB:function(){
		var parent=this,
		success=false;
		$.ajax({
			url:"do/memberdo.php",
			async:false,
			type:"POST",
			data: { actiontype:"UnsetFB" },
			dataType:"json",
			success:function(data){
				if(data){					
					if(data.result=="1"){
						success=true;
					}
					else{
						parent.message=data.msg;
					}
				}
				else{
					parent.message="回傳資料錯誤";
				}
			}
			,error:function(xhr, ajaxOptions, thrownError){
				parent.message="讀取資料時發生錯誤,請梢候再試"+thrownError;
			}
		});	
		return success;	 
	 }
}



/*function setCookie(m_id){
		var parent = this,
		success = false;
		$.ajax({
			url : "php/memberdo.php",
			async : false,
			type : "POST",
			data : {m_id : m_id, actiontype : "setCookie" },
			dataType : "json",
			success : function(data){
				if(data){
					if(data.result == true){
						success = true;
						parent.list = data.list;
					}else{
						parent.message = data.msg;
					}
				}else{
					parent.message = "回傳資料錯誤";
				}
			},
			error : function(xhr, ajaxOptions, thrownError){
				parent.message = "讀取資料時發生錯誤,請梢候再試"+thrownError;
			}
		});
		return success;		 
}*/