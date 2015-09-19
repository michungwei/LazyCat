<?PHP
/*Initial*/
ini_set('display_errors', 1);	// 0不顯示 1顯示
ini_set('error_reporting', E_ALL | E_STRICT);	// report all errors
error_reporting(E_ALL);	// report all errors
ini_set('magic_quotes_runtime', 0);
date_default_timezone_set("Asia/Taipei");
ob_start();
session_start();
mb_internal_encoding("UTF-8");
header("Content-type:text/html; charset=utf-8");

$web_name = "LazyCat 特色配件專門店";
$web_url = "http://localhost/lazycat/";
$description = "";
$keywords = "";
$favicon = "assets/images/favicon.ico";
$author = "CODER 誠智數位";
$copyright = "LazyCat © 2014";
$manage_name = "LazyCat－網站管理系統";


/*Database*/
$HS = "xxxx";
$ID = "xxxx";
$PW = "xxxx";
$DB = "xxxx";

/*SMTP Server*/ 
$smtp_auth = false;
$smtp_host = "127.0.0.1";
$smtp_port = 25;
$smtp_id   = ""; 
$smtp_pw   = "";

/*DB Table*/
$table_admin = "lc_admin";
$table_banner = "lc_banner";
$table_contact = "lc_contact";
$table_lookbook = "lc_lookbook";
$table_lookbookpic = "lc_lookbookpic";
$table_member = "lc_member";
$table_order = "lc_order";
$table_orderdetail = "lc_orderdetail";
$table_ordersno = "lc_ordersno";
$table_pic = "lc_pic";
$table_product = "lc_product";
$table_productserial = "lc_productserial";
$table_wish = "lc_wish";
$table_storelog = "lc_storelog";
$table_subbanner = "lc_subbanner";

/*Upload path*/
//banner
$web_path_banner = "upload/banner/";
$admin_path_banner = "../../upload/banner/";

//subbanner
$web_path_subbanner = "upload/subbanner/";
$admin_path_subbanner = "../../upload/subbanner/";

//pic
$web_path_pic = "upload/pic/";
$admin_path_pic = "../../upload/pic/";

//product
$web_path_product = "upload/product/";
$admin_path_product = "../../upload/product/";

//productserial
$web_path_productserial = "upload/productserial/";
$admin_path_productserial = "../../upload/productserial/";

//lookbook
$web_path_lookbook = "upload/lookbook/";
$admin_path_lookbook = "../../upload/lookbook/";

/*Image setup*/
//banner
$banner_pic_w = 1284;
$banner_pic_h = 435;

//subbanner
$subbanner_pic_w = 1280;
$subbanner_pic_h = 300;

$subbannerw_pic_w = 1280;
$subbannerw_pic_h = 127;

//pic
$pic_pic_w = 301;
$pic_pic_h = 261;

//productserial
$productserial_pic_w = 301;
$productserial_pic_h = 261;

//product
$product_pic_w = 450;
$product_pic_h = 600;

//lookbook
$lookbook_pic_w = 1010;
$lookbook_pic_h = 1010;

/*資料用ARY*/
$ary_yn = array('否', '是');
$ary_payment_state = array(0 => '未付款', 1 => '已付款');//付款狀態
$ary_payment_type = array(1 => '線上刷卡', 2 => 'ATM虛擬帳號', 3 => '超商代收', 4 => '7-11ibon / 全家FamiPort / 萊爾富Life-ET / OK 超商OK-go', 9 => '貨到付款');//付款方式
$ary_order_state = array(0 => '處理中', 1 => '可出貨', 2 => '己出貨', 3 => '交易完成', 10 => '退貨', 11 => '交易取消');//訂單狀態
$ary_product_type = array(1 => 'Bag', 2 => 'Accessories', 3 => 'Others');
$ary_page = array(1 => "首頁", 2 =>"what's news", 3 => "bags", 4 => "accessories", 5 => "others", 6 => "wishlist");

/*Email*/
$sys_email = "lazycat.co@gmail.com";
$sys_name = "客服中心";


$slash = (strstr(dirname(__FILE__), '/'))?"/":"\\";
define("CONFIG_DIR",dirname(__FILE__).$slash);

require_once(CONFIG_DIR."_func.php");
require_once(CONFIG_DIR."_database.class.php");


/***END PHP***/

