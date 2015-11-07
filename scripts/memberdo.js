$(document).ready(function() {
	var myMember = new coderMember();
	
	$.validator.addMethod("isMobile", function(value, element) {
		var length = value.length;
		return this.optional(element) || (length == 10 && /^09[0-9]{8}$/.test(value));
	}, "請正確填寫手機號碼");

	$.validator.addMethod("chkAcc", function(value, element) {
		var acc = $('#acc').val();
		return this.optional(element) || myMember.chkAcc(acc);
	}, "會員帳號已存在,請重新輸入");
	
	$.validator.addMethod("chkEmail", function(value, element) {
		var email = $('#email').val();
		return this.optional(element) || myMember.chkEmail(email);
	}, "會員Email已存在,請重新輸入");

	$.validator.addMethod("acctounRule", function(value, element) {
		return this.optional(element) || /^[\u0391-\uFFE5\w]+$/.test(value);
	}, "請輸入英文大小寫,數字或_");
	
	var showErrors = function(errorMap, errorList){
		// Clean up any tooltips for valid elements
		$.each(this.validElements(), function (index, element){
			var $element = $(element);
			$element.data("title", "") // Clear the title - there is no error associated anymore
					.removeClass("error")
					.tooltip("destroy");
		});
		// Create new tooltips for invalid elements
		$.each(errorList, function (index, error){
			var $element = $(error.element);
			
			$element.tooltip("destroy")// Destroy any pre-existing tooltip so we can repopulate with new tooltip content
					.data("title", error.message)
					.addClass("error")
					.tooltip(); // Create a new tooltip based on the error messsage we just set in the title
		});
	}


	//加入會員
	if (document.getElementById("join_member_form")) {
		$('#join_member_btn').click(function() {
			$('#join_member_form').submit();
		});
		$('#address_country').change(function(){
			if($(this).val() != '台灣')
				$('#address_city').hide();
			else
				$('#address_city').show();	
		});
		//$('#join_member_table').tooltip();
		$("#join_member_form").validate({
			rules: {
				acc: {
					required: true,
					/*minlength: 8,
					maxlength: 12,
					acctounRule: true,*/
					email: true,
					chkAcc: true
				},
				pwd: {
					required: true,
					acctounRule: true,
					minlength: 8,
					maxlength: 12
				},
				repwd: {
					required: true,
					equalTo: "#pwd"
				},
				name: {
					required: true,
					minlength: 2,
					maxlength: 30
				},
				email: {
					required: true,
					email: true,
					chkEmail: true
				},
				sex: {
					required : true,
				},
				ymd_year : {
					required : true,
				},
				ymd_month : {
					required : true,
				},
				ymd_day : {
					required : true,
				},
				mobile: {
					required: true
					//isMobile: true
				},
				address:{
					required: true			
				},
				address_city:{
					required: true
				},
				address_code:{
					required: true
				}
			},
			messages: {
				acc: {
					required: "請輸入帳號",
					/*minlength: "帳號字數請勿少於8各字元",
					maxlength: "帳號字數請勿超過12各字元"*/
					email: "請填寫正確的Email格式"
				},
				pwd: {
					required: "請填寫密碼",
					minlength: "密碼字數請勿少於8各字元",
					maxlength: "密碼字數請勿超過12各字元"
				},
				repwd: {
					required: "請填寫確認密碼",
					equalTo: "兩次密碼輸入不相同"
				},
				name: {
					required: "請輸入您的姓名",
					minlength: "姓名字數請勿少於2各字元",
					maxlength: "姓名字數請勿超過30各字元"
				},
				email: {
					required: "請輸入您的email",
					email: "請填寫正確的Email格式"
				},
				sex : {
					required: "請選擇性別"
				},
				ymd_year : { 
					required : "請輸入年"
				},
				ymd_month : { 
					required : "請輸入月"
				},
				ymd_day : { 
					required : "請輸入日"
				},
				mobile: {
					required: "請輸入手機號碼"
				},
				address:{
					required: "請輸入收件地址"			
				},
				address_city:{
					required: "請選擇縣市"
				},
				address_code:{
					required: "請輸入郵遞區號"
				}
			},

			showErrors: showErrors,
			
			submitHandler: function(form) {
				alertify.log("加入會員中...請稍待!");
				$('#join_member_btn').hide();
				//var myMember = new coderMember();
				var _item = new Object();
				_item.account = $('#acc').val();
				_item.password = $('#pwd').val();
				_item.name = $('#name').val();
				_item.email = $('#acc').val();
				_item.sex = $('#sex').val();
				_item.birthday = $('#ymd_year').val()+padLeft($('#ymd_month').val(), 2)+padLeft($('#ymd_day').val(), 2);
				_item.mobile_national_number = $('#mobile_national_number').val();
				_item.mobile = $('#mobile').val();
				_item.address = '('+ $('#address_code').val() +')' + $('#address_country').val() + $('#address_city').val() + $('#address').val();
				console.log(_item.address);
				setTimeout(
					function() {
						if (myMember.insert(_item, 'insert')) {
							//window.location.href = "auth.html";
							alert("會員加入完成!");
							if(myMember.MemberLogin(_item)){
								window.location.href = "index.html";
							}
						} else {
							$('#join_member_btn').show();
							alert("會員加入失敗！");
						}
					}, 1000);
			}
		});
	}
	
	
	// 登入
	if (document.getElementById("login_member_form")) {
		$('#login_member_btn').click(function() {
			$('#login_member_form').submit();
		});
		/*if($.cookie('login_account')!=''){
			$('#account').val($.cookie('login_account'));
		}*/
		//$('#login_member_table').tooltip();
		
		$("#login_member_form").validate({
			rules: {
				account: {
					required: true,
					email: true
				},
				password: {
					required: true,
					minlength: 8
				},
				checkword: {
					required: true
				},
				agree: {
					required: true
				}				
			},
			messages: {
				account: {
					required: '請填寫您的登入帳號',
					email: "請填寫正確的Email格式"
				},
				password:{
					required: '請填寫您的登入密碼',
					minlength: "密碼字數請勿少於8各字元"
				},
				checkword: {
					required: '請輸入驗證碼'
				},
				agree: {
					required: '請同意LazyCat服務條款'
				}
			},

			showErrors: showErrors,
			submitHandler: function(form) {
				var keep_login = $('input[name=remember]:checked').val();
				var page = $("#page").val();
				var pro_id = $("#pro_id").val();
				var type_id = $("#type_id").val();
				alertify.log("登入驗證中...請稍候!");
				$('#login_member_btn').hide();
				var _item = new Object();
				_item.account = $('#account').val();
				_item.password = $('#password').val();
				_item.checkCode = $('#checkword').val();
				//console.log(_item);
				setTimeout(
					function() {
						if(myMember.MemberLogin(_item)){
							if(keep_login == 1){
								var m_str = myMember.member_id+myMember.member_name;
								//console.log(m_str);
								var m_token = MD5(m_str);
								setCookie("m_id", m_token, 15);
								setToken(m_token, myMember.member_id);
							}else{
								deleteCookie("m_id");	
							}
							if(page == "wish"){
								window.location.href = "wishlist.html";
							}else if(page == "detail"){
								window.location.href = "product.html?pro_id="+pro_id+"&type_id="+type_id;
							}else{
								window.location.href = "index.html";
							}
								
						}else{
							$('#login_member_btn').show();
							alert(myMember.message);	
						}
					}, 1000);
			}
		});
	}
	
	//修改會員
	if (document.getElementById("modifyForm")) {
		$('#modify_btn').click(function() {
			$('#modifyForm').submit();
		});

		$("#modifyForm").validate({
			rules: {
				name: {
					required: true,
					minlength: 2,
					maxlength: 30
				},
				email: {
					required: true,
					email: true,
					chkEmail: true
				},
				ymd_year : {
					required : true,
				},
				ymd_month : {
					required : true,
				},
				ymd_day : {
					required : true,
				},
				mobile: {
					required: true
					//isMobile: true
				},
				address: {
					required: true
				}
				
			},
			messages: {
				name: {
					required: "請輸入您的姓名",
					minlength: "姓名字數請勿少於2各字元",
					maxlength: "姓名字數請勿超過30各字元"
				},
				email: {
					required: "請輸入您的email",
					email: "請填寫正確的Email格式"
				},
				ymd_year : { 
					required : "請輸入年"
				},
				ymd_month : { 
					required : "請輸入月"
				},
				ymd_day : { 
					required : "請輸入日"
				},
				mobile: {
					required: "請輸入手機號碼"
				},
				address: {
					required: "請輸入地址"
				}
				
			},

			showErrors: showErrors,
			submitHandler: function(form) {
				alertify.log("修改會員中...請稍待!");

				$('#modify_btn').hide();
				var _item = new Object();
				_item.name = $('#name').val();
				_item.email = $('#email').val();
				_item.birthday = $('#ymd_year').val()+padLeft($('#ymd_month').val(), 2)+padLeft($('#ymd_day').val(), 2);
				_item.mobile_national_number = $('#mobile_national_number').val();
				_item.mobile = $('#mobile').val();
				_item.address = $('#address').val();
				
				setTimeout(
					function() {
						
						if (myMember.update(_item)) {
							alert('您的資料己修改完成');
							$('#modify_btn').show();
						} else {
							$('#modify_btn').show();
							alert("修改會員資料失敗！ <br> " + myMember.message);
						}
					}, 1000);
			}
		});
	}

	//修改密碼
	if (document.getElementById("passwordForm")) {
		$('#pwd_btn').click(function() {
			$('#passwordForm').submit();
		});
		
		$("#passwordForm").validate({
			rules: {
				oldpassword: {
					required: true,
					minlength: 8,
					maxlength: 12,
					acctounRule: true
				},
				password: {
					required: true,
					acctounRule: true,
					minlength: 8,
					maxlength: 12
				},
				repassword: {
					required: true,
					minlength: 8,
					maxlength: 12,
					equalTo: "#password"
				}
			},
			messages: {
				oldpassword: {
					required: "請填寫您目前的登入密碼",
					minlength: "密碼字數請勿少於8各字元",
					maxlength: "密碼字數請勿超過12各字元"
				},
				password: {
					required: "請填寫新密碼",
					minlength: "密碼字數請勿少於8各字元",
					maxlength: "密碼字數請勿超過12各字元"
				},
				repassword: {
					required: "請填寫確認新密碼",
					equalTo: "兩次密碼輸入不相同"
				}
			},

			showErrors: showErrors,
			submitHandler: function(form) {
				alertify.log("修改密碼中...請稍待!");
				$('#pwd_btn').hide();
				
				setTimeout(
					function() {
						if (myMember.changePassword($('#oldpassword').val(),$('#password').val())) {
							alertify.alert('密碼修改完成,請重新登入!',function(){logOut();});
							
						} else {
							$('#pwd_btn').show();
							alertify.alert("密碼修改失敗！ <br> " + myMember.message);
						}
					}, 1000);
			}
		});
	}
	
	// 忘記密碼
	if (document.getElementById("forgetForm")) {
		$('#forget_pwd_btn').click(function() {
			$('#forgetForm').submit();
		});

		$("#forgetForm").validate({
			rules: {
				email: {
					required: true,
					email: true
				}
			},
			messages: {
				email: {
					required: '請輸入註冊時所填寫的Eamil',
					email: "請填寫正確的Email格式"
				}
			},

			showErrors: showErrors,
			submitHandler: function(form) {
				$('#forget_pwd_btn').hide();
				alertify.log("發信中...請稍候!");
				setTimeout(
					function() {
						if (myMember.passwordMail($('#email').val())) {
							alertify.alert('新密碼己寄出,請至您所填寫的Email中收取!', function() {
								parent.$.colorbox.close();
								window.location.href = "sign.html";
							});
						} else {
							$('#forget_pwd_btn').show();
							alert("發信失敗！ <br> " + myMember.message);
						}
					}, 1000
				);
			}
		});
	}

	
	//FB加入會員
/*	if (document.getElementById("fbjoinForm")) {
		$('#address_div').twzipcode({
			countyName: 'city',
			districtName: 'area',
			zipcodeName: 'zcode',
			countySel: '',
			districtSel: '',
			zipcodeSel: '',
			css: ['join_option_fix', 'join_option_fix', 'join_option_fix']
		});
		
		$('#submitBtn').click(function() {
			$('#fbjoinForm').submit();
		});
		var check_employee = function() {
        	return $("#is_employee").is(':checked');
    	};
		$('.join_table').tooltip();
		$("#fbjoinForm").validate({
			rules: {
				name: {
					required: true,
					minlength: 2,
					maxlength: 30
				},
				sex: {
					required: true
				},
				year: {
					required: true
				},
				month: {
					required: true
				},
				day: {
					required: true
				},
				zcode: {
					required: true
				},
				tel_zone: {
					required: true,
					minlength: 2,
					maxlength: 4,
					digits: true
				},
				tel: {
					required: true,
					minlength: 6,
					maxlength: 10,
					digits: true
				},
				mobile: {
					required: true,
					isMobile: true
				},
				city: {
					required: true
				},
				area: {
					required: true
				},
				addr: {
					required: true
				},
				email: {
					required: true,
					email: true,
					chkEmail: true
				},
				code: {
					required: true,
					maxlength: 5
				},
				is_red: {
					required: true
				},
				company_name: {
					required: { depends: check_employee }
				},
				company_sno: {
					required: { depends: check_employee }
				},
				company_phone: {
					required: { depends: check_employee }
				},
				company_email: {
					required: { depends: check_employee },
					email: true
				}
			},
			messages: {
				username: {
					required: "請輸入您的姓名",
					minlength: "姓名字數太少"
				},
				sex: {
					required: "請設定您的姓別"
				},
				year: {
					required: "請選擇生日年份"
				},
				month: {
					required: "請選擇生日月份"
				},
				day: {
					required: "請選擇生日日期"
				},
				zcode: {
					required: "請選擇居住區域"
				},
				tel_zone: {
					required: "請輸入聯絡電話區碼",
					minlength: "最少請輸入2碼",
					maxlength: "最多請勿超過4碼",
					digits: "請輸入數字"
				},
				tel: {
					required: "請輸入聯絡電話",
					minlength: "最少請輸入6碼",
					maxlength: "最多請勿超過10碼",
					digits: "請輸入數字"
				},
				mobile: {
					required: "請輸入手機號碼"
				},
				city: {
					required: "請選擇縣市"
				},
				area: {
					required: "請選擇鄉鎮市區"
				},
				addr: {
					required: "請輸入地址"
				},
				email: {
					email: "請填寫正確的Email格式"
				},
				code: {
					required: "請輸入左圖中的數字"
				},
				is_red: {
					required: "仔細閱讀並同意「會員服務條款」與「隱私權聲明」等內容!"
				},
				company_name: {
					required: "請輸入公司名稱"
				},
				company_sno: {
					required: "請輸入員工編號"
				},
				company_phone: {
					required: "請輸入公司電話+分機"
				},
				company_email: {
					required: "請輸入公司Email",
					email: "請填寫正確的EMAIL格式"
				}
			},

			showErrors: showErrors,
			submitHandler: function(form) {
				alertify.log("加入會員中...請稍待!");

				$('#submitBtn').hide();
				var _item = new Object();
				_item.name = $('#name').val();
				_item.sex = $('input:radio:checked[name="sex"]').val();
				_item.birthday = $('#year').val() + '/' + $('#month').val() + '/' + $('#day').val();
				_item.zcode = $('#zcode').val();
				_item.tel = $('#tel').val();
				_item.tel_zone = $('#tel_zone').val();
				_item.mobile = $('#mobile').val();
				_item.city = $('#city').val();
				_item.area = $('#area').val();
				_item.addr = $('#addr').val();
				_item.email = $('#email').val();
				_item.epaper = $('#epaper').prop('checked') ? 1 : 0 ;
				_item.code = $('#code').val();
				<!--add by bill start-->
				_item.erp_id = $('#erp_id').val();
				_item.is_employee = $("#is_employee:checked").val();
				_item.company_name = $('#company_name').val();
				_item.company_phone = $('#company_phone').val();
				_item.company_email = $('#company_email').val();
				_item.company_sno = $('#company_sno').val();
				<!--add by bill start-->
				setTimeout(
					function() {
						if (member.insert(_item,'fbjoin')) {
							window.location.href = "auth.html";
						} else {
							$('#submitBtn').show();
							alert("會員加入失敗！ <br> " + member.message);
						}
					}, 1000);
			}
		});
		
	}
	// 輸入認證碼
	if (document.getElementById("registerForm")) {
		$('#submitBtn').click(function() {
			$('#registerForm').submit();
		});

		$("#registerForm").validate({
			rules: {
				code: {
					required: true,
					minlength: 10
				}
			},
			message: {
				code: {
					required: '請輸入Email中的認證碼'
				}
			},

			showErrors: showErrors,
			submitHandler: function(form) {
				$('#submitBtn').hide();
				alertify.log("認證中...請稍候!");
				setTimeout(
					function() {
						
						if (member.vaild($('#code').val())) {
							alert('認證完成,歡迎加入會員!');
							window.location.href = "login.html";
						} else {
							$('#submitBtn').show();
							alert("認證失敗！ <br> " + member.message);
						}
					}, 1000);
			}
		});
	}


	// 寄認證信
	if (document.getElementById("registerMailForm")) {
		$('#submitBtn').click(function() {
			$('#registerMailForm').submit();
		});



		$("#registerMailForm").validate({
			rules: {
				email: {
					required: true,
					email: true
				}
			},
			messages: {
				email: {
					required: '請輸入註冊時所填寫的Eamil'
				}
			},

			showErrors: showErrors,
			submitHandler: function(form) {
				$('#submitBtn').hide();
				alertify.log("發信中...請稍候!");
				setTimeout(
					function() {
						if (member.registerMail($('#email').val())) {
							alertify.alert('認證信己寄出,請至您填寫的Email中收取!', function() {
								window.location.href = "auth.html";
							});
						} else {
							$('#submitBtn').show();
							alert("發信失敗！ <br> " + member.message);
						}
					}, 1000);
			}
		});
	}

	



	
*/
});

