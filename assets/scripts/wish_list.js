function delWishList(w_id){
	$.ajax({
			url : "do/wish_del.html",
			//async : false,
			cache: false,
			type : "POST",
			data : {w_id : w_id},
			dataType : "json",
			success : function(data){
					if(data.result){
						//alert(data.msg);
						window.location.reload();
					}else{
						alert(data.msg);
					}
			},
			error : function(xhr, ajaxOptions, thrownError){
				alert("讀取資料時發生錯誤,請梢候再試"+thrownError);
			}
		});
}
$(document).ready(function(e) {
	demo();
	$(".wish_del").click(function(e) {
		var w_id = $(this).attr("w_id");
		$.ajax({
			url : "do/wish_del.html",
			//async : false,
			cache: false,
			type : "POST",
			data : {w_id : w_id},
			dataType : "json",
			success : function(data){
					if(data.result){
						alert(data.msg);
						window.location.reload();
					}else{
						alert(data.msg);
					}
			},
			error : function(xhr, ajaxOptions, thrownError){
				alert("讀取資料時發生錯誤,請梢候再試"+thrownError);
			}
		});
    });
	
	$(".wish_add_cart").click(function(e) {
		var w_id = $(this).attr("w_id");
		var w_color = $(this).attr("w_color");
		var product_id = $(this).attr("pro_id");
		var product_sno = $(this).attr("pro_sno");
		//var type_id = $(this).attr("type_id");
		//var member_id = $(this).attr("member_id");
		//var page = $(this).attr("page");
		var product_name_en = $(this).attr("name_en");
		var product_name_tw = $(this).attr("name_tw");
		var sell_price = $(this).attr("sell_price");
		var special_price = 0;
		var pic = $(this).attr("pic");
		var amount = 1;
		var subtotal = (sell_price * amount);
		var stock = $(this).parents("tr.list").find("span.num").text();
		//console.log(stock);
		if(isLogin()){
			if(stock > 0){
				addCar(product_id, product_sno, special_price, sell_price, amount, subtotal, product_name_en, product_name_tw, pic, w_color);
				delWishList(w_id);
			}else{
				alert("此商品庫存目前不足!");
			}
		}else{
			//alert("請先登入會員!");
			//location.href = "sign.html?page="+page+"&pro_id="+product_id+"&type_id="+type_id;
		}
        
    });
});
