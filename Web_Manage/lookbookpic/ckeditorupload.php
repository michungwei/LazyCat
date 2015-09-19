<?php
include_once('_config.php');
include_once($inc_path.'_imgupload.php');

$url = '';
$file = new imgUploder($_FILES['upload']);

if($file -> file_name != ""){
	$rr = explode('.', $file -> file_name);
	$file -> set("file_name", '1'.time().'.'.end($rr));	
	$file -> set("file_max", 1024*1024*3);
	$file -> set("file_dir", $file_path);
	$file -> set("overwrite", "3");
	$file -> set("fstyle", "image");
	if($file -> upload() && $file -> file_name != ""){	
		//$url = $path_ckeditor.$file -> file_name;	
		$url = $web_url.$web_path_producte.$file -> file_name;	
		$msg = '圖片上傳完成';

	}else{
		$msg = '圖片上傳失敗!'.$file -> user_msg;
	}
}else{
	$msg = '請選擇上傳檔案。';
}

$funcNum = $_GET['CKEditorFuncNum'] ;

echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$msg');</script>";


/*END PHP*/