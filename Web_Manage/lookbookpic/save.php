<?php
include_once("_config.php");
include_once($inc_path."_imgupload.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();

$data["lookbookpic_lookbook_id"] = post("type");
$data["lookbookpic_is_show"] = post("is_show");
$data["lookbookpic_create_time"] = request_cd();
$data["lookbookpic_update_time"] = request_cd();

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
		$file -> createSmailImg($lookbook_pic_w, $lookbook_pic_h, 6);	
		$data["lookbookpic_pic"] = $file -> file_name;
	}	
}

$db -> query_insert($table_lookbookpic, $data);
$db -> close();

script("新增成功!", "index.php");

/*END PHP*/



