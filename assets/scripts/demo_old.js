function demo(a) {
    $(".close").click(function() {
        $(".pop").hide();
    });

	$(document).click(function(e) {
        //console.log($(e.target));
        if ($(e.target).hasClass('numSelect')) {
            $(".numSelect").css("overflow", "hidden");
            $(e.target).css("overflow", "visible");
        } else {
            $(".numSelect").css("overflow", "hidden");
        }
    });
	
}

function selectOrder(order){
	$(".num li").each(function(index, element) {
        if($(this).attr("isval") == order){
			$(".numSelect").find(".beSelect").text($(this).text());
		}
    });
}
//商品列表 左右animate
function IMG_scroll(wraper, prev, next, img, speed, or){ 
	var flag = "left"; 
	var wraper = $(wraper); 
	var prev = $(prev); 
	var next = $(next); 
	var img = $(img).find('ul'); 
	var w = img.find('li').outerWidth(true); 
	var s = speed; 
	next.click(function(){ 
		img.animate({'margin-left':-w},function(){ 
			img.find('li').eq(0).appendTo(img); 
			img.css({'margin-left':0}); 
		}); 
		flag = "left"; 
	}); 
	prev.click(function(){ 
		img.find('li:last').prependTo(img); 
		img.css({'margin-left':-w}); 
		img.animate({'margin-left':0}); 
		flag = "right"; 
	}); 
	if (or == true){ 
		ad = setInterval(function() { flag == "left" ? next.click() : prev.click()},s*1000); 
		wraper.hover(function(){clearInterval(ad);},function(){ad = setInterval(function() {flag == "left" ? next.click() : prev.click()},s*1000);}); 
	} 
} 

/*function proSlider(){
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
*/

//商品換圖
function prodShow() {
	var proInd = 0,
		proLength = $(".proImg-ist .prol-item").length-1;
	$(".prol-item").click(function() {
		proInd = $(this).index();
		console.log(proInd);
		$('.proImg img').attr("src", $(this).find('img').attr('src'));
		$(".prol-item").removeClass('active');
		$(this).addClass('active');
	});
	$(".arrow-right").click(function(){
		proInd++;
		if(proInd>proLength){
			//proInd = proLength;
			proInd = 0;
		}
		$('.proImg img').attr("src", $('.prol-item:eq('+proInd+')').find('img').attr('src'));
		$(".prol-item").removeClass('active');
		$('.prol-item:eq('+proInd+')').addClass('active');
	});
	$(".arrow-left").click(function(){
		proInd--;
		if(proInd<0){
			//proInd = 0;
			proInd = proLength;
		}
		$('.proImg img').attr("src", $('.prol-item:eq('+proInd+')').find('img').attr('src'));
		$(".prol-item").removeClass('active');
		$('.prol-item:eq('+proInd+')').addClass('active');
	});
        //console.log(proLength);
}
//金額篩選 jq-UI
function select_price(min_price, max_price){
    
    $("#slider-range").slider({
        range: true,
        min: min_price,
        max: max_price,
        values: [min_price, max_price],
        slide: function(event, ui) {
            $("#amount").val("TWD$" + ui.values[0] + " - TWD$" + ui.values[1]);
        },
		stop: function( event, ui ) {
			var type_id = $("#type_id").val();
			var serial_id = $("#serial_id").val();
			var height_price = ui.values[1];
			var low_price = ui.values[0];
			var order = $("#order").val();
			var page = $("#page").val();
			location.href = "product-list_"+type_id+"_"+serial_id+"_"+height_price+"_"+low_price+"_"+order+"_"+page+".html";	
		}
    });
	
	$("#amount").val("TWD$" + $("#slider-range").slider("values", 0) +
        " - TWD$" + $("#slider-range").slider("values", 1));
		
}	
