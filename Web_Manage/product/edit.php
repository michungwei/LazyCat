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
		FROM $table_product 
		WHERE $id_column = '$id'";
$row = $db -> query_first($sql);
$rows_pic = Banner::getPic();

if($row){
	$type = $row["product_type_id"];
    $style = $row["product_style_id"];
	$serial = $row["product_serial_id"];
	$sno = $row["product_sno"];
	$name_tw = $row["product_name_tw"];
	$name_en = $row["product_name_en"];
	$price = $row["product_sell_price"];
	$sprice = $row["product_special_price"];
	$stock = $row["product_stock"];
    $colorStock = $row["product_colorStock"];
	$pic1 = $row["product_pic1"];
	$pic2 = $row["product_pic2"];
	$pic3 = $row["product_pic3"];
	$pic4 = $row["product_pic4"];
	$weekly = $row["product_weekly"];
	$hot = $row["product_hot"];
	$comment = $row["product_comment"];
    $comment2 = $row["product_comment2"];
	$is_show = $row["product_is_show"];
	$update_time = $row["product_update_time"];
    $color = $row["product_color"];
}else{
 	script("資料不存在");
}

$sql_serial = "SELECT *
			 FROM $table_productserial 
			 WHERE productserial_is_show = 1 AND productserial_type_id = '$type'
			 ORDER BY productserial_ind DESC";
$rows_serial = $db -> fetch_all_array($sql_serial);

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
<script src="../../ui/colorpicker/js/colorpicker.js"></script>
<script>
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
		//if(re){re = isnull("sno", "商品序號", 0, 1, 20);}
		if(re){re = isnull("name_tw", "名稱(中)", 0, 1, 20);}
		if(re){re = isnull("name_en", "名稱(英)", 0, 1, 35);}
		if(re){re = isnull("price", "售價", 0, 1, 200);}
		if(re){re = isnull("stock", "庫存", 0, 1, 200);}
		//if(re){re = isnull("pic1", "圖片1", 0, 1, 9999);}
		//if(re){re = isnull("pic2", "圖片2", 0, 1, 9999);}
		//if(re){re = isnull("pic3", "圖片3", 0, 1, 9999);}
		//if(re){re = isnull("pic4", "圖片4", 0, 1, 9999);}
		if(re){re = isnull("comment", "說明", 0, 1, 9999999);}
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

    //initial color select
    var OriColor = <?php echo "\"".$color."\""; ?>;
    var OriColorStrAry = OriColor.split(",");
    console.log(OriColorStrAry);
    var OriColorStock = <?php echo "\"".$stock."\""; ?>;
    var OriColorStockAry = OriColorStock.split(",");
    console.log(OriColorStrAry);

    var colorSelCnt = 0;
    for(index in OriColorStrAry)
    {
        if(OriColorStrAry[index] != "")
        {
            var colorSelId ="colorItem" + colorSelCnt;
            var colorStockSelId = 'colorStock' + colorSelCnt;
            $('#colorContainer').append("<div id='colorShow'><div id='"+colorSelId+"' style='background-color: "+OriColorStrAry[index]+"'></div></div>");
            $('#colorStockContainer').append("<input type='text' id='"+colorStockSelId+"' size='5' value='"+OriColorStockAry[index]+"'/>");
            CreateColorSelect(colorSelCnt, colorSelId, OriColorStrAry[index], colorStockSelId);
            OriColorStrAry[index] += ",";
            OriColorStockAry[index] += ",";
            colorSelCnt ++;
        }
    }
    console.log(OriColorStrAry);
    console.log(OriColorStockAry);

    var colorStrAry = OriColorStrAry;
    var StockNumAry = OriColorStockAry;
    var combineStr = "";
    $('#colorSelector').click(function(){
        var colorSelId ="colorItem" + colorSelCnt;
        var colorStockSelId = 'colorStock' + colorSelCnt;
        $('#colorContainer').append("<div id='colorShow'><div id='"+colorSelId+"' style='background-color: #0000ff'></div></div>");
        $('#colorStockContainer').append("<input type='text' id='"+colorStockSelId+"' size='5' value='0'/>");
        colorStrAry[colorSelCnt] = "#0000ff,";
        StockNumAry[colorSelCnt] = "0,";
        CreateColorSelect(colorSelCnt, colorSelId, "#0000ff", colorStockSelId);
        colorSelCnt ++;
    });
    function CreateColorSelect(id, name, color, stockName)
    {
        console.log("CreateColorSelect!!");
        var colorStr = "";
        var selectorId = "#" + name;
        var stockSelId = "#" + stockName;
        console.log(selectorId);
        $(selectorId).ColorPicker({
            color: color,
            onShow: function (colpkr) {
                 $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onSubmit: function(hsb, hex, rgb, el) {
                var combineStr = "";
                colorStrAry[id] = "#" + hex + ",";
                for(var index in colorStrAry)
                {
                    combineStr += colorStrAry[index];
                }
                console.log(combineStr);
                $('#color').val(combineStr);
                $(el).ColorPickerHide();
            },
            onChange: function (hsb, hex, rgb) {
                //console.log("onchange!!");
                $(selectorId).css('background-color', '#' + hex);
            }
        });
        $(stockSelId).change(function(){
            var combineStr = "";
            StockNumAry[id] = $(this).val() + ",";
            for(var index in StockNumAry)
            {
                combineStr += StockNumAry[index];
            }
            console.log(combineStr);
            $('#stock').val(combineStr);
        });
    }
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
                    <input name="old_stock" type="hidden" id="old_stock" value="<?php echo $stock; ?>">
                    <table width="850" border="0" cellpadding="0" cellspacing="3">
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">最後修改時間</h4></td>
                            <td><?php echo $update_time; ?></td>
                            <td rowspan="15" valign="top"><?php if($pic1 != ""){echo '<a href="'.$file_path.$pic1.'" target="_blank"><img src="'.$file_path.$pic1.'" width="100"></a>圖1';} ?>
                                <br />
                                <?php if($pic2 != ""){echo '<a href="'.$file_path.$pic2.'" target="_blank"><img src="'.$file_path.$pic2.'" width="100"></a>圖2';} ?>
                                <br />
                                <?php if($pic3 != ""){echo '<a href="'.$file_path.$pic3.'" target="_blank"><img src="'.$file_path.$pic3.'" width="100"></a>圖3';} ?>
                                <br />
                                <?php if($pic4 != ""){echo '<a href="'.$file_path.$pic4.'" target="_blank"><img src="'.$file_path.$pic4.'" width="100"></a>圖4';} ?>
                                <br /></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">是否顯示</h4></td>
                            <td><input type="checkbox" name="is_show" id="is_show" <?php echo ($is_show == 1) ? "checked" : ""; ?> value="1" />
                                顯示 </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">每周最新</h4></td>
                            <td><input type="checkbox" name="weekly" id="weekly" <?php echo ($weekly == 1) ? "checked" : ""; ?> value="1"/>
                                &nbsp;
                                是 </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">熱門商品</h4></td>
                            <td><input type="checkbox" name="hot" id="hot" <?php echo ($hot == 1) ? "checked" : ""; ?> value="1"/>
                                &nbsp;
                                是 </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">分類</h4></td>
                            <td><select name="type" id="type">
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
                            <td><select name="serial" id="serial">
                                    <?php
										foreach($rows_serial as $row_serial){
									?>
                                    <option value="<?php echo $row_serial["productserial_id"]; ?>" <?php echo ($serial == $row_serial["productserial_id"]) ? "selected" : ""; ?>><?php echo $row_serial["productserial_name"]; ?></option>
                                    <?php
										}
									?>
                                </select></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">風格</h4></td>
                            <td ><select name="style" id="style">
                                    <option value="0" >請選擇風格</option>
                                    <?php
                                        foreach($rows_pic as $row_pic){
                                    ?>
                                        <option value="<?php echo $row_pic['pic_id']; ?>" <?php echo ($style == $row_pic['pic_id']) ? "selected": "";?> ><?php echo $row_pic['pic_name_tw'].'/'.$row_pic['pic_name_en']?></option>
                                    <?php
                                        }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">商品序號</h4></td>
                            <td><?php echo $sno; ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">名稱(中)</h4></td>
                            <td><input type="text" name="name_tw" id="name_tw" size="50" value="<?php echo $name_tw; ?>"/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">名稱(英)</h4></td>
                            <td><input type="text" name="name_en" id="name_en" size="50" value="<?php echo $name_en; ?>"/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">售價</h4></td>
                            <td><input type="text" name="price" id="price" size="50" value="<?php echo $price; ?>"/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">特價</h4></td>
                            <td><input type="text" name="sprice" id="sprice" size="50" value="<?php echo $sprice; ?>"/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">顏色</h4></td>
                            <td>
                                <div id="colorSelector"><a>+</a></div>
                                <div id="colorContainer"></div>
                                <input type="hidden" name="color" id="color" size="50" value="<?php echo $color; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">庫存</h4></td>
                            <td><div id="colorStockContainer"></div><input type="text" name="stock" id="stock" size="50" value="<?php echo $stock; ?>"/></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片1</h4></td>
                            <td><p>
                                    <input type="file" name="pic1" id="pic1" />
                                    <br />
                                    (請上傳符合比 <?php echo $product_pic_w; ?> x <?php echo $product_pic_h; ?> 大尺寸的圖片)<!--<input name="pic1_del" type="checkbox" id="pic1_del" value="1">&nbsp;刪除圖片1--></p></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片2</h4></td>
                            <td><p>
                                    <input type="file" name="pic2" id="pic2" />
                                    <br />
                                    (請上傳符合比 <?php echo $product_pic_w; ?> x <?php echo $product_pic_h; ?> 大尺寸的圖片)&nbsp;&nbsp;
                                    <input name="pic2_del" type="checkbox" id="pic2_del" value="1">
                                    &nbsp;刪除圖片2</p></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片3</h4></td>
                            <td><p>
                                    <input type="file" name="pic3" id="pic3" />
                                    <br />
                                    (請上傳符合比 <?php echo $product_pic_w; ?> x <?php echo $product_pic_h; ?> 大尺寸的圖片)&nbsp;&nbsp;
                                    <input name="pic3_del" type="checkbox" id="pic3_del" value="1">
                                    &nbsp;刪除圖片3</p></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片4</h4></td>
                            <td><p>
                                    <input type="file" name="pic4" id="pic4" />
                                    <br />
                                    (請上傳符合比 <?php echo $product_pic_w; ?> x <?php echo $product_pic_h; ?> 大尺寸的圖片)&nbsp;&nbsp;
                                    <input name="pic4_del" type="checkbox" id="pic4_del" value="1">
                                    &nbsp;刪除圖片4</p></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">內容</h4></td>
                            <td><textarea name="comment" id="comment" class="ckeditor"><?php echo $comment; ?></textarea></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">圖片內容</h4></td>
                            <td><textarea name="comment2" id="comment2" class="ckeditor"><?php echo $comment2; ?></textarea></td>
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
