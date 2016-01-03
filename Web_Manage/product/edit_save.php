<?php
include_once("_config.php");
include_once($inc_path."_imgupload.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$id = post("cid");
$old_store = post("old_stock", 1);
$new_store = post("stock", 1);


$pic2_del = post("pic2_del");
$pic3_del = post("pic3_del");
$pic4_del = post("pic4_del");

$data["product_type_id"] = post("type");
$data["product_style_id"] = post("style");
$data["product_serial_id"] = post("serial");
//$data["product_sno"] = post("sno", 1);
$data["product_name_tw"] = post("name_tw", 1);
$data["product_name_en"] = post("name_en", 1);
$data["product_sell_price"] = post("price", 1);
$data["product_special_price"] = post("sprice", 1);
$data["product_stock"] = post("stock", 1);
$data["product_comment"] = request_str("comment");
$data["product_comment2"] = request_str("comment2");
$data["product_is_show"] = post("is_show");
$data["product_weekly"] = post("weekly");
$data["product_hot"] = post("hot");
$data["product_update_time"] = request_cd();

$data["product_color"] = post("color",1);

$file = new imgUploder($_FILES['pic1']);
if($file -> file_name != ""){		
	$rr = explode('.', $file -> file_name);
	$file -> set("file_name", '1'.time().'.'.end($rr));
	$file -> set("file_max", 1024*1024*3); 
	$file -> set("file_dir", $file_path); 
	$file -> set("overwrite", "3"); 
	$file -> set("fstyle", "image"); 
	if($file -> upload() && $file -> file_name != ""){
		// $file -> file_sname = "m";
		// $file -> createSmailImg($product_pic_w, $product_pic_h, 6);	
		$data["product_pic1"] = $file -> file_name;
	}	
}
$file = new imgUploder($_FILES['pic2']);
if($file -> file_name != ""){		
	$rr = explode('.', $file -> file_name);
	$file -> set("file_name", '2'.time().'.'.end($rr));
	$file -> set("file_max", 1024*1024*3); 
	$file -> set("file_dir", $file_path); 
	$file -> set("overwrite", "3"); 
	$file -> set("fstyle", "image"); 
	if($file -> upload() && $file -> file_name != ""){
		// $file -> file_sname = "m";
		// $file -> createSmailImg($product_pic_w, $product_pic_h, 6);	
		$data["product_pic2"] = $file -> file_name;
	}	
}
if($pic2_del == 1){
	$data["product_pic2"] = "";
}
$file = new imgUploder($_FILES['pic3']);
if($file -> file_name != ""){		
	$rr = explode('.', $file -> file_name);
	$file -> set("file_name", '3'.time().'.'.end($rr));
	$file -> set("file_max", 1024*1024*3); 
	$file -> set("file_dir", $file_path); 
	$file -> set("overwrite", "3"); 
	$file -> set("fstyle", "image"); 
	if($file -> upload() && $file -> file_name != ""){
		// $file -> file_sname = "m";
		// $file -> createSmailImg($product_pic_w, $product_pic_h, 6);	
		$data["product_pic3"] = $file -> file_name;
	}	
}
if($pic3_del == 1){
	$data["product_pic3"] = "";
}
$file = new imgUploder($_FILES['pic4']);
if($file -> file_name != ""){		
	$rr = explode('.', $file -> file_name);
	$file -> set("file_name", '4'.time().'.'.end($rr));
	$file -> set("file_max", 1024*1024*3); 
	$file -> set("file_dir", $file_path); 
	$file -> set("overwrite", "3"); 
	$file -> set("fstyle", "image"); 
	if($file -> upload() && $file -> file_name != ""){
		// $file -> file_sname = "m";
		// $file -> createSmailImg($product_pic_w, $product_pic_h, 6);	
		$data["product_pic4"] = $file -> file_name;
	}	
}
if($pic4_del == 1){
	$data["product_pic4"] = "";
}

$db -> query_update($table_product, $data, "$id_column = $id");


//修改庫存log
if($old_store != $new_store){
	$data_store["storelog_product_id"] = $id;
	$data_store["storelog_acc"] = $_SESSION["madmin"];
	$data_store["storelog_comment"] = "(修改庫存)庫存由".$old_store."修改為".$new_store;
	$data_store["storelog_create_time"] = request_cd();
	
	$db -> query_insert($table_storelog, $data_store);
}


$db -> close();

script("修改完成!", "edit.php?id=".$id);

/*End PHP*/



