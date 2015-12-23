<?php
include_once("_config.php");
include_once($inc_path."lib/_banner.php");

$id = get("id");

if($id == 0){
	script("資料傳輸不正確", "index.php".$query_str);
}

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$sql = "SELECT * 
		FROM $table_discharge 
		WHERE $id_column = '$id'";
$row = $db -> query_first($sql);
$rows_pic = Banner::getPic();

if($row){
	$name = $row["discharge_name"];
    $enable = $row["discharge_enable"];
    $forever = $row["discharge_forever"];
    $start_time = $row["discharge_start_time"];
    $end_time = $row["discharge_end_time"];
}else{
 	script("資料不存在");
}

$db -> close();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Untitled Document</title>
<link href="../css/admin_style_gray.css" rel="stylesheet" />
<link rel="stylesheet" href="../../ui/colorpicker/css/colorpicker.css" type="text/css" />
<link href="../../ui/uploadify/uploadify.css" rel="stylesheet"/>

<script src="../../scripts/jquery-1.6.1rc1.min.js"></script>
<script src="../../ui/ckeditor/ckeditor.js"></script>
<script src="../../scripts/public.js"></script>
<script src="../../scripts/function.js"></script>

<!--jquery ui-->
<script type="text/javascript" src="../../ui/jquery-ui-1.11.0/jquery-ui.min.js"></script>
<link href="../../ui/jquery-ui-1.11.0/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="../../ui/jquery-ui-1.11.0/jquery-ui.structure.min.css" rel="stylesheet" type="text/css">
<link href="../../ui/jquery-ui-1.11.0/jquery-ui.theme.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../ui/jquery-ui-1.11.0/timePlugin/jquery-ui-timepicker-addon.js"></script>
<link href="../../ui/jquery-ui-1.11.0/timePlugin/jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css">

<script>
$(function() {
/*$( "#news_upday" ).datepicker();*/
    $('#start_time').datetimepicker();
    $('#end_time').datetimepicker();
});
</script>

<script>
$(document).ready(function(){ 
	$("form").submit(function(){
		$('textarea.ckeditor').each(function () {
			var $textarea = $(this);
			$textarea.val(CKEDITOR.instances[$textarea.attr('name')].getData());
        });
		
		var re = true;
		err_msg = '';
        if(re){re = isnull("name", "名稱", 0, 1, 100);}
        if(re){re = isnull("start_time", "開始時間", 0, 1, 100);}
        if(re){re = isnull("end_time", "結束時間", 0, 1, 100);}		
        if (!re){
			alert(err_msg)
			return false;
		}
		return true;
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
                <p align="left">修改購物抵用金</p>
            </div>
            <div class="listshow">
                <form action="edit_save.php?<?php echo $query_str; ?>" method="post" enctype="multipart/form-data" name="form" id="form">
                    <input type="hidden" name="cid" value="<?php echo $id; ?>">
                    <input name="old_stock" type="hidden" id="old_stock" value="<?php echo $stock; ?>">
                    <table width="850" border="0" cellpadding="0" cellspacing="3">
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">是否啟用</h4></td>
                            <td><input type="checkbox" name="enable" id="enable" <?php if($enable) echo "checked";?> value="1"/>
                                &nbsp;
                                啟用 </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">是否永久生效</h4></td>
                            <td><input type="checkbox" name="forever" id="forever" <?php if($forever) echo "checked";?> value="1"/>
                                &nbsp;
                                永久 </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">名稱</h4></td>
                            <td><input type="text" name="name" id="name" size="50" value="<?php echo $name; ?>"/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">開始時間</h4></td>
                            <td width="762"><input name="start_time" type="text" id="start_time" maxlength="10" readonly size="39" value="<?php echo $start_time; ?>"/></td>                        
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">結束時間</h4></td>
                            <td width="762"><input name="end_time" type="text" id="end_time" maxlength="10" readonly size="39" value="<?php echo $end_time; ?>"/></td>
                        </tr>
                        <tr>
                            <td></td>
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
