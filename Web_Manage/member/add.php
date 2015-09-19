<?php
include_once("_config.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Untitled Document</title>
<link href="../css/admin_style_gray.css" rel="stylesheet" />
<script src="../../scripts/jquery-1.6.1rc1.min.js"></script>
<script src="../../scripts/public.js"></script>
<script src="../../scripts/function.js"></script>
<script src="../../scripts/addressselectlist.js"></script>
<script src="../../scripts/jquery.validate.js"></script>
<script>
function checkAcc(acc){
	var success = false;
	$.ajax({
		url : 'check_acc.php',
		type : 'POST',
		data : {
			acc : acc
		},
		async : false,
		error : function(){
			err_msg = "發生錯誤";
			success = false;
		},
		success : function(response){
			if(response == "1"){
				success = true;
			}else{
				success = false;
			} 
		}
	});
	 return success;
}
function set_ymd_date(year_start)
{
	var now = new Date();
 
	//年(year_start~今年)
	for(var i = now.getFullYear(); i >= year_start; i--)
	{
		$('#ymd_year').
		append($("<option></option>").
		attr("value",i).
		text(i));
	}
 
	//月
	for(var i = 1; i <= 12; i++)
	{
		$('#ymd_month').
		append($("<option></option>").
		attr("value",i).
		text(i));
	}
 
	$('#ymd_year').change(onChang_date);   
	$('#ymd_month').change(onChang_date);   
 
	//年、月選單改變時
	function onChang_date()
	{
		if($('#ymd_year').val() != "" && $('#ymd_month').val() != ""){
 
			var date_temp = new Date($('#ymd_year').val(), $('#ymd_month').val(), 0);
 
			//移除超過此月份的天數
			$("#ymd_day option").each(function()
			{
				if($(this).val() != "" && $(this).val() > date_temp.getDate()) $(this).remove();
			});                
 
			//加入此月份的天數
			for(var i = 1; i <= date_temp.getDate(); i++)
			{
				if(!$("#ymd_day option[value='" + i + "']").length)
				{
					$('#ymd_day').
					append($("<option></option>").
					attr("value",i).
					text(i));
				}
			}
		} 
		else 
		{
			$("#ymd_day option:selected").removeAttr("selected");
		}      
	}
}
$(document).ready(function(){
	set_ymd_date(1911);
	
	jQuery.validator.addMethod("isMobile", function(value, element){
		var length = value.length;
		return this.optional(element) || (length == 10 && /^09[0-9]{8}$/.test(value));
	}, "請正確填寫您的手機號碼");
	
	jQuery.validator.addMethod("chkAcc", function(value, element){
		var acc=$('#acc').val();
		return this.optional(element) || checkAcc(acc);
	}, "會員帳號重覆,請重新輸入");

	$("#form").validate({
		rules: {
			acc: {
				required: true,
				chkAcc: true
			},
			pwd: {
				maxlength: 12,
				required: true
			},
			pwd2: {
				maxlength: 12,
				equalTo: "#pwd"
			},
			name: {
				required: true,
				minlength: 2
			},
			mobile: {
				required: true
			},
			email: {
				required: true,
				email: true
			}, 
			ymd_year: {
				required: true,
			},
			ymd_month: {
				required: true,
			},
			ymd_day: {
				required: true,
			},
			address: {
				required: true,
			}
		},
		messages: {
			acc:{ 
				required:"請輸入帳號",
			},
			pwd: {
				maxlength: jQuery.format("最多不要超過{0}碼"),
				required: "請輸密碼"
			},
			pwd2: {
				maxlength: jQuery.format("最多不要超過{0}碼"),
				equalTo: "兩次密碼輸入不相同"
			},
			name: {
				required: "請輸入您的姓名",
				minlength: "姓名至少二個字"
			},
			mobile: {
				required: "請輸入您的手機"
			},
			email: {
				required: "請輸入您的email",
				email:"請填寫正確的EMAIL格式"
			},
			ymd_year:{ 
				required:"請輸入年"
			},
			ymd_month:{ 
				required:"請輸入月"
			},
			ymd_day:{ 
				required:"請輸入日"
			},
			address: {
				required: "請輸入地址"
			}
		}
	});
	
	$("div.listshow").one('focus', 'form#form', function () {
		set_ymd_date(1911);
		return false;
	})
});
</script>
<style>
.error {
	color:#F00;
}
</style>
</head>

<body>
<div id="mgbody-content">
    <div id="adminlist">
        <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle; ?> >&nbsp;&nbsp;新增</h2>
        <div class="accordion ">
            <div class="tableheader">
                <div class="handlediv"></div>
                <p align="left">新增會員</p>
            </div>
            <div class="listshow">
                <FORM action="save.php?<?php echo $query_str; ?>" method="post" enctype="multipart/form-data" name="form" id="form">
                    <table width="850" border="0" cellpadding="0" cellspacing="3">
                        <!--<tr>
                            <td width="150" valign="top"><h4 class="input-text-title">是否顯示</h4></td>
                            <td><input type="checkbox" name="isshow" id="isshow" checked value="1"/>
                                顯示 </td>
                        </tr>-->
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">帳號</h4></td>
                            <td><input type="text" name="acc" id="acc" size="40" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">密碼</h4></td>
                            <td><input type="password" name="pwd" id="pwd" size="40" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">確認密碼</h4></td>
                            <td><input type="password" name="pwd2" id="pwd2" size="40" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">姓名</h4></td>
                            <td><input type="text" name="name" id="name" size="40" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">email</h4></td>
                            <td><input type="text" name="email" id="email" size="40" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">手機</h4></td>
                            <td><select name="mobile_national_number" id="mobile_national_number" style="width:275px;">
                                    <option value="93" >Afghanistan (+93)</option>
                                    <option value="355" >Albania (+355)</option>
                                    <option value="213" >Algeria (+213)</option>
                                    <option value="1684" >American Samoa (+1684)</option>
                                    <option value="376" >Andorra (+376)</option>
                                    <option value="244" >Angola (+244)</option>
                                    <option value="1264" >Anguilla (+1264)</option>
                                    <option value="1268" >Antigua and Barbuda (+1268)</option>
                                    <option value="54" >Argentina (+54)</option>
                                    <option value="374" >Armenia (+374)</option>
                                    <option value="297" >Aruba (+297)</option>
                                    <option value="61" >Australia (+61)</option>
                                    <option value="43" >Austria (+43)</option>
                                    <option value="994" >Azerbaijan (+994)</option>
                                    <option value="1242" >Bahamas (+1242)</option>
                                    <option value="973" >Bahrain (+973)</option>
                                    <option value="880" >Bangladesh (+880)</option>
                                    <option value="1246" >Barbados (+1246)</option>
                                    <option value="375" >Belarus (+375)</option>
                                    <option value="32" >Belgium (+32)</option>
                                    <option value="501" >Belize (+501)</option>
                                    <option value="229" >Benin (+229)</option>
                                    <option value="1441" >Bermuda (+1441)</option>
                                    <option value="975" >Bhutan (+975)</option>
                                    <option value="591" >Bolivia, Pluricountryal State of (+591)</option>
                                    <option value="387" >Bosnia and Herzegovina (+387)</option>
                                    <option value="267" >Botswana (+267)</option>
                                    <option value="55" >Brazil (+55)</option>
                                    <option value="673" >Brunei Darussalam (+673)</option>
                                    <option value="359" >Bulgaria (+359)</option>
                                    <option value="226" >Burkina Faso (+226)</option>
                                    <option value="257" >Burundi (+257)</option>
                                    <option value="855" >Cambodia (+855)</option>
                                    <option value="237" >Cameroon (+237)</option>
                                    <option value="1" >Canada (+1)</option>
                                    <option value="238" >Cape Verde (+238)</option>
                                    <option value="1345" >Cayman Islands (+1345)</option>
                                    <option value="236" >Central African Republic (+236)</option>
                                    <option value="235" >Chad (+235)</option>
                                    <option value="56" >Chile (+56)</option>
                                    <option value="86" >China (+86)</option>
                                    <option value="57" >Colombia (+57)</option>
                                    <option value="269" >Comoros (+269)</option>
                                    <option value="242" >Congo (+242)</option>
                                    <option value="243" >Congo, the Democratic Republic of the (+243)</option>
                                    <option value="682" >Cook Islands (+682)</option>
                                    <option value="506" >Costa Rica (+506)</option>
                                    <option value="225" >Cote d'Ivoire (+225)</option>
                                    <option value="385" >Croatia (+385)</option>
                                    <option value="53" >Cuba (+53)</option>
                                    <option value="357" >Cyprus (+357)</option>
                                    <option value="420" >Czech Republic (+420)</option>
                                    <option value="45" >Denmark (+45)</option>
                                    <option value="253" >Djibouti (+253)</option>
                                    <option value="1767" >Dominica (+1767)</option>
                                    <option value="1809" >Dominican Republic (+1809)</option>
                                    <option value="593" >Ecuador (+593)</option>
                                    <option value="20" >Egypt (+20)</option>
                                    <option value="503" >El Salvador (+503)</option>
                                    <option value="240" >Equatorial Guinea (+240)</option>
                                    <option value="291" >Eritrea (+291)</option>
                                    <option value="372" >Estonia (+372)</option>
                                    <option value="251" >Ethiopia (+251)</option>
                                    <option value="500" >Falkland Islands (Malvinas) (+500)</option>
                                    <option value="298" >Faroe Islands (+298)</option>
                                    <option value="679" >Fiji (+679)</option>
                                    <option value="358" >Finland (+358)</option>
                                    <option value="33" >France (+33)</option>
                                    <option value="594" >French Guiana (+594)</option>
                                    <option value="689" >French Polynesia (+689)</option>
                                    <option value="241" >Gabon (+241)</option>
                                    <option value="220" >Gambia (+220)</option>
                                    <option value="995" >Georgia (+995)</option>
                                    <option value="49" >Germany (+49)</option>
                                    <option value="233" >Ghana (+233)</option>
                                    <option value="350" >Gibraltar (+350)</option>
                                    <option value="30" >Greece (+30)</option>
                                    <option value="299" >Greenland (+299)</option>
                                    <option value="1473" >Grenada (+1473)</option>
                                    <option value="590" >Guadeloupe (+590)</option>
                                    <option value="1671" >Guam (+1671)</option>
                                    <option value="502" >Guatemala (+502)</option>
                                    <option value="224" >Guinea (+224)</option>
                                    <option value="245" >Guinea-Bissau (+245)</option>
                                    <option value="592" >Guyana (+592)</option>
                                    <option value="509" >Haiti (+509)</option>
                                    <option value="379" >Holy See (Vatican City State) (+379)</option>
                                    <option value="504" >Honduras (+504)</option>
                                    <option value="852" >Hong Kong (+852)</option>
                                    <option value="36" >Hungary (+36)</option>
                                    <option value="354" >Iceland (+354)</option>
                                    <option value="91" >India (+91)</option>
                                    <option value="62" >Indonesia (+62)</option>
                                    <option value="98" >Iran, Islamic Republic of (+98)</option>
                                    <option value="964" >Iraq (+964)</option>
                                    <option value="353" >Ireland (+353)</option>
                                    <option value="972" >Israel (+972)</option>
                                    <option value="39" >Italy (+39)</option>
                                    <option value="1876" >Jamaica (+1876)</option>
                                    <option value="81" >Japan (+81)</option>
                                    <option value="962" >Jordan (+962)</option>
                                    <option value="7" >Kazakhstan (+7)</option>
                                    <option value="254" >Kenya (+254)</option>
                                    <option value="686" >Kiribati (+686)</option>
                                    <option value="850" >Korea, Democratic People's Republic of (+850)</option>
                                    <option value="82" >Korea, Republic of (+82)</option>
                                    <option value="965" >Kuwait (+965)</option>
                                    <option value="996" >Kyrgyzstan (+996)</option>
                                    <option value="856" >Lao People's Democratic Republic (+856)</option>
                                    <option value="371" >Latvia (+371)</option>
                                    <option value="961" >Lebanon (+961)</option>
                                    <option value="266" >Lesotho (+266)</option>
                                    <option value="231" >Liberia (+231)</option>
                                    <option value="218" >Libyan Arab Jamahiriya (+218)</option>
                                    <option value="423" >Liechtenstein (+423)</option>
                                    <option value="370" >Lithuania (+370)</option>
                                    <option value="352" >Luxembourg (+352)</option>
                                    <option value="853" >Macao (+853)</option>
                                    <option value="389" >Macedonia, the former Yugoslav Republic of (+389)</option>
                                    <option value="261" >Madagascar (+261)</option>
                                    <option value="265" >Malawi (+265)</option>
                                    <option value="60" >Malaysia (+60)</option>
                                    <option value="960" >Maldives (+960)</option>
                                    <option value="223" >Mali (+223)</option>
                                    <option value="356" >Malta (+356)</option>
                                    <option value="692" >Marshall Islands (+692)</option>
                                    <option value="596" >Martinique (+596)</option>
                                    <option value="222" >Mauritania (+222)</option>
                                    <option value="230" >Mauritius (+230)</option>
                                    <option value="262" >Mayotte (+262)</option>
                                    <option value="52" >Mexico (+52)</option>
                                    <option value="691" >Micronesia, Federated States of (+691)</option>
                                    <option value="373" >Moldova, Republic of (+373)</option>
                                    <option value="377" >Monaco (+377)</option>
                                    <option value="976" >Mongolia (+976)</option>
                                    <option value="382" >Montenegro (+382)</option>
                                    <option value="1664" >Montserrat (+1664)</option>
                                    <option value="212" >Morocco (+212)</option>
                                    <option value="258" >Mozambique (+258)</option>
                                    <option value="95" >Myanmar (+95)</option>
                                    <option value="264" >Namibia (+264)</option>
                                    <option value="674" >Nauru (+674)</option>
                                    <option value="977" >Nepal (+977)</option>
                                    <option value="31" >Netherlands (+31)</option>
                                    <option value="599" >Netherlands Antilles (+599)</option>
                                    <option value="687" >New Caledonia (+687)</option>
                                    <option value="64" >New Zealand (+64)</option>
                                    <option value="505" >Nicaragua (+505)</option>
                                    <option value="227" >Niger (+227)</option>
                                    <option value="234" >Nigeria (+234)</option>
                                    <option value="683" >Niue (+683)</option>
                                    <option value="672" >Norfolk Island (+672)</option>
                                    <option value="1670" >Northern Mariana Islands (+1670)</option>
                                    <option value="47" >Norway (+47)</option>
                                    <option value="968" >Oman (+968)</option>
                                    <option value="92" >Pakistan (+92)</option>
                                    <option value="680" >Palau (+680)</option>
                                    <option value="507" >Panama (+507)</option>
                                    <option value="675" >Papua New Guinea (+675)</option>
                                    <option value="595" >Paraguay (+595)</option>
                                    <option value="51" >Peru (+51)</option>
                                    <option value="63" >Philippines (+63)</option>
                                    <option value="870" >Pitcairn (+870)</option>
                                    <option value="48" >Poland (+48)</option>
                                    <option value="351" >Portugal (+351)</option>
                                    <option value="1" >Puerto Rico (+1)</option>
                                    <option value="974" >Qatar (+974)</option>
                                    <option value="262" >Reunion (+262)</option>
                                    <option value="40" >Romania (+40)</option>
                                    <option value="7" >Russian Federation (+7)</option>
                                    <option value="250" >Rwanda (+250)</option>
                                    <option value="290" >Saint Helena, Ascension and Tristan da Cunha (+290)</option>
                                    <option value="1869" >Saint Kitts and Nevis (+1869)</option>
                                    <option value="1758" >Saint Lucia (+1758)</option>
                                    <option value="508" >Saint Pierre and Miquelon (+508)</option>
                                    <option value="1784" >Saint Vincent and the Grenadines (+1784)</option>
                                    <option value="685" >Samoa (+685)</option>
                                    <option value="378" >San Marino (+378)</option>
                                    <option value="239" >Sao Tome and Principe (+239)</option>
                                    <option value="966" >Saudi Arabia (+966)</option>
                                    <option value="221" >Senegal (+221)</option>
                                    <option value="381" >Serbia (+381)</option>
                                    <option value="248" >Seychelles (+248)</option>
                                    <option value="232" >Sierra Leone (+232)</option>
                                    <option value="65" >Singapore (+65)</option>
                                    <option value="421" >Slovakia (+421)</option>
                                    <option value="386" >Slovenia (+386)</option>
                                    <option value="677" >Solomon Islands (+677)</option>
                                    <option value="252" >Somalia (+252)</option>
                                    <option value="27" >South Africa (+27)</option>
                                    <option value="34" >Spain (+34)</option>
                                    <option value="94" >Sri Lanka (+94)</option>
                                    <option value="249" >Sudan (+249)</option>
                                    <option value="597" >Suriname (+597)</option>
                                    <option value="268" >Swaziland (+268)</option>
                                    <option value="46" >Sweden (+46)</option>
                                    <option value="41" >Switzerland (+41)</option>
                                    <option value="963" >Syrian Arab Republic (+963)</option>
                                    <option value="886" selected>Taiwan (+886)</option>
                                    <option value="992" >Tajikistan (+992)</option>
                                    <option value="255" >Tanzania, United Republic of (+255)</option>
                                    <option value="66" >Thailand (+66)</option>
                                    <option value="228" >Togo (+228)</option>
                                    <option value="690" >Tokelau (+690)</option>
                                    <option value="676" >Tonga (+676)</option>
                                    <option value="1868" >Trinidad and Tobago (+1868)</option>
                                    <option value="216" >Tunisia (+216)</option>
                                    <option value="90" >Turkey (+90)</option>
                                    <option value="993" >Turkmenistan (+993)</option>
                                    <option value="1649" >Turks and Caicos Islands (+1649)</option>
                                    <option value="688" >Tuvalu (+688)</option>
                                    <option value="256" >Uganda (+256)</option>
                                    <option value="380" >Ukraine (+380)</option>
                                    <option value="971" >United Arab Emirates (+971)</option>
                                    <option value="44" >United Kingdom (+44)</option>
                                    <option value="1" >United States (+1)</option>
                                    <option value="598" >Uruguay (+598)</option>
                                    <option value="998" >Uzbekistan (+998)</option>
                                    <option value="678" >Vanuatu (+678)</option>
                                    <option value="58" >Venezuela, Bolivarian Republic of (+58)</option>
                                    <option value="84" >Viet Nam (+84)</option>
                                    <option value="1284" >Virgin Islands, British (+1284)</option>
                                    <option value="1340" >Virgin Islands, U.S. (+1340)</option>
                                    <option value="681" >Wallis and Futuna (+681)</option>
                                    <option value="967" >Yemen (+967)</option>
                                    <option value="260" >Zambia (+260)</option>
                                    <option value="263" >Zimbabwe (+263)</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"></td>
                            <td><input type="text" name="mobile" id="mobile" size="40" value=""/></td>
                        </tr>
                        <!--<tr>
                            <td width="150" valign="top"><h4 class="input-text-title">性別</h4></td>
                            <td><input name="gender" type="radio" value="0" checked>
                                女
                                <input name="gender" type="radio" value="1">
                                男</td>
                        </tr>-->
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">生日</h4></td>
                            <td>西元
                                <select name="ymd_year" id="ymd_year">
                                    <option value="">請選擇</option>
                                </select>
                                年
                                <select name="ymd_month" id="ymd_month">
                                    <option value="">請選擇</option>
                                </select>
                                月
                                <select name="ymd_day" id="ymd_day">
                                    <option value="">請選擇</option>
                                </select>
                                日</td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">地址</h4></td>
                            <td><input type="text" name="address" id="address" size="40" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150"></td>
                            <td height="30"><input name="savenews" type="submit" id="savenews" value=" 送 出 " />
                                &nbsp;&nbsp;&nbsp;
                                <input name="" type="reset" value=" 重 設 " /></td>
                        </tr>
                    </table>
                </FORM>
            </div>
        </div>
    </div>
</div>
</body>
</html>
