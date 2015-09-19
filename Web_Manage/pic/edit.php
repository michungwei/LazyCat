<?php
include_once("_config.php");

$id = get("id");
if($id == 0){
	script("資料傳輸不正確", "index.php");
}

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$sql = "SELECT *
		FROM $table_pic 
		WHERE $id_column = '$id'";
$row = $db -> query_first($sql);

if($row){
	$name_tw = $row["pic_name_tw"];
	$name_en= $row["pic_name_en"];
	$link= $row["pic_link"];
	$pic = $row["pic_pic"];
	$is_show = $row["pic_is_show"];
	$create_time = $row["pic_create_time"];
	$update_time = $row["pic_update_time"];
}else{
 	script("資料不存在");
}

$db -> close();
?>
<!doctype html>
<html>
<head>
<title>Untitled Document</title>
<meta charset="utf-8" />
<link href="../css/admin_style_gray.css" rel="stylesheet" />
<script src="../../scripts/jquery-1.6.1rc1.min.js"></script>
<script src="../../scripts/public.js"></script>
<script src="../../scripts/function.js"></script>
<script>
$(document).ready(function(){
	$("form").submit(function(){
		var re = true;
		err_msg = '';
		if(re){re = isnull("name_tw", "名稱(TW)", 0, 1, 100);}
		if(re){re = isnull("name_en", "名稱(EN)", 0, 1, 100);}
		if(re){re = isnull("link", "連結", 0, 1, 255);}
		//if(re){re = isnull("pic", "圖片", 0, 1, 9999);}
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
                <p align="left">修改首頁圖片</p>
            </div>
            <div class="listshow">
                <form action="edit_save.php?<?php echo $query_str; ?>" method="post" enctype="multipart/form-data" name="form" id="form">
                    <input type="hidden" name="cid" value="<?php echo $id; ?>">
                    <table width="850" border="0" cellpadding="0" cellspacing="3">
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">最後更新時間</h4></td>
                            <td><?php echo $update_time; ?></td>
                            <td rowspan="6" valign="top"><?php if($pic != ""){echo '<a href="'.$file_path.$pic.'" target="_blank"><img src="'.$file_path."".$pic.'" width="100"></a>';} ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">是否顯示</h4></td>
                            <td><input type="checkbox" name="is_show" id="is_show" <?php echo ($is_show == 1) ? "checked" : ""; ?> value="1" />
                                顯示 </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">名稱(TW)</h4></td>
                            <td><input type="text" name="name_tw" id="name_tw" size="50" value="<?php echo $name_tw; ?>"/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">名稱(EN)</h4></td>
                            <td><input type="text" name="name_en" id="name_en" size="50" value="<?php echo $name_en; ?>"/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">連接</h4></td>
                            <td><input type="text" name="link" id="link" size="50" value="<?php echo $link; ?>"/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片</h4></td>
                            <td><p>
                                    <input type="file" name="pic" id="pic" />
                                    <br />
                                    (請上傳符合 <?php echo $pic_pic_w; ?> x <?php echo $pic_pic_h; ?> 尺寸的圖片)</p></td>
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
