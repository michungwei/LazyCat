<script>
$(document).ready(function(e) {
    demo();
    $(".memberSelect").click(function(){
        if($(".memberSelectItem").css("display") == "none")
        {
            $(".memberSelectItem").css("display","block");
            $(".memberSelectItem").css("left",$(".m_name").position().left + 13);
            //console.log($(".m_name").position().left);
        }
        else
            $(".memberSelectItem").css("display","none");
    });
    $(".memberMenu").find(".memberSelectItem>li").click(function() {
        var isVal = $(this).text();
        //$(".numSelect").find(".beSelect").text(isVal);
        var order = $(this).attr("href");
        $(location).attr('href', order);
        //console.log(order);
    });
});

</script>
<div class="memberMenu">
    <ul class="memberSelectItem">
        <li href="memberCenter_<?php echo $_SESSION["session_id"]; ?>.html"> 會員中心 </li>
        <li href="wishlist.html"> wishlist </li>
        <li href="order-list.php?member_id=<?php echo $_SESSION["session_id"]; ?>"> 訂單查詢 </li>
        <!--<li> 購物抵用金查詢 </li>-->
    </ul>
</div>
<div id="nav">
    <a href="index.html"><h1 class="logo"></h1></a>
    <ul class="menu">
        <li class="menu-item menu-news"><a href="product-new.html">what's_news</a></li>
        <li class="menu-item menu-bag"><a href="product-list_1.html">bags</a></li>
        <li class="menu-item menu-access"><a href="product-list_2.html">accessories</a></li>
        <li class="menu-item menu-other"><a href="product-list_3.html">others</a></li>
        <li class="menu-item menu-other"><a href="product-list_4.html">Sale</a></li>
        <li class="menu-item menu-look"><a href="lookbook.html">lookbook</a></li>
        <!--<li class="menu-item menu-wish"><a href="wishlist.html">wishlist</a></li>-->
    </ul>
    <ul class="submenu">
    	<?php
			if(isLogin() || chkCookie()){
		?>
        	<li class="sub-item m_name">
                <div class="memberSelect"> <span class="beSelect"> <?php echo $_SESSION["session_acc"]; ?> </span>
                </div>
                <!--<a href="memberCenter_<?php echo $_SESSION["session_id"]; ?>.html"><?php echo $_SESSION["session_acc"]; ?></a>-->
            </li>
        	<li class="sub-item" style="font-weight:900; font-size:12px; margin-right:20px; border-right-color:#000000; border-right-style:solid; border-right-width:2px; padding-right:10px; height:15px;"><a href="javascript:;" class="log_out" onclick="logOut()">logout</a></li>
        <?php
			}else{
		?>
        	<li class="sub-item menu-signin"><a href="sign.html">sign</a></li>
        <?php		
			}
		?>
        <li class="sub-item">
        	<div class="search">
            	<span id="search_pic"></span>
                <span id="search_input"><input type="text" value="" id="search"/></span>
            </div>
            <!--<label class="search active">
             <span id="bb" style="float:left;"></span>
            <input type="text" value="" id="search" style="float:left;"/>
            </label> -->
        </li>
        <li class="sub-item cart_no"><a href="order-step1.html"><span class="ui-s-bag"><?php echo ($u > 0) ? $u : 0; ?></span></a></li>
        
    </ul>
</div>
