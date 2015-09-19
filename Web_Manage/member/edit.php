<?php
include_once("_config.php");

$id = get("id");
if($id == 0){
	script("資料傳輸不正確", "index.php");
}

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$sql = "SELECT * 
		FROM $table_member 
		WHERE $id_column = '$id'";
$row = $db -> query_first($sql);
if($row){
	$acc = $row["member_account"];
	$pwd = $row["member_password"];
	$name = $row["member_name"];
	$email = $row["member_email"];
	$mobile_nation = $row["member_mobile_nation"]; 
	$mobile = $row["member_mobile"];
	$birthday = $row["member_birthday"];
	$address = $row["member_address"];
	$update_time = $row["member_update_time"];
	$year = substr($birthday, 0, 4);
	$month = substr($birthday, 4, 2);
	$day = substr($birthday, 6, 2);
}else{
	script("資料不存在");
}

$db->close();
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
<script src="../../scripts/jquery.validate.js"></script>
<script>
var year = <?php echo $year; ?>;
var month = <?php echo $month; ?>;
var day = <?php echo $day; ?>;
function set_ymd_date(year_start, year, month, day){
	var now = new Date();
 
	//年(year_start~今年)
	for(var i = now.getFullYear(); i >= year_start; i--){
		if(year == i){
			$('#ymd_year').append($("<option></option>").attr("value",i).attr("selected",true).text(i));
		}else{
			$('#ymd_year').append($("<option></option>").attr("value",i).text(i));
		}
	}
 
	//月
	for(var i = 1; i <= 12; i++){
		if(month == i){
			$('#ymd_month').append($("<option></option>").attr("value",i).attr("selected",true).text(i));
		}else{
			$('#ymd_month').append($("<option></option>").attr("value",i).text(i));
		}
	}
 
	$('#ymd_year').change(onChang_date);   
	$('#ymd_month').change(onChang_date);   
 	onChang_date();
	//年、月選單改變時
	function onChang_date(){
		if($('#ymd_year').val() != "" && $('#ymd_month').val() != ""){
 
			var date_temp = new Date($('#ymd_year').val(), $('#ymd_month').val(), 0);
 
			//移除超過此月份的天數
			$("#ymd_day option").each(function(){
				if($(this).val() != "" && $(this).val() > date_temp.getDate()) $(this).remove();
			});                
 
			//加入此月份的天數
			for(var i = 1; i <= date_temp.getDate(); i++){
				if(!$("#ymd_day option[value='" + i + "']").length){
					if(day == i){
						$('#ymd_day').append($("<option></option>").attr("value",i).attr("selected",true).text(i));
					}else{
						$('#ymd_day').append($("<option></option>").attr("value",i).text(i));
					}
				}
			}
		}else{
			$("#ymd_day option:selected").removeAttr("selected");
		}      
	}
}

$(document).ready(function(){
	set_ymd_date(1911, year, month, day);
	
	jQuery.validator.addMethod("isMobile", function(value, element) {
		var length = value.length;
		return this.optional(element) || (length == 10 && /^09[0-9]{8}$/.test(value));
	}, "請正確填寫您的手機號碼");

	$("#form").validate({
		rules: {
//			acc: {
//				required: true,
//				email: true,
//				chkAcc: true
//			},
			pwd: {
				maxlength: 12
			},
			pwd2: {
				maxlength: 12,
				equalTo: "#pwd"
			},
			name: {
				required: true,
				minlength: 2
			},
			email: {
				required: true,
				email: true
			},
			mobile: {
				required: true
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
//			acc:{ 
//				required:"請輸入帳號",
//				email:"請填寫正確的EMAIL格式"
//			},
			pwd: {
				maxlength: jQuery.format("最多不要超過{0}碼")
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
	
	$("#nationality option").each(function(index, element) {
        var val = $(this).val();
		if(nationality == val){
			$(this).attr("selected", true);
		}
    });
	
});
</script>
</head>

<body>
<div id="mgbody-content">
    <div id="adminlist">
        <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle; ?> >&nbsp;&nbsp;修改</h2>
        <div class="accordion ">
            <div class="tableheader">
                <div class="handlediv"></div>
                <p align="left">修改會員</p>
            </div>
            <div class="listshow" >
                <form action="edit_save.php?<?php echo $query_str; ?>" method="post" enctype="multipart/form-data" name="form" id="form">
                    <input type="hidden" name="cid" value="<?php echo $id; ?>">
                    <table width="850" border="0" cellpadding="0" cellspacing="3">
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">最後更新時間</h4></td>
                            <td><?php echo $update_time; ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">帳號</h4></td>
                            <td><?php echo $acc; ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">密碼</h4></td>
                            <td ><input name="pwd" type="password" id="pwd" size="40" value="" /></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">確認密碼</h4></td>
                            <td><input type="password" name="pwd2" id="pwd2" size="40" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">姓名</h4></td>
                            <td><input name="name" type="text" id="name" size="40" value="<?php echo $name; ?>" /></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">email</h4></td>
                            <td><input type="text" name="email" id="email" size="40" value="<?php echo $email; ?>"/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">手機</h4></td>
                            <td><select name="mobile_national_number" id="mobile_national_number" style="width:275px;">
                                    <option value="93" <?php echo ($mobile_nation == 93) ? "selected" : ""; ?>>Afghanistan (+93)</option>
                                    <option value="355" <?php echo ($mobile_nation == 355) ? "selected" : ""; ?>>Albania (+355)</option>
                                    <option value="213" <?php echo ($mobile_nation == 213) ? "selected" : ""; ?>>Algeria (+213)</option>
                                    <option value="1684" <?php echo ($mobile_nation == 1684) ? "selected" : ""; ?>>American Samoa (+1684)</option>
                                    <option value="376" <?php echo ($mobile_nation == 376) ? "selected" : ""; ?>>Andorra (+376)</option>
                                    <option value="244" <?php echo ($mobile_nation == 244) ? "selected" : ""; ?>>Angola (+244)</option>
                                    <option value="1264" <?php echo ($mobile_nation == 1264) ? "selected" : ""; ?>>Anguilla (+1264)</option>
                                    <option value="1268" <?php echo ($mobile_nation == 1268) ? "selected" : ""; ?>>Antigua and Barbuda (+1268)</option>
                                    <option value="54" <?php echo ($mobile_nation == 54) ? "selected" : ""; ?>>Argentina (+54)</option>
                                    <option value="374" <?php echo ($mobile_nation == 374) ? "selected" : ""; ?>>Armenia (+374)</option>
                                    <option value="297" <?php echo ($mobile_nation == 297) ? "selected" : ""; ?>>Aruba (+297)</option>
                                    <option value="61" <?php echo ($mobile_nation == 61) ? "selected" : ""; ?>>Australia (+61)</option>
                                    <option value="43" <?php echo ($mobile_nation == 43) ? "selected" : ""; ?>>Austria (+43)</option>
                                    <option value="994" <?php echo ($mobile_nation == 994) ? "selected" : ""; ?>>Azerbaijan (+994)</option>
                                    <option value="1242" <?php echo ($mobile_nation == 1242) ? "selected" : ""; ?>>Bahamas (+1242)</option>
                                    <option value="973" <?php echo ($mobile_nation == 973) ? "selected" : ""; ?>>Bahrain (+973)</option>
                                    <option value="880" <?php echo ($mobile_nation == 880) ? "selected" : ""; ?>>Bangladesh (+880)</option>
                                    <option value="1246" <?php echo ($mobile_nation == 1246) ? "selected" : ""; ?>>Barbados (+1246)</option>
                                    <option value="375" <?php echo ($mobile_nation == 375) ? "selected" : ""; ?>>Belarus (+375)</option>
                                    <option value="32" <?php echo ($mobile_nation == 32) ? "selected" : ""; ?>>Belgium (+32)</option>
                                    <option value="501" <?php echo ($mobile_nation == 501) ? "selected" : ""; ?>>Belize (+501)</option>
                                    <option value="229" <?php echo ($mobile_nation == 229) ? "selected" : ""; ?>>Benin (+229)</option>
                                    <option value="1441" <?php echo ($mobile_nation == 1441) ? "selected" : ""; ?>>Bermuda (+1441)</option>
                                    <option value="975" <?php echo ($mobile_nation == 975) ? "selected" : ""; ?>>Bhutan (+975)</option>
                                    <option value="591" <?php echo ($mobile_nation == 591) ? "selected" : ""; ?>>Bolivia, Pluricountryal State of (+591)</option>
                                    <option value="387" <?php echo ($mobile_nation == 387) ? "selected" : ""; ?>>Bosnia and Herzegovina (+387)</option>
                                    <option value="267" <?php echo ($mobile_nation == 267) ? "selected" : ""; ?>>Botswana (+267)</option>
                                    <option value="55" <?php echo ($mobile_nation == 55) ? "selected" : ""; ?>>Brazil (+55)</option>
                                    <option value="673" <?php echo ($mobile_nation == 673) ? "selected" : ""; ?>>Brunei Darussalam (+673)</option>
                                    <option value="359" <?php echo ($mobile_nation == 359) ? "selected" : ""; ?>>Bulgaria (+359)</option>
                                    <option value="226" <?php echo ($mobile_nation == 226) ? "selected" : ""; ?>>Burkina Faso (+226)</option>
                                    <option value="257" <?php echo ($mobile_nation == 257) ? "selected" : ""; ?>>Burundi (+257)</option>
                                    <option value="855" <?php echo ($mobile_nation == 855) ? "selected" : ""; ?>>Cambodia (+855)</option>
                                    <option value="237" <?php echo ($mobile_nation == 237) ? "selected" : ""; ?>>Cameroon (+237)</option>
                                    <option value="1" <?php echo ($mobile_nation == 1) ? "selected" : ""; ?>>Canada (+1)</option>
                                    <option value="238" <?php echo ($mobile_nation == 238) ? "selected" : ""; ?>>Cape Verde (+238)</option>
                                    <option value="1345" <?php echo ($mobile_nation == 1345) ? "selected" : ""; ?>>Cayman Islands (+1345)</option>
                                    <option value="236" <?php echo ($mobile_nation == 236) ? "selected" : ""; ?>>Central African Republic (+236)</option>
                                    <option value="235" <?php echo ($mobile_nation == 235) ? "selected" : ""; ?>>Chad (+235)</option>
                                    <option value="56" <?php echo ($mobile_nation == 56) ? "selected" : ""; ?>>Chile (+56)</option>
                                    <option value="86" <?php echo ($mobile_nation == 86) ? "selected" : ""; ?>>China (+86)</option>
                                    <option value="57" <?php echo ($mobile_nation == 57) ? "selected" : ""; ?>>Colombia (+57)</option>
                                    <option value="269" <?php echo ($mobile_nation == 269) ? "selected" : ""; ?>>Comoros (+269)</option>
                                    <option value="242" <?php echo ($mobile_nation == 242) ? "selected" : ""; ?>>Congo (+242)</option>
                                    <option value="243" <?php echo ($mobile_nation == 243) ? "selected" : ""; ?>>Congo, the Democratic Republic of the (+243)</option>
                                    <option value="682" <?php echo ($mobile_nation == 682) ? "selected" : ""; ?>>Cook Islands (+682)</option>
                                    <option value="506" <?php echo ($mobile_nation == 506) ? "selected" : ""; ?>>Costa Rica (+506)</option>
                                    <option value="225" <?php echo ($mobile_nation == 225) ? "selected" : ""; ?>>Cote d'Ivoire (+225)</option>
                                    <option value="385" <?php echo ($mobile_nation == 385) ? "selected" : ""; ?>>Croatia (+385)</option>
                                    <option value="53" <?php echo ($mobile_nation == 53) ? "selected" : ""; ?>>Cuba (+53)</option>
                                    <option value="357" <?php echo ($mobile_nation == 357) ? "selected" : ""; ?>>Cyprus (+357)</option>
                                    <option value="420" <?php echo ($mobile_nation == 420) ? "selected" : ""; ?>>Czech Republic (+420)</option>
                                    <option value="45" <?php echo ($mobile_nation == 45) ? "selected" : ""; ?>>Denmark (+45)</option>
                                    <option value="253" <?php echo ($mobile_nation == 253) ? "selected" : ""; ?>>Djibouti (+253)</option>
                                    <option value="1767" <?php echo ($mobile_nation == 1767) ? "selected" : ""; ?>>Dominica (+1767)</option>
                                    <option value="1809" <?php echo ($mobile_nation == 1809) ? "selected" : ""; ?>>Dominican Republic (+1809)</option>
                                    <option value="593" <?php echo ($mobile_nation == 593) ? "selected" : ""; ?>>Ecuador (+593)</option>
                                    <option value="20" <?php echo ($mobile_nation == 20) ? "selected" : ""; ?>>Egypt (+20)</option>
                                    <option value="503" <?php echo ($mobile_nation == 503) ? "selected" : ""; ?>>El Salvador (+503)</option>
                                    <option value="240" <?php echo ($mobile_nation == 240) ? "selected" : ""; ?>>Equatorial Guinea (+240)</option>
                                    <option value="291" <?php echo ($mobile_nation == 291) ? "selected" : ""; ?>>Eritrea (+291)</option>
                                    <option value="372" <?php echo ($mobile_nation == 372) ? "selected" : ""; ?>>Estonia (+372)</option>
                                    <option value="251" <?php echo ($mobile_nation == 251) ? "selected" : ""; ?>>Ethiopia (+251)</option>
                                    <option value="500" <?php echo ($mobile_nation == 500) ? "selected" : ""; ?>>Falkland Islands (Malvinas) (+500)</option>
                                    <option value="298" <?php echo ($mobile_nation == 298) ? "selected" : ""; ?>>Faroe Islands (+298)</option>
                                    <option value="679" <?php echo ($mobile_nation == 679) ? "selected" : ""; ?>>Fiji (+679)</option>
                                    <option value="358" <?php echo ($mobile_nation == 358) ? "selected" : ""; ?>>Finland (+358)</option>
                                    <option value="33" <?php echo ($mobile_nation == 33) ? "selected" : ""; ?>>France (+33)</option>
                                    <option value="594" <?php echo ($mobile_nation == 594) ? "selected" : ""; ?>>French Guiana (+594)</option>
                                    <option value="689" <?php echo ($mobile_nation == 689) ? "selected" : ""; ?>>French Polynesia (+689)</option>
                                    <option value="241" <?php echo ($mobile_nation == 241) ? "selected" : ""; ?>>Gabon (+241)</option>
                                    <option value="220" <?php echo ($mobile_nation == 220) ? "selected" : ""; ?>>Gambia (+220)</option>
                                    <option value="995" <?php echo ($mobile_nation == 995) ? "selected" : ""; ?>>Georgia (+995)</option>
                                    <option value="49" <?php echo ($mobile_nation == 49) ? "selected" : ""; ?>>Germany (+49)</option>
                                    <option value="233" <?php echo ($mobile_nation == 233) ? "selected" : ""; ?>>Ghana (+233)</option>
                                    <option value="350" <?php echo ($mobile_nation == 350) ? "selected" : ""; ?>>Gibraltar (+350)</option>
                                    <option value="30" <?php echo ($mobile_nation == 30) ? "selected" : ""; ?>>Greece (+30)</option>
                                    <option value="299" <?php echo ($mobile_nation == 299) ? "selected" : ""; ?>>Greenland (+299)</option>
                                    <option value="1473" <?php echo ($mobile_nation == 1473) ? "selected" : ""; ?>>Grenada (+1473)</option>
                                    <option value="590" <?php echo ($mobile_nation == 590) ? "selected" : ""; ?>>Guadeloupe (+590)</option>
                                    <option value="1671" <?php echo ($mobile_nation == 1671) ? "selected" : ""; ?>>Guam (+1671)</option>
                                    <option value="502" <?php echo ($mobile_nation == 502) ? "selected" : ""; ?>>Guatemala (+502)</option>
                                    <option value="224" <?php echo ($mobile_nation == 224) ? "selected" : ""; ?>>Guinea (+224)</option>
                                    <option value="245" <?php echo ($mobile_nation == 245) ? "selected" : ""; ?>>Guinea-Bissau (+245)</option>
                                    <option value="592" <?php echo ($mobile_nation == 592) ? "selected" : ""; ?>>Guyana (+592)</option>
                                    <option value="509" <?php echo ($mobile_nation == 509) ? "selected" : ""; ?>>Haiti (+509)</option>
                                    <option value="379" <?php echo ($mobile_nation == 379) ? "selected" : ""; ?>>Holy See (Vatican City State) (+379)</option>
                                    <option value="504" <?php echo ($mobile_nation == 504) ? "selected" : ""; ?>>Honduras (+504)</option>
                                    <option value="852" <?php echo ($mobile_nation == 852) ? "selected" : ""; ?>>Hong Kong (+852)</option>
                                    <option value="36" <?php echo ($mobile_nation == 36) ? "selected" : ""; ?>>Hungary (+36)</option>
                                    <option value="354" <?php echo ($mobile_nation == 354) ? "selected" : ""; ?>>Iceland (+354)</option>
                                    <option value="91" <?php echo ($mobile_nation == 91) ? "selected" : ""; ?>>India (+91)</option>
                                    <option value="62" <?php echo ($mobile_nation == 62) ? "selected" : ""; ?>>Indonesia (+62)</option>
                                    <option value="98" <?php echo ($mobile_nation == 98) ? "selected" : ""; ?>>Iran, Islamic Republic of (+98)</option>
                                    <option value="964" <?php echo ($mobile_nation == 964) ? "selected" : ""; ?>>Iraq (+964)</option>
                                    <option value="353" <?php echo ($mobile_nation == 353) ? "selected" : ""; ?>>Ireland (+353)</option>
                                    <option value="972" <?php echo ($mobile_nation == 972) ? "selected" : ""; ?>>Israel (+972)</option>
                                    <option value="39" <?php echo ($mobile_nation == 39) ? "selected" : ""; ?>>Italy (+39)</option>
                                    <option value="1876" <?php echo ($mobile_nation == 1876) ? "selected" : ""; ?>>Jamaica (+1876)</option>
                                    <option value="81" <?php echo ($mobile_nation == 81) ? "selected" : ""; ?>>Japan (+81)</option>
                                    <option value="962" <?php echo ($mobile_nation == 962) ? "selected" : ""; ?>>Jordan (+962)</option>
                                    <option value="7" <?php echo ($mobile_nation == 7) ? "selected" : ""; ?>>Kazakhstan (+7)</option>
                                    <option value="254" <?php echo ($mobile_nation == 254) ? "selected" : ""; ?>>Kenya (+254)</option>
                                    <option value="686" <?php echo ($mobile_nation == 686) ? "selected" : ""; ?>>Kiribati (+686)</option>
                                    <option value="850" <?php echo ($mobile_nation == 850) ? "selected" : ""; ?>>Korea, Democratic People's Republic of (+850)</option>
                                    <option value="82" <?php echo ($mobile_nation == 82) ? "selected" : ""; ?>>Korea, Republic of (+82)</option>
                                    <option value="965" <?php echo ($mobile_nation == 965) ? "selected" : ""; ?>>Kuwait (+965)</option>
                                    <option value="996" <?php echo ($mobile_nation == 996) ? "selected" : ""; ?>>Kyrgyzstan (+996)</option>
                                    <option value="856" <?php echo ($mobile_nation == 856) ? "selected" : ""; ?>>Lao People's Democratic Republic (+856)</option>
                                    <option value="371" <?php echo ($mobile_nation == 371) ? "selected" : ""; ?>>Latvia (+371)</option>
                                    <option value="961" <?php echo ($mobile_nation == 961) ? "selected" : ""; ?>>Lebanon (+961)</option>
                                    <option value="266" <?php echo ($mobile_nation == 266) ? "selected" : ""; ?>>Lesotho (+266)</option>
                                    <option value="231" <?php echo ($mobile_nation == 231) ? "selected" : ""; ?>>Liberia (+231)</option>
                                    <option value="218" <?php echo ($mobile_nation == 218) ? "selected" : ""; ?>>Libyan Arab Jamahiriya (+218)</option>
                                    <option value="423" <?php echo ($mobile_nation == 423) ? "selected" : ""; ?>>Liechtenstein (+423)</option>
                                    <option value="370" <?php echo ($mobile_nation == 370) ? "selected" : ""; ?>>Lithuania (+370)</option>
                                    <option value="352" <?php echo ($mobile_nation == 352) ? "selected" : ""; ?>>Luxembourg (+352)</option>
                                    <option value="853" <?php echo ($mobile_nation == 853) ? "selected" : ""; ?>>Macao (+853)</option>
                                    <option value="389" <?php echo ($mobile_nation == 389) ? "selected" : ""; ?>>Macedonia, the former Yugoslav Republic of (+389)</option>
                                    <option value="261" <?php echo ($mobile_nation == 261) ? "selected" : ""; ?>>Madagascar (+261)</option>
                                    <option value="265" <?php echo ($mobile_nation == 265) ? "selected" : ""; ?>>Malawi (+265)</option>
                                    <option value="60" <?php echo ($mobile_nation == 60) ? "selected" : ""; ?>>Malaysia (+60)</option>
                                    <option value="960" <?php echo ($mobile_nation == 960) ? "selected" : ""; ?>>Maldives (+960)</option>
                                    <option value="223" <?php echo ($mobile_nation == 223) ? "selected" : ""; ?>>Mali (+223)</option>
                                    <option value="356" <?php echo ($mobile_nation == 356) ? "selected" : ""; ?>>Malta (+356)</option>
                                    <option value="692" <?php echo ($mobile_nation == 692) ? "selected" : ""; ?>>Marshall Islands (+692)</option>
                                    <option value="596" <?php echo ($mobile_nation == 596) ? "selected" : ""; ?>>Martinique (+596)</option>
                                    <option value="222" <?php echo ($mobile_nation == 222) ? "selected" : ""; ?>>Mauritania (+222)</option>
                                    <option value="230" <?php echo ($mobile_nation == 230) ? "selected" : ""; ?>>Mauritius (+230)</option>
                                    <option value="262" <?php echo ($mobile_nation == 262) ? "selected" : ""; ?>>Mayotte (+262)</option>
                                    <option value="52" <?php echo ($mobile_nation == 52) ? "selected" : ""; ?>>Mexico (+52)</option>
                                    <option value="691" <?php echo ($mobile_nation == 691) ? "selected" : ""; ?>>Micronesia, Federated States of (+691)</option>
                                    <option value="373" <?php echo ($mobile_nation == 373) ? "selected" : ""; ?>>Moldova, Republic of (+373)</option>
                                    <option value="377" <?php echo ($mobile_nation == 377) ? "selected" : ""; ?>>Monaco (+377)</option>
                                    <option value="976" <?php echo ($mobile_nation == 976) ? "selected" : ""; ?>>Mongolia (+976)</option>
                                    <option value="382" <?php echo ($mobile_nation == 382) ? "selected" : ""; ?>>Montenegro (+382)</option>
                                    <option value="1664" <?php echo ($mobile_nation == 1664) ? "selected" : ""; ?>>Montserrat (+1664)</option>
                                    <option value="212" <?php echo ($mobile_nation == 212) ? "selected" : ""; ?>>Morocco (+212)</option>
                                    <option value="258" <?php echo ($mobile_nation == 258) ? "selected" : ""; ?>>Mozambique (+258)</option>
                                    <option value="95" <?php echo ($mobile_nation == 95) ? "selected" : ""; ?>>Myanmar (+95)</option>
                                    <option value="264" <?php echo ($mobile_nation == 264) ? "selected" : ""; ?>>Namibia (+264)</option>
                                    <option value="674" <?php echo ($mobile_nation == 674) ? "selected" : ""; ?>>Nauru (+674)</option>
                                    <option value="977" <?php echo ($mobile_nation == 977) ? "selected" : ""; ?>>Nepal (+977)</option>
                                    <option value="31" <?php echo ($mobile_nation == 31) ? "selected" : ""; ?>>Netherlands (+31)</option>
                                    <option value="599" <?php echo ($mobile_nation == 599) ? "selected" : ""; ?>>Netherlands Antilles (+599)</option>
                                    <option value="687" <?php echo ($mobile_nation == 687) ? "selected" : ""; ?>>New Caledonia (+687)</option>
                                    <option value="64" <?php echo ($mobile_nation == 64) ? "selected" : ""; ?>>New Zealand (+64)</option>
                                    <option value="505" <?php echo ($mobile_nation == 505) ? "selected" : ""; ?>>Nicaragua (+505)</option>
                                    <option value="227" <?php echo ($mobile_nation == 227) ? "selected" : ""; ?>>Niger (+227)</option>
                                    <option value="234" <?php echo ($mobile_nation == 234) ? "selected" : ""; ?>>Nigeria (+234)</option>
                                    <option value="683" <?php echo ($mobile_nation == 683) ? "selected" : ""; ?>>Niue (+683)</option>
                                    <option value="672" <?php echo ($mobile_nation == 672) ? "selected" : ""; ?>>Norfolk Island (+672)</option>
                                    <option value="1670" <?php echo ($mobile_nation == 1670) ? "selected" : ""; ?>>Northern Mariana Islands (+1670)</option>
                                    <option value="47" <?php echo ($mobile_nation == 47) ? "selected" : ""; ?>>Norway (+47)</option>
                                    <option value="968" <?php echo ($mobile_nation == 968) ? "selected" : ""; ?>>Oman (+968)</option>
                                    <option value="92" <?php echo ($mobile_nation == 92) ? "selected" : ""; ?>>Pakistan (+92)</option>
                                    <option value="680" <?php echo ($mobile_nation == 680) ? "selected" : ""; ?>>Palau (+680)</option>
                                    <option value="507" <?php echo ($mobile_nation == 507) ? "selected" : ""; ?>>Panama (+507)</option>
                                    <option value="675" <?php echo ($mobile_nation == 675) ? "selected" : ""; ?>>Papua New Guinea (+675)</option>
                                    <option value="595" <?php echo ($mobile_nation == 595) ? "selected" : ""; ?>>Paraguay (+595)</option>
                                    <option value="51" <?php echo ($mobile_nation == 51) ? "selected" : ""; ?>>Peru (+51)</option>
                                    <option value="63" <?php echo ($mobile_nation == 63) ? "selected" : ""; ?>>Philippines (+63)</option>
                                    <option value="870" <?php echo ($mobile_nation == 870) ? "selected" : ""; ?>>Pitcairn (+870)</option>
                                    <option value="48" <?php echo ($mobile_nation == 48) ? "selected" : ""; ?>>Poland (+48)</option>
                                    <option value="351" <?php echo ($mobile_nation == 351) ? "selected" : ""; ?>>Portugal (+351)</option>
                                    <option value="1" <?php echo ($mobile_nation == 1) ? "selected" : ""; ?>>Puerto Rico (+1)</option>
                                    <option value="974" <?php echo ($mobile_nation == 974) ? "selected" : ""; ?>>Qatar (+974)</option>
                                    <option value="262" <?php echo ($mobile_nation == 262) ? "selected" : ""; ?>>Reunion (+262)</option>
                                    <option value="40" <?php echo ($mobile_nation == 40) ? "selected" : ""; ?>>Romania (+40)</option>
                                    <option value="7" <?php echo ($mobile_nation == 7) ? "selected" : ""; ?>>Russian Federation (+7)</option>
                                    <option value="250" <?php echo ($mobile_nation == 250) ? "selected" : ""; ?>>Rwanda (+250)</option>
                                    <option value="290" <?php echo ($mobile_nation == 290) ? "selected" : ""; ?>>Saint Helena, Ascension and Tristan da Cunha (+290)</option>
                                    <option value="1869" <?php echo ($mobile_nation == 1869) ? "selected" : ""; ?>>Saint Kitts and Nevis (+1869)</option>
                                    <option value="1758" <?php echo ($mobile_nation == 1758) ? "selected" : ""; ?>>Saint Lucia (+1758)</option>
                                    <option value="508" <?php echo ($mobile_nation == 508) ? "selected" : ""; ?>>Saint Pierre and Miquelon (+508)</option>
                                    <option value="1784" <?php echo ($mobile_nation == 1784) ? "selected" : ""; ?>>Saint Vincent and the Grenadines (+1784)</option>
                                    <option value="685" <?php echo ($mobile_nation == 685) ? "selected" : ""; ?>>Samoa (+685)</option>
                                    <option value="378" <?php echo ($mobile_nation == 378) ? "selected" : ""; ?>>San Marino (+378)</option>
                                    <option value="239" <?php echo ($mobile_nation == 239) ? "selected" : ""; ?>>Sao Tome and Principe (+239)</option>
                                    <option value="966" <?php echo ($mobile_nation == 966) ? "selected" : ""; ?>>Saudi Arabia (+966)</option>
                                    <option value="221" <?php echo ($mobile_nation == 221) ? "selected" : ""; ?>>Senegal (+221)</option>
                                    <option value="381" <?php echo ($mobile_nation == 381) ? "selected" : ""; ?>>Serbia (+381)</option>
                                    <option value="248" <?php echo ($mobile_nation == 248) ? "selected" : ""; ?>>Seychelles (+248)</option>
                                    <option value="232" <?php echo ($mobile_nation == 232) ? "selected" : ""; ?>>Sierra Leone (+232)</option>
                                    <option value="65" <?php echo ($mobile_nation == 65) ? "selected" : ""; ?>>Singapore (+65)</option>
                                    <option value="421" <?php echo ($mobile_nation == 421) ? "selected" : ""; ?>>Slovakia (+421)</option>
                                    <option value="386" <?php echo ($mobile_nation == 386) ? "selected" : ""; ?>>Slovenia (+386)</option>
                                    <option value="677" <?php echo ($mobile_nation == 677) ? "selected" : ""; ?>>Solomon Islands (+677)</option>
                                    <option value="252" <?php echo ($mobile_nation == 252) ? "selected" : ""; ?>>Somalia (+252)</option>
                                    <option value="27" <?php echo ($mobile_nation == 27) ? "selected" : ""; ?>>South Africa (+27)</option>
                                    <option value="34" <?php echo ($mobile_nation == 34) ? "selected" : ""; ?>>Spain (+34)</option>
                                    <option value="94" <?php echo ($mobile_nation == 94) ? "selected" : ""; ?>>Sri Lanka (+94)</option>
                                    <option value="249" <?php echo ($mobile_nation == 249) ? "selected" : ""; ?>>Sudan (+249)</option>
                                    <option value="597" <?php echo ($mobile_nation == 597) ? "selected" : ""; ?>>Suriname (+597)</option>
                                    <option value="268" <?php echo ($mobile_nation == 268) ? "selected" : ""; ?>>Swaziland (+268)</option>
                                    <option value="46" <?php echo ($mobile_nation == 46) ? "selected" : ""; ?>>Sweden (+46)</option>
                                    <option value="41" <?php echo ($mobile_nation == 41) ? "selected" : ""; ?>>Switzerland (+41)</option>
                                    <option value="963" <?php echo ($mobile_nation == 963) ? "selected" : ""; ?>>Syrian Arab Republic (+963)</option>
                                    <option value="886" <?php echo ($mobile_nation == 886) ? "selected" : ""; ?>>Taiwan (+886)</option>
                                    <option value="992" <?php echo ($mobile_nation == 992) ? "selected" : ""; ?>>Tajikistan (+992)</option>
                                    <option value="255" <?php echo ($mobile_nation == 255) ? "selected" : ""; ?>>Tanzania, United Republic of (+255)</option>
                                    <option value="66" <?php echo ($mobile_nation == 66) ? "selected" : ""; ?>>Thailand (+66)</option>
                                    <option value="228" <?php echo ($mobile_nation == 228) ? "selected" : ""; ?>>Togo (+228)</option>
                                    <option value="690" <?php echo ($mobile_nation == 690) ? "selected" : ""; ?>>Tokelau (+690)</option>
                                    <option value="676" <?php echo ($mobile_nation == 676) ? "selected" : ""; ?>>Tonga (+676)</option>
                                    <option value="1868" <?php echo ($mobile_nation == 1868) ? "selected" : ""; ?>>Trinidad and Tobago (+1868)</option>
                                    <option value="216" <?php echo ($mobile_nation == 216) ? "selected" : ""; ?>>Tunisia (+216)</option>
                                    <option value="90" <?php echo ($mobile_nation == 90) ? "selected" : ""; ?>>Turkey (+90)</option>
                                    <option value="993" <?php echo ($mobile_nation == 993) ? "selected" : ""; ?>>Turkmenistan (+993)</option>
                                    <option value="1649" <?php echo ($mobile_nation == 1649) ? "selected" : ""; ?>>Turks and Caicos Islands (+1649)</option>
                                    <option value="688" <?php echo ($mobile_nation == 688) ? "selected" : ""; ?>>Tuvalu (+688)</option>
                                    <option value="256" <?php echo ($mobile_nation == 256) ? "selected" : ""; ?>>Uganda (+256)</option>
                                    <option value="380" <?php echo ($mobile_nation == 380) ? "selected" : ""; ?>>Ukraine (+380)</option>
                                    <option value="971" <?php echo ($mobile_nation == 971) ? "selected" : ""; ?>>United Arab Emirates (+971)</option>
                                    <option value="44" <?php echo ($mobile_nation == 44) ? "selected" : ""; ?>>United Kingdom (+44)</option>
                                    <option value="1" <?php echo ($mobile_nation == 1) ? "selected" : ""; ?>>United States (+1)</option>
                                    <option value="598" <?php echo ($mobile_nation == 598) ? "selected" : ""; ?>>Uruguay (+598)</option>
                                    <option value="998" <?php echo ($mobile_nation == 998) ? "selected" : ""; ?>>Uzbekistan (+998)</option>
                                    <option value="678" <?php echo ($mobile_nation == 678) ? "selected" : ""; ?>>Vanuatu (+678)</option>
                                    <option value="58" <?php echo ($mobile_nation == 58) ? "selected" : ""; ?>>Venezuela, Bolivarian Republic of (+58)</option>
                                    <option value="84" <?php echo ($mobile_nation == 84) ? "selected" : ""; ?>>Viet Nam (+84)</option>
                                    <option value="1284" <?php echo ($mobile_nation == 1284) ? "selected" : ""; ?>>Virgin Islands, British (+1284)</option>
                                    <option value="1340" <?php echo ($mobile_nation == 1340) ? "selected" : ""; ?>>Virgin Islands, U.S. (+1340)</option>
                                    <option value="681" <?php echo ($mobile_nation == 681) ? "selected" : ""; ?>>Wallis and Futuna (+681)</option>
                                    <option value="967" <?php echo ($mobile_nation == 967) ? "selected" : ""; ?>>Yemen (+967)</option>
                                    <option value="260" <?php echo ($mobile_nation == 260) ? "selected" : ""; ?>>Zambia (+260)</option>
                                    <option value="263" <?php echo ($mobile_nation == 263) ? "selected" : ""; ?>>Zimbabwe (+263)</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"></td>
                            <td><input name="mobile" type="text" id="mobile" size="40" value="<?php echo $mobile; ?>" /></td>
                        </tr>
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
                            <td><input name="address" type="text" id="address" size="40" value="<?php echo $address; ?>" /></td>
                        </tr>
                        <tr>
                            <td width="150"></td>
                            <td height="30"><input name="savenews" type="submit" id="savenews" value=" 送 出 " />
                                &nbsp;&nbsp;&nbsp;
                                <input name="" type="reset" value=" 重 設 " /></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
