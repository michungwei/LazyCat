<?php
include_once("_config.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$sql_type = "SELECT *
			 FROM $table_onlinetype 
			 WHERE onlinetype_is_show = 1
			 ORDER BY onlinetype_ind DESC";
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
		$('textarea.ckeditor').each(function () {
			var $textarea = $(this);
			$textarea.val(CKEDITOR.instances[$textarea.attr('name')].getData());
        });
		
		var re = true;
		err_msg = '';
		if(re){re = isnull("title", "標題", 0, 1, 200);}
		if(re){re = isnull("price", "價格", 0, 1, 200);}
		if(re){re = isnull("link", "連結", 0, 1, 255);}
		if(re){re = isnull("content", "內容", 0, 1, 99999999);}
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
                <p align="left">新增線上課程</p>
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
                            <td width="150" valign="top"><h4 class="input-text-title">分類</h4></td>
                            <td><select name="type" id="type">
                                    <?php
										foreach($rows_type as $row_type){
									?>
                                    <option value="<?php echo $row_type["onlinetype_id"]; ?>" <?php echo ($type == $row_type["onlinetype_id"]) ? "selected" : ""; ?>><?php echo $row_type["onlinetype_name_en"]." / ".$row_type["onlinetype_name_tw"]; ?></option>
                                    <?php
										}
									?>
                                </select></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">標題</h4></td>
                            <td><input type="text" name="title" id="title" size="50" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">價格</h4></td>
                            <td><input type="text" name="price" id="price" size="50" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">連結</h4></td>
                            <td><input type="text" name="link" id="link" size="50" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">內容</h4></td>
                            <td><textarea name="content" id="content" class="ckeditor"></textarea></td>
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
