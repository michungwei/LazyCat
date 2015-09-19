<?php
class Banner{

	public static function getBanner(){
		global $db, $table_banner;
		return $db -> fetch_all_array(
										"SELECT * 
										 FROM $table_banner 
										 WHERE banner_is_show = 1
										 ORDER BY banner_ind DESC"
		);
	}
	
	public static function getPic(){
		global $db, $table_pic;
		return $db -> fetch_all_array(
										"SELECT *
					   					 FROM $table_pic
					   					 WHERE pic_is_show = 1
					   					 ORDER BY pic_is_show DESC"
		);
	}
	
	public static function getSubBanner(){
		global $db, $table_subbanner;
		return $db -> fetch_all_array(
										"SELECT * 
										 FROM $table_subbanner
										 ORDER BY subbanner_page"
		);
	}

}


/***END PHP***/