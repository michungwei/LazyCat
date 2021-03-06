<?php
include_once("_config.php");

$id = get("id");

if($id == 0){
	script("資料傳輸不正確", "index.php".$query_str);
}

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$sql = "SELECT * 
		FROM $table_lookbookpic 
		WHERE $id_column = '$id'";
$row = $db -> query_first($sql);

if($row){
	$lookbook_id = $row["lookbookpic_lookbook_id"];
	$pic = $row["lookbookpic_pic"];
	$is_show = $row["lookbookpic_is_show"];
	$update_time = $row["lookbookpic_update_time"];
}else{
 	script("資料不存在");
}

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
<script src="../../ui/ckeditor/ckeditor.js"></script>
<script src="../../scripts/public.js"></script>
<script src="../../scripts/function.js"></script>
<script>
$(document).ready(function(){ 
	$("form").submit(function(){
		var re = true;
		err_msg = '';
		if(re){re = isnull("type", "宣傳冊", 0, 1, 10);}
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
                <p align="left">修改商品</p>
            </div>
            <div class="listshow">
                <form action="edit_save.php?<?php echo $query_str; ?>" method="post" enctype="multipart/form-data" name="form" id="form">
                    <input type="hidden" name="cid" value="<?php echo $id; ?>">
                    <table width="850" border="0" cellpadding="0" cellspacing="3">
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">最後修改時間</h4></td>
                            <td><?php echo $update_time; ?></td>
                            <td rowspan="15" valign="top"><?php if($pic != ""){echo '<a href="'.$file_path.$pic.'" target="_blank"><img src="'.$file_path."m".$pic.'" width="120"></a>';} ?>
                                <br /></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">是否顯示</h4></td>
                            <td><input type="checkbox" name="is_show" id="is_show" <?php echo ($is_show == 1) ? "checked" : ""; ?> value="1" />
                                顯示 </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">分類</h4></td>
                            <td><select name="type" id="type">
                                    <?php 
									foreach($rows_type as $row_type){
									?>
									<option value="<?php echo $row_type["lookbook_id"]; ?>" <?php echo ($lookbook_id == $row_type["lookbook_id"]) ? "selected" : ""; ?>><?php echo $row_type["lookbook_title"]; ?></option>
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
                                    (請上傳符合 <?php echo $lookbook_pic_w; ?> x <?php echo $lookbook_pic_h; ?> 尺寸的圖片)<!--<input name="pic1_del" type="checkbox" id="pic1_del" value="1">&nbsp;刪除圖片1--></p></td>
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
