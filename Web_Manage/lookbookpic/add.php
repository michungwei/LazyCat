<?php
include_once("_config.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$sql_type = "SELECT *
			 FROM $table_lookbook 
			 WHERE lookbook_is_show = 1
			 ORDER BY lookbook_ind DESC";
$rows_type = $db -> fetch_all_array($sql_type);

$db -> close();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Untitled Document</title>
<link href="../css/admin_style_gray.css" rel="stylesheet" />
<script src="../../scripts/jquery-1.6.1rc1.min.js"></script>
<script src="../../scripts/public.js"></script>
<script src="../../ui/ckeditor/ckeditor.js"></script>
<script src="../../scripts/function.js"></script>
<script>
$(document).ready(function(){ 
	$("form").submit(function(){
		var re = true;
		err_msg = '';
		if(re){re = isnull("type", "宣傳冊", 0, 1, 10);}
		if(re){re = isnull("pic", "圖片", 0, 1, 9999);}
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
        <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle; ?> >&nbsp;&nbsp;新增</h2>
        <div class="accordion ">
            <div class="tableheader">
                <div class="handlediv"></div>
                <p align="left">新增宣傳冊圖片</p>
            </div>
            <div class="listshow">
                <form action="save.php?<?php echo $query_str; ?>" method="post" enctype="multipart/form-data" name="form" id="form">
                    <table width="850" border="0" cellpadding="0" cellspacing="3">
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">是否顯示</h4></td>
                            <td><input type="checkbox" name="is_show" id="is_show" checked value="1"/>
                                &nbsp;
                                顯示 </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">宣傳冊</h4></td>
                            <td><select name="type" id="type">
                                    <option value="">--請選擇--</option>
                                    <?php 
									foreach($rows_type as $row_type){
									?>
									<option value="<?php echo $row_type["lookbook_id"]; ?>"><?php echo $row_type["lookbook_title"]; ?></option>
									<?php
										}
									?>
                                </select></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片</h4></td>
                            <td><p>
                                    <input type="file" name="pic" id="pic" />
                                    <br />
                            (請上傳符合 <?php echo $lookbook_pic_w; ?> x <?php echo $lookbook_pic_h; ?> 尺寸的圖片)</p></td>
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
