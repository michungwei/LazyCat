<?php
class shareMail{
	
	public static $type= array( 'news'=>'mail_news.php','member_register'=>'mail_member_register.php','member_success'=>'mail_member_success.php','member_password'=>'mail_member_password.php','order'=>'mail_order.php' );


	public static function getMailContent($type, $search, $replace){
		global $template_path;
        $header = file_get_contents($template_path . 'mail_header.php');
        $footer = file_get_contents($template_path . 'mail_footer.php');	
        return self::_getContent($header . self::getContent($type, $search, $replace) . $footer,$search,$replace);	
	}

	public static function getContent($type, $search, $replace){
		global $template_path;
		$content='';
		if(!array_key_exists ( $type , self::$type)){
            self::_oops('內容類別錯誤');
        }
        $content = file_get_contents($template_path . self::$type[$type]);

        return self::_getContent($content,$search,$replace);	
	}
    private static function _getContent($content, $search, $replace) {
        global $template_path, $weburl, $webname;

        $search = array_merge($search, array(
            '{$weburl}',
            '{$webname}'
        ));
        $replace = array_merge($replace, array(
            $weburl,
            $webname
        ));
        
        return str_replace($search, $replace, $content);
    }

	private static function _oops($msg){
		throw new Exception('contentManage:'.$msg);
	}
}