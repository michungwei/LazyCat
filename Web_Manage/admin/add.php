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
		if(re){re = isnull("account", "帳號", 0, 1, 30);}
		if(re){re = isexist($("#account").val(), "帳號", "check.php");}
		if(re){re = isnull("password", "密碼", 0, 1, 50);}
		if(re){re = isnull("password2", "確認密碼", 0, 1, 50);}
		if(re){re = checkPassword("password", "password2");}
		//if(re){re = isnull("name", "姓名", 0, 1, 30);}
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
                <p align="left">新增管理者</p>
            </div>
            <div class="listshow">
                <form action="save.php?<?php echo $query_str; ?>" method="post" enctype="multipart/form-data" name="form" id="form">
                    <table width="850" border="0" cellpadding="0" cellspacing="3">
                        <!--<tr>
                            <td width="150" valign="top"><h4 class="input-text-title">是否顯示</h4></td>
                            <td><input type="checkbox" name="isshow" id="isshow" checked value="1"/>
                                &nbsp;
                                顯示 </td>
                        </tr>-->
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">帳號</h4></td>
                            <td><input type="text" name="account" id="account" size="50" maxlength="30" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">密碼</h4></td>
                            <td><input type="password" name="password" id="password" size="50" maxlength="50" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">確認密碼</h4></td>
                            <td><input type="password" name="password2" id="password2" size="50" maxlength="50" value=""/></td>
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
