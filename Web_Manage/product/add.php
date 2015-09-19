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
<script src="../../ui/ckeditor/ckeditor.js"></script>
<script src="../../scripts/function.js"></script>
<script>
function checkSNO(sno){
	var success = false;
	$.ajax({
		url: 'check_sno.php',
		type: 'POST',
		data:{
			sno  : sno
		},
		async: false,
		error: function(){
			err_msg ="發生錯誤";
			success =false;
		},
		success: function(response){
			if(response=="1"){
				success =true;
  		}else{
				err_msg ="商品序號重複";
				success =false;
  		} 
		}
	});
	return success;
}
$(document).ready(function(){ 
	$("form").submit(function(){
		$('textarea.ckeditor').each(function () {
			var $textarea = $(this);
			$textarea.val(CKEDITOR.instances[$textarea.attr('name')].getData());
        });
		
		var re = true;
		err_msg = '';
		if(re){re = isnull("type", "分類", 0, 1, 5);}
		if(re){re = isnull("serial", "系列", 0, 1, 5);}
		if(re){re = isnull("sno", "商品序號", 0, 1, 20);}
		if(re){re = isnull("name_tw", "名稱(中)", 0, 1, 20);}
		if(re){re = isnull("name_en", "名稱(英)", 0, 1, 35);}
		if(re){re = isnull("price", "售價", 0, 1, 200);}
		if(re){re = isnull("stock", "庫存", 0, 1, 200);}
		if(re){re = isnull("pic1", "圖片1", 0, 1, 9999);}
		//if(re){re = isnull("pic2", "圖片2", 0, 1, 9999);}
		//if(re){re = isnull("pic3", "圖片3", 0, 1, 9999);}
		//if(re){re = isnull("pic4", "圖片4", 0, 1, 9999);}
		if(re){re = isnull("comment", "說明", 0, 1, 9999999);}
		if(re){re = checkSNO($.trim($("#sno").val()));}
		if (!re){
			alert(err_msg)
			return false;
		}
		return true;
	});
	
	$("#type").change(function(e) {
        var val = $(this).val();
		if(val != ""){
			$.ajax({
				url : "get_serial.php",
				data : {type : val, action : 'add'},
				type : "post",
				dataType : "json",
				async : false,
				success : function(data){
					if(data.result){
						$("#serial").html(data.list);
					}else{
						alert(data.list);
					}
				}
			});
		}else{
			$("#serial").html('<option value="" >請選擇分類</option>');
		}
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
                <p align="left">新增商品</p>
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
                            <td width="150" valign="top"><h4 class="input-text-title">每周最新</h4></td>
                            <td><input type="checkbox" name="weekly" id="weekly" value="1"/>
                                &nbsp;
                                是 </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">熱門商品</h4></td>
                            <td><input type="checkbox" name="hot" id="hot" value="1"/>
                                &nbsp;
                                是 </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">分類</h4></td>
                            <td><select name="type" id="type">
                                    <option value="">--請選擇--</option>
                                    <?php 
										foreach($ary_product_type as $key => $val){
									?>
                                    <option value="<?php echo $key; ?>" <?php echo ($type == $key) ? "selected" : ""; ?>><?php echo $val; ?></option>
                                    <?php
										}
									?>
                                </select></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">系列</h4></td>
                            <td class="type_box"><select name="serial" id="serial">
                                    <option value="" >請選擇分類</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">商品序號</h4></td>
                            <td><input type="text" name="sno" id="sno" size="50" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">名稱(中)</h4></td>
                            <td><input type="text" name="name_tw" id="name_tw" size="50" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">名稱(英)</h4></td>
                            <td><input type="text" name="name_en" id="name_en" size="50" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">售價</h4></td>
                            <td><input type="text" name="price" id="price" size="50" value=""/></td>
                        </tr>
                        <!--<tr>
                            <td width="150" valign="top"><h4 class="input-text-title">特價</h4></td>
                            <td><input type="text" name="sprice" id="sprice" size="50" value=""/></td>
                        </tr>-->
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">庫存</h4></td>
                            <td><input type="text" name="stock" id="stock" size="50" value=""/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片1</h4></td>
                            <td><p>
                                    <input type="file" name="pic1" id="pic1" />
                                    <br />
                                    (請上傳符合 <?php echo $product_pic_w; ?> x <?php echo $product_pic_h; ?> 尺寸的圖片)</p></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片2</h4></td>
                            <td><p>
                                    <input type="file" name="pic2" id="pic2" />
                                    <br />
                                    (請上傳符合 <?php echo $product_pic_w; ?> x <?php echo $product_pic_h; ?> 尺寸的圖片)</p></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片3</h4></td>
                            <td><p>
                                    <input type="file" name="pic3" id="pic3" />
                                    <br />
                                    (請上傳符合 <?php echo $product_pic_w; ?> x <?php echo $product_pic_h; ?> 尺寸的圖片)</p></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片4</h4></td>
                            <td><p>
                                    <input type="file" name="pic4" id="pic4" />
                                    <br />
                                    (請上傳符合 <?php echo $product_pic_w; ?> x <?php echo $product_pic_h; ?> 尺寸的圖片)</p></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">說明</h4></td>
                            <td><textarea name="comment" id="comment" class="ckeditor"></textarea></td>
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
