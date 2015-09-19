<?php
include_once("_config.php");
include_once($inc_path."_imgupload.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$id = post("cid");

$data["pic_name_tw"] = post("name_tw", 1);
$data["pic_name_en"] = post("name_en", 1);
$data["pic_link"] = post("link", 1);
$data["pic_is_show"] = post("is_show");
$data["pic_update_time"] = request_cd();

$file = new imgUploder($_FILES['pic']);
if($file -> file_name != ""){		
	$rr = explode('.', $file -> file_name);
	$file -> set("file_name", time().'.'.end($rr));
	$file -> set("file_max", 1024*1024*3); 
	$file -> set("file_dir", $file_path); 
	$file -> set("overwrite", "3"); 
	$file -> set("fstyle", "image"); 
	if($file -> upload() && $file -> file_name != ""){
		$file -> file_sname = "m";
		$file -> createSmailImg($pic_pic_w, $pic_pic_h, 6);	
		$data["pic_pic"] = $file -> file_name;
	}	
}

$db -> query_update($table_pic, $data, "$id_column = $id");
$db -> close();

script("修改完成!", "edit.php?id=".$id);

/*PHP END*/






