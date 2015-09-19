<?php
include_once("_config.php");
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
        <h2> <img src="../images/admintitle.png" />&nbsp;&nbsp;<?php echo $mtitle; ?> >&nbsp;&nbsp;新增</h2>
        <div class="accordion ">
            <div class="tableheader">
                <div class="handlediv"></div>
                <p align="left">新增BANNER</p>
            </div>
            <div class="listshow">
                <form action="save.php?<?php echo $query_str; ?>" method="post" enctype="multipart/form-data" name="form" id="form">
                    <table width="850" border="0" cellpadding="0" cellspacing="3">
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">所屬內頁</h4></td>
                            <td>
                            	<select name="page" id="page">
                            		<?php
									foreach($ary_page as $key => $val){
									?>
                                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                    <?php
									}
									?>
                            	</select>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片</h4></td>
                            <td><p>
                                    <input type="file" name="pic" id="pic" />
                                    <br />
                                    (請上傳符合 <?php echo $subbanner_pic_w; ?> x <?php echo $subbanner_pic_h; ?> 尺寸的圖片)</p></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片連結</h4></td>
                            <td><input type="text" name="piclink" id="piclink" size="50" value=""/>
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
