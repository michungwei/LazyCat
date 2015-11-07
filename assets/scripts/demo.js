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
		
	$("#search_pic").click(function(e) {
        var keyword = $("#search_input input").val();
		if(keyword == ""){
			alert("請輸入關鍵字!");
		}else{
			location.href = "product-list.html?keyword="+keyword;
		}
		
    });
	$("#search_input input").focus(function(e) {
        $(document).keypress(function(e) {
            if(e.keyCode == 13){
				var keyword = $("#search_input input").val();
				if(keyword == ""){
					alert("請輸入關鍵字!");
				}else{
					location.href = "product-list.html?keyword="+keyword;
				}
			}
        });
    });
	$("#search_input input").blur(function(e) {
        $(document).keypress().unbind();
    });
	
	$("div.search").hover(
		function () {
			$("li.m_name").hide();
	  	},
	  	function () {
			$("li.m_name").show();
	  	}
	);
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
		//console.log(proInd);
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
function select_price(max_price, min_price, max_value_price, min_value_price, where){
    $("#slider-range").slider({
        range: true,
        min: min_price,
        max: max_price,
        values: [ min_value_price, max_value_price ],
        slide: function(event, ui) {
            $("#amount").val("TWD$" + ui.values[0] + " - TWD$" + ui.values[1]);
        },
		stop: function( event, ui ) {
			var type_id = $("#type_id").val();
			var style_id = $("#style_id").val();
			var serial_id = $("#serial_id").val();
			var max_value_price = ui.values[1];
			var min_value_price = ui.values[0];
			var order = $("#order").val();
			var page = $("#page").val();
			var keyword = $("#keyword").val();
			if(where == "list"){
				if(keyword != ""){
					location.href = "product-list.html?keyword="+keyword+"&max_value_price="+max_value_price+"&min_value_price="+min_value_price+"&order="+order+"&page="+page;
				}else{
					location.href = "product-list_"+type_id+"_"+serial_id+"_"+max_value_price+"_"+min_value_price+"_"+order+"_"+page+".html";
				}
				
			}else if(where == "new"){
				location.href = "product-new_"+type_id+"_"+max_value_price+"_"+min_value_price+"_"+order+"_"+page+".html";
			}
			else if(where == "style"){
				location.href = "style-list.php?style_id="+style_id+"&serial_id="+serial_id+"&max_value_price="+max_value_price+"&min_value_price="+min_value_price+"&order="+order+"&page="+page;			
			}
		}
    });
	
	$("#amount").val("TWD$" + $("#slider-range").slider("values", 0) + " - TWD$" + $("#slider-range").slider("values", 1));
		
}	
function set_ymd_date(year_start,year,month,day){
	var now = new Date();
 
	//年(year_start~今年)
	for(var i = now.getFullYear(); i >= year_start; i--){
		$('#ymd_year').
		append($("<option "+(i==year?'selected':'')+"></option>").
		attr("value",i).
		text(i));
	}
 
	//月
	for(var i = 1; i <= 12; i++){
		$('#ymd_month').
		append($("<option "+(i==month?'selected':'')+"></option>").
		attr("value",i).
		text(i));
	}
	
	if(year!=""&&month!=""&&day!=""){
		//日
		for(var i = 1; i <= 31; i++){
			$('#ymd_day').
			append($("<option "+(i==day?'selected':'')+"></option>").
			attr("value",i).
			text(i));
		}
	}
 
	$('#ymd_year').change(onChang_date);   
	$('#ymd_month').change(onChang_date);   
 
	//年、月選單改變時
	function onChang_date(){
		//$('#ymd_day').html("");
		if($('#ymd_year').val() != "" && $('#ymd_month').val() != ""){
 
			var date_temp = new Date($('#ymd_year').val(), $('#ymd_month').val(), 0);
 
			//移除超過此月份的天數
			$("#ymd_day option").each(function(){
				if($(this).val() != "" && $(this).val() > date_temp.getDate()) $(this).remove();
			});                
 
			//加入此月份的天數
			for(var i = 1; i <= date_temp.getDate(); i++){
				if(!$("#ymd_day option[value='" + i + "']").length){
					$('#ymd_day').
					append($("<option "+(i==day?'selected':'')+"></option>").
					attr("value",i).
					text(i));
				}
			}
		}else {
			$("#ymd_day option:selected").removeAttr("selected");
		}      
	}
}

function padLeft(str, lenght){
    if(str.length >= lenght){
        return str;
	}else{
        return padLeft("0"+str, lenght);
	}
}

function setToken(m_token, m_id){
	$.ajax({
		url : "do/set_token.php",
		cache : false,
		data : {m_token : m_token, m_id : m_id},
		type : 'POST',
		async : false,
		dataType : 'json',
		success : function(data){
			if(data.result){
				//return true;	
			}else{
					
			}
		}
	}); 
}

function isLogin(){
	var success=false;
	$.ajax({
		url: 'do/check_login.php',
		type: 'POST',
		dataType: "json",
		async:false,
		error: function(){
			alert('發生錯誤');
		},
		success: function(data){
			success= data.sResult;			
		}
	});		
	return success;
}

function logOut(){
	var myMember = new coderMember();
	if(myMember.MemberLogout()){
		location.href = "index.html";
		deleteCookie("m_id");	
	}else{
		alert(myMember.message);
	}
}
