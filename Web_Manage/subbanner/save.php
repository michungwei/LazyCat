<?php
include_once("_config.php");
include_once($inc_path."_imgupload.php");

$db = new Database($HS, $ID, $PW, $DB);
$db -> connect();


$data["subbanner_page"] = post("page", 1);
$data["subbanner_pic_link"] = post("piclink", 1);
$data["subbanner_update_time"] = request_cd();

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
		if($data["subbanner_page"] == 6){
			$file -> createSmailImg($subbannerw_pic_w, $subbannerw_pic_h, 6);
		}else{
			$file -> createSmailImg($subbanner_pic_w, $subbanner_pic_h, 6);
		}
		$data["subbanner_pic"] = $file -> file_name;
	}	
}

$db -> query_insert($table_subbanner, $data);

$db -> close();
script("新增成功!", "index.php");



/*****END PHP*****/



