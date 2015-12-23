<?php
$inc_path = "../inc/";
$manage_path = "";
include_once("_config.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Untitled Document</title>
<link href="css/admin_style_gray.css" rel="stylesheet" />
<script src="../scripts/jquery-1.6.1rc1.min.js"></script>
<script>
$(function(){
	$(".menu-top").toggle(function(){
		$(this).parent().parent().find("li").slideDown("fast");
	},function(){
		$(this).parent().parent().find("li").slideUp("fast");
	});

	$("ul li").hide();

	$(".menu-top").find("a").click(function(){
		$(".menutitle-on").removeClass("menutitle-on");
		$(".menu-open").removeClass().addClass("menu-close");
		$(this).parent().parent().removeClass().addClass("menu-open");
		$(this).parent().parent().find("li").slideDown("fast");
	});

	$("#adminmenu li a").click(function(){
		$(".menutitle-on").removeClass("menutitle-on");
		$(".menu-open").removeClass().addClass("menu-close");
		$(this).parent().parent().removeClass().addClass("menu-open");
		$("#adminmenu li").removeClass();
		$(this).parent().addClass("current");
	});
});
</script>
</head>

<body>
<div id="adminmenu">
    <div id="adminmenu_title"  class="menutitle-on"><a href="index.php" target="_top"><img src="images/menutitle.png" /></a>&nbsp;&nbsp;功能列表</div>
    <ul>
        <div class="menu-close">
            <div class="menu-top">
                <div class="mg-menu-toggle"><br />
                </div>
                <span>訂單管理</span></div>
            <li><a href="order/index.php" target="main" onFocus="this.blur()"><span>訂單列表</span></a></li>
        </div>
        <div class="clear"></div>
    </ul>
    <ul>
        <div class="menu-close">
            <div class="menu-top">
                <div class="mg-menu-toggle"><br />
                </div>
                <span>商品管理</span></div>
            <li><a href="product/index.php" target="main" onFocus="this.blur()"><span>商品列表</span></a></li>
            <li><a href="productserial/index.php" target="main" onFocus="this.blur()"><span>商品系列列表</span></a></li>
        </div>
        <div class="clear"></div>
    </ul>
    <ul>
        <div class="menu-close">
            <div class="menu-top">
                <div class="mg-menu-toggle"><br />
                </div>
                <span>WISHLIST管理</span></div>
            <li><a href="wishlist/member_list.php" target="main" onFocus="this.blur()"><span>WISHLIST清單</span></a></li>
            <li><a href="wishlist/index.php" target="main" onFocus="this.blur()"><span>WISHLIST列表</span></a></li>
            <li><a href="wishlist/wish_list.php" target="main" onFocus="this.blur()"><span>WISHLIST統計</span></a></li>
        </div>
        <div class="clear"></div>
    </ul>
    <ul>
        <div class="menu-close">
            <div class="menu-top">
                <div class="mg-menu-toggle"><br />
                </div>
                <span>抵用金管理</span></div>
            <li><a href="promo/index.php" target="main" onFocus="this.blur()"><span>折扣碼列表</span></a></li>
            <li><a href="discharge/index.php" target="main" onFocus="this.blur()"><span>抵用金列表</span></a></li>
        </div>
        <div class="clear"></div>
    </ul>
    <ul>
        <div class="menu-close">
            <div class="menu-top">
                <div class="mg-menu-toggle"><br />
                </div>
                <span>會員管理</span></div>
            <li><a href="member/index.php" target="main" onFocus="this.blur()"><span>會員列表</span></a></li>
        </div>
        <div class="clear"></div>
    </ul>
    <ul>
        <div class="menu-close">
            <div class="menu-top">
                <div class="mg-menu-toggle"><br />
                </div>
                <span>聯絡我管理</span></div>
            <li><a href="contact/index.php" target="main" onFocus="this.blur()"><span>聯絡我列表</span></a></li>
        </div>
        <div class="clear"></div>
    </ul>
    <ul>
        <div class="menu-close">
            <div class="menu-top">
                <div class="mg-menu-toggle"><br />
                </div>
                <span>BANNER管理</span></div>
            <li><a href="banner/index.php" target="main" onFocus="this.blur()"><span>BANNER列表</span></a></li>
            <li><a href="subbanner/index.php" target="main" onFocus="this.blur()"><span>內頁BANNER列表</span></a></li>
        </div>
        <div class="clear"></div>
    </ul>
    <ul>
        <div class="menu-close">
            <div class="menu-top">
                <div class="mg-menu-toggle"><br />
                </div>
                <span>首頁圖片管理</span></div>
            <li><a href="pic/index.php" target="main" onFocus="this.blur()"><span>首頁圖片列表</span></a></li>
        </div>
        <div class="clear"></div>
    </ul>
    <ul>
        <div class="menu-close">
            <div class="menu-top">
                <div class="mg-menu-toggle"><br />
                </div>
                <span>宣傳冊管理</span></div>
            <li><a href="lookbook/index.php" target="main" onFocus="this.blur()"><span>宣傳冊列表</span></a></li>
            <li><a href="lookbookpic/index.php" target="main" onFocus="this.blur()"><span>宣傳冊圖片列表</span></a></li>
        </div>
        <div class="clear"></div>
    </ul>
    <ul>
        <div class="menu-close">
            <div class="menu-top ">
                <div class="mg-menu-toggle"><br />
                </div>
                <span>管理者管理</span></div>
            <li><a href="admin/index.php" target="main" onFocus="this.blur()"><span>管理者列表</span></a></li>
            <li><a href="logout.php" target="main" onFocus="this.blur()"><span>退出系統</span></a></li>
        </div>
        <div class="clear"></div>
    </ul>
</div>
</body>
</html>
