function demo(a) {
    $(".close").click(function() {
        $(".pop").hide();
    });

    $(document).click(function(e) {
        //console.log($(e.target));
        if ($(e.target).hasClass('numSelect')) {
            $(".numSelect").css("overflow", "hidden");
            $(e.target).css("overflow", "visible");
            $(e.target).find(".num>li").click(function() {
                var isVal = $(this).text();
                $(e.target).find(".beSelect").text(isVal);
            });
        } else {
            $(".numSelect").css("overflow", "hidden");
        }

    });

}
//商品列表 左右animate
function proSlider(){
    var proInd = 0,
        proLength = 0;
    $(".arrow-left").click(function(){
        proInd--;
        if(proInd<0){
            proInd = 0;
        }
        $(this).parent().find(".new-outer .new-list").animate({"left":-(246*proInd)+"px"},500);
    });
    $(".arrow-right").click(function(){
        proLength = $(this).parent().find(".new-outer .new-list a").length-5;
        proInd++;
        if(proInd > proLength){
            proInd = proLength;
        }
        $(this).parent().find(".new-outer .new-list").animate({"left":-(246*proInd)+"px"},500);
        //console.log($(this).next(".new-outer"));
        return proLength;
    });
}


//商品換圖
function prodShow() {
    	var proInd = 0,
            proLength = $(".proImg-ist .prol-item").length-1;
        $(".prol-item").click(function() {
        	proInd = $(this).index();
            $('.proImg img').attr("src", $('.prol-item').find('img').attr('src'));
            $(".prol-item").removeClass('active');
            $(this).addClass('active');

        });
        $(".arrow-right").click(function(){
        	proInd++;
        	if(proInd>proLength){
        		proInd = proLength;
        	}
        	$('.proImg img').attr("src", $('.prol-item:eq('+proInd+')').find('img').attr('src'));
        	$(".prol-item").removeClass('active');
            $('.prol-item:eq('+proInd+')').addClass('active');
        });
        $(".arrow-left").click(function(){
        	proInd--;
        	if(proInd<0){
        		proInd = 0;
        	}
        	$('.proImg img').attr("src", $('.prol-item:eq('+proInd+')').find('img').attr('src'));
        	$(".prol-item").removeClass('active');
            $('.prol-item:eq('+proInd+')').addClass('active');
        });
        //console.log(proLength);
    }
$(document).ready(function(e) {

    demo();
    prodShow();
    proSlider();
    //金額篩選 jq-UI
    $("#slider-range").slider({
        range: true,
        min: 0,
        max: 4000,
        values: [300, 600],
        slide: function(event, ui) {
            $("#amount").val("TWD$" + ui.values[0] + " - TWD$" + ui.values[1]);
        }
    });
    $("#amount").val("TWD$" + $("#slider-range").slider("values", 0) +
        " - TWD$" + $("#slider-range").slider("values", 1));
    //maniBanner-slider
    var i = 0;
    $(".btn-right").click(function() {
        $("#banner").stopTime();
        //alert($("#banner").width());
        $(".banner-list img").fadeOut(500);
        $(".bang-item").delay(1000).removeClass('active');
        i++
        if (i > 4) {
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
            i = 4;
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
    function bannerAni() {
        $(".banner-list img").fadeOut(500);
        $(".bang-item").delay(1000).removeClass('active');
        i++;
        if (i > 4) {
            i = 0;
        }
        $(".banner-list img:eq(" + i + ")").fadeIn(2000);
        $(".bang-item:eq(" + i + ")").addClass('active');
    }
    $("#banner").everyTime(5000, bannerAni);

});
