$(document).ready(function(e) {
    demo();
	prodShow();
	IMG_scroll('.hot_box','.hot_left','.hot_right','.hot_list',3,false);
	
	$(".numSelect").find(".num>li").click(function() {
		var isVal = $(this).text();
		$(".numSelect").find(".beSelect").text(isVal);
	});
	
	$("#wish_btn").click(function(e) {
		var pro_id = $(this).attr("pro_id");
		var type_id = $(this).attr("type_id");
		var member_id = $(this).attr("member_id");
		var page = $(this).attr("page");
		if(isLogin()){
			$.ajax({
				url : "do/product_wish.html",
				async : false,
				cache: false,
				type : "POST",
				data : {pro_id : pro_id, member_id : member_id},
				dataType : "json",
				success : function(data){
						if(data.result=="true"){
							alert(data.msg);
						}else{
							alert(data.msg);
						}
				},
				error : function(xhr, ajaxOptions, thrownError){
					alert("讀取資料時發生錯誤,請梢候再試"+thrownError);
				}
			});
		}else{
			alert("請先登入會員!");
			location.href = "sign.html?page="+page+"&pro_id="+pro_id+"&type_id="+type_id;
		}
        
    });
	
	$("#buy_btn").click(function(e) {
		var product_id = $(this).attr("pro_id");
		var product_sno = $(this).attr("pro_sno");
		var type_id = $(this).attr("type_id");
		//var member_id = $(this).attr("member_id");
		var page = $(this).attr("page");
		var product_name_en = $(this).attr("name_en");
		var product_name_tw = $(this).attr("name_tw");
		var sell_price = $(this).attr("sell_price");
		var special_price = 0;
		var pic = $(this).attr("pic");
		var amount = $(".beSelect").text();
		var subtotal = (sell_price * amount);
		//console.log(subtotal);
		if(isLogin()){
			addCar(product_id, product_sno, special_price, sell_price, amount, subtotal, product_name_en, product_name_tw, pic);
		}else{
			alert("請先登入會員!");
			location.href = "sign.html?page="+page+"&pro_id="+product_id+"&type_id="+type_id;
		}
    });
	
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
