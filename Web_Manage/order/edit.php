<?php
include_once("_config.php");

$id = get("id");

if($id == 0){
	script("資料傳輸不正確", "index.php".$query_str);
}

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$sql = "SELECT *
		FROM $table_order
		WHERE $id_column = '$id'";
$row = $db -> query_first($sql);

if($row){
	$order_sno = $row["order_sno"];
	$order_member_id= $row["order_member_id"];
	$order_payment_type = $row["order_payment_type"];
	$order_payment_state = $row["order_payment_state"];
	$order_state = $row["order_state"];
	$order_comment = $row["order_comment"];
	$order_total_price = $row["order_total_price"];
	$order_freight = $row["order_freight"];
	$order_recipient_name = $row["order_recipient_name"];
	$order_recipient_email = $row["order_recipient_email"];
	$order_recipient_mobile = $row["order_recipient_mobile"];
	$order_recipient_address = $row["order_recipient_address"];
	$order_manager = $row["order_manager"];
	$order_create_time = $row["order_create_time"];
	$order_update_time = $row["order_update_time"];
	$order_payment_return = $row["order_payment_return"];
    $order_transport_memo = $row["order_transport_memo"];
	
	$row_member = $db -> query_first("SELECT * FROM $table_member WHERE member_id = '$order_member_id'");
	
	$sql_products = "SELECT orderdetail_order_sno, orderdetail_product_id, orderdetail_product_sno, orderdetail_sell_price, orderdetail_amount, orderdetail_total_price, product_sno, product_name_tw, product_name_en, product_pic1 
					 FROM $table_orderdetail LEFT JOIN $table_product ON orderdetail_product_id = product_id AND orderdetail_product_sno = product_sno 
					 WHERE orderdetail_order_sno = '$order_sno'
					 ORDER BY orderdetail_id";
	$rows_product = $db ->fetch_all_array($sql_products);
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
<script src="../../scripts/jquery-1.6.1rc1.min.js"></script>
<script src="../../ui/ckeditor/ckeditor.js"></script>
<script src="../../scripts/public.js"></script>
<script src="../../scripts/function.js"></script>
<script>
$(document).ready(function(){ 

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
                <p align="left">訂單編號 - <?php echo $order_sno; ?></p>
            </div>
            <div class="listshow">
                <form action="edit_save.php?<?php echo $query_str; ?>" method="post" enctype="multipart/form-data" name="form" id="form">
                    <input type="hidden" name="cid" value="<?php echo $id; ?>">
                    <input name="order_sno" type="hidden" id="order_sno" value="<?php echo $order_sno; ?>">
                    <input name="old_order_state" type="hidden" id="old_order_state" value="<?php echo $order_state; ?>">
                    <table width="850" border="0" cellpadding="0" cellspacing="3">
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">訂單日期</h4></td>
                            <td><?php echo $order_create_time; ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">最後修改時間</h4></td>
                            <td><?php echo $order_update_time; ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">付款狀態</h4></td>
                            <td><?php echo $ary_payment_state[$row["order_payment_state"]]; ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">付款方式</h4></td>
                            <td><?php echo $ary_payment_type[$row["order_payment_type"]]; ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">運送方式</h4></td>
                            <td><?php echo $ary_transport_type[$row["order_recipient_wayOption"]]; ?><?php if($row["order_recipient_wayOption"] == 1) echo " - ".$row["order_transport_memo"]; ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">訂單金額</h4></td>
                            <td><?php echo $order_total_price; ?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">訂單狀態</h4></td>
                            <td><?php 
								if($order_state == 3 || $order_state == 10 || $order_state == 11){
									echo ' <input type="radio" class="order_state" name="order_state" value="'.$order_state.'" checked>'.$ary_order_state[$order_state] .'  ';
								}else{
									foreach($ary_order_state as $key => $val){
										echo ' <input type="radio" class="order_state" name="order_state" value="'.$key.'" '.(($order_state == $key) ? "checked" : "").'>'.$val.' ';
									}
								}
    						?></td>
                        </tr>
                        <tr>
                            <td width="150" valign="top"><h4 class="input-text-title">訂單備註</h4></td>
                            <td><textarea name="order_comment" id="note" cols="70" rows="5" style="text-align:left;" /><?php echo $order_comment; ?></textarea></td>
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
            <div class="tableheader">
                <div class="handlediv"></div>
                <p align="left">訂購明細</p>
            </div>
            <div class="listshow">
                <table width="850" border="0" cellpadding="3" cellspacing="3" style="margin-left:50px" >
                    <tr bgcolor="#E3E3E3">
                        <td width="50" alien="center">項次</td>
                        <td width="100" alien="center">商品編號</td>
                        <td width="100" alien="center">圖片</td>
                        <td width="200" >產品名稱</td>
                        <td width="80"  alien="center">數量</td>
                        <td width="80"  alien="center">單價</td>
                        <td width="80"  alien="center">小計</td>
                    </tr>
                    <?php
					$i = 1;
					foreach($rows_product as $row_product){
					?>
                    <tr>
                        <td alien="center"><?php echo $i; ?></td>
                        <td alien="center"><?php echo $row_product["product_sno"]; ?></td>
                        <td alien="center"><img src="<?php echo $admin_path_product."m".$row_product["product_pic1"]; ?>" alt="#" width="50" ></td>
                        <td><?php echo $row_product["product_name_tw"]."/".$row_product["product_name_en"]; ?></td>
                        <td alien="center"><?php echo $row_product["orderdetail_amount"]; ?></td>
                        <td alien="center"><?php echo $row_product["orderdetail_sell_price"]; ?></td>
                        <td alien="center"><?php echo $row_product["orderdetail_total_price"]; ?></td>
                    </tr>
                    <?php
					$i ++;
					}
					?>
                </table>
            </div>
            <div class="tableheader">
                <div class="handlediv"></div>
                <p align="left">訂購人資訊</p>
            </div>
            <div class="listshow">
                <table width="850" border="0" cellpadding="0" cellspacing="3" >
                    <tr>
                        <td width="120" valign="top"><h4 class="input-text-title">姓名</h4></td>
                        <td><?php echo $row_member["member_name"]; ?></td>
                    </tr>
                    <tr>
                        <td width="120" valign="top"><h4 class="input-text-title">電子信箱 </h4></td>
                        <td><?php echo $row_member["member_email"]; ?></td>
                    </tr>
                    <tr>
                        <td width="120" valign="top"><h4 class="input-text-title">手機號碼</h4></td>
                        <td ><?php echo $row_member["member_mobile"]; ?></td>
                    </tr>
                    <tr>
                        <td width="120" valign="top"><h4 class="input-text-title">聯絡地址</h4></td>
                        <td><?php echo $row_member["member_address"]; ?></td>
                    </tr>
                    <tr>
                        <td width="120" valign="top"><h4 class="input-text-title">生日</h4></td>
                        <td ><?php echo $row_member["member_birthday"]; ?></td>
                    </tr>
                </table>
            </div>
            <div class="tableheader">
                <div class="handlediv"></div>
                <p align="left">收件人資訊</p>
            </div>
            <div class="listshow">
                <table width="850" border="0" cellpadding="0" cellspacing="3" >
                    <tr>
                        <td width="120" valign="top"><h4 class="input-text-title">姓名</h4></td>
                        <td><?php echo $order_recipient_name; ?></td>
                    </tr>
                    <tr>
                        <td width="120" valign="top"><h4 class="input-text-title">電子信箱 </h4></td>
                        <td><?php echo $order_recipient_email; ?></td>
                    </tr>
                    <tr>
                        <td width="120" valign="top"><h4 class="input-text-title">手機號碼</h4></td>
                        <td ><?php echo $order_recipient_mobile; ?></td>
                    </tr>
                    <tr>
                        <td width="120" valign="top"><h4 class="input-text-title">聯絡地址</h4></td>
                        <td><?php echo $order_recipient_address; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
