<?php
class LookBook{

	public static function getLookBook(){
		global $db, $table_lookbook;
		return $db -> fetch_all_array(
										"SELECT * 
										 FROM $table_lookbook 
										 WHERE lookbook_is_show = 1
										 ORDER BY lookbook_ind DESC"
		);
	}
	
	public static function getLookBookPic($lookbook_id){
		global $db, $table_lookbookpic, $table_lookbook;
		return $db -> fetch_all_array(
										"SELECT lookbookpic_pic, lookbook_title, lookbook_titlepic
					   					 FROM $table_lookbookpic LEFT JOIN $table_lookbook ON lookbookpic_lookbook_id = lookbook_id
					   					 WHERE lookbookpic_is_show = 1 AND lookbookpic_lookbook_id ='$lookbook_id'
					   					 ORDER BY lookbookpic_id DESC"
		);
	}
	

}


/***END PHP***/