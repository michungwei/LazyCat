<?php
include_once("_config.php");

$id = get("id");
if($id == 0){
	script("資料傳輸不正確", "index.php");
}

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$sql = "SELECT *
		FROM $table_subbanner 
		WHERE $id_column = '$id'";
$row = $db -> query_first($sql);

if($row){
	$pic = $row["subbanner_pic"];
	$pic_link = $row["subbanner_pic_link"];
	$sub_page = $row["subbanner_page"];
	$update_time = $row["subbanner_update_time"];
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
		//if(re){re = isnull("type", "分類", 0, 1, 2);}
		//if(re){re = isnull("name", "名稱", 0, 1, 20);}
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
                <p align="left">修改內頁BANNER</p>
            </div>
            <div class="listshow">
                <form action="edit_save.php?<?php echo $query_str; ?>" method="post" enctype="multipart/form-data" name="form" id="form">
                    <input type="hidden" name="cid" value="<?php echo $id; ?>">
                    <input name="sub_page" type="hidden" id="sub_page" value="<?php echo $sub_page; ?>">
                    <table width="850" border="0" cellpadding="0" cellspacing="3">
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">最後修改時間</h4></td>
                            <td><?php echo $update_time; ?></td>
                            <td rowspan="6" valign="top"><?php if($pic != ""){echo '<a href="'.$file_path.$pic.'" target="_blank"><img src="'.$file_path."m".$pic.'" width="120"></a>';} ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">所屬內頁</h4></td>
                            <td><?php echo $ary_page[$page]; ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片</h4></td>
                            <td><p>
                                    <input type="file" name="pic" id="pic" />
                                    <br />
                                    (請上傳符合 <?php echo $sub_page == 6 ? $subbannerw_pic_w : $subbanner_pic_w; ?> x <?php echo $sub_page == 6 ? $subbannerw_pic_h : $subbanner_pic_h; ?> 尺寸的圖片)</p></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片連結</h4></td>
                            <td><input type="text" name="piclink" id="piclink" size="50" value="<?php echo $pic_link; ?>"/>
                                <br />
                                (請輸入完整的連結網址,如http://www.lazycat.com.tw)</td>
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
