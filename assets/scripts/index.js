$(document).ready(function(e) {
	demo();
	IMG_scroll('.new_box','.new_left','.new_right','.new_list',3,false);
	IMG_scroll('.hot_box','.hot_left','.hot_right','.hot_list',3,false);
    //mainBanner-slider
    var i = 0;
	var j =  $(".banner-list img").length - 1;
	if(j < 0){ j = 0;}
	$(".bang-item:eq(" + i + ")").addClass('active');
	
	function bannerAni() {
        $(".banner-list img").fadeOut(500);
        $(".bang-item").delay(1000).removeClass('active');
        i++;
        if (i > j) {
            i = 0;
        }
        $(".banner-list img:eq(" + i + ")").fadeIn(2000);
        $(".bang-item:eq(" + i + ")").addClass('active');
    }
    $(".btn-right").click(function() {
        $("#banner").stopTime();
        //alert($("#banner").width());
        $(".banner-list img").fadeOut(500);
        $(".bang-item").delay(1000).removeClass('active');
        i++
        if (i > j) {
            i = 0;
        }
        $(".banner-list img:eq(" + i + ")").fadeIn(2000);
        $(".bang-item:eq(" + i + ")").addClass('active');
        $("#banner").everyTime(5000, bannerAni);
    });
    $(".btn-left").click(function() {
        $("#banner").stopTime();
        //alert($("#banner").width());
        $(".banner-list img").fadeOut(500);
        $(".bang-item").delay(1000).removeClass('active');
        i--
        if (i < 0) {
            i = j;
        }
        $(".banner-list img:eq(" + i + ")").fadeIn(2000);
        $(".bang-item:eq(" + i + ")").addClass('active');
        $("#banner").everyTime(5000, bannerAni);
    });
    $(".bang-item").click(function() {
        i = $(this).index();
        $("#banner").stopTime();
        $(".banner-list img").fadeOut(500);
        $(".banner-list img:eq(" + i + ")").fadeIn(2000);
        $(".bang-item").removeClass('active');
        $(this).addClass('active');
        $("#banner").everyTime(5000, bannerAni);
    })
   
    $("#banner").everyTime(5000, bannerAni);
	
	$("li.pro_new").hover(
		function () {
			var pic1 = $(this).attr("pic1");
			var pic2 = $(this).attr("pic2");
			if(pic2 == pic_path){
				pic2 = pic1;
			}
			$(this).find("img").attr("src", pic2);
	  	},
	  	function () {
			var pic1 = $(this).attr("pic1");
			var pic2 = $(this).attr("pic2");
			if(pic2 == pic_path){
				pic2 = pic1;
			}
			$(this).find("img").attr("src", pic1);
	  	}
	);
	
	$("li.pro_hot").hover(
		function () {
			var pic1 = $(this).attr("pic1");
			var pic2 = $(this).attr("pic2");
			if(pic2 == pic_path){
				pic2 = pic1;
			}
			$(this).find("img").attr("src", pic2);
	  	},
	  	function () {
			var pic1 = $(this).attr("pic1");
			var pic2 = $(this).attr("pic2");
			if(pic2 == pic_path){
				pic2 = pic1;
			}
			$(this).find("img").attr("src", pic1);
	  	}
	);

});
