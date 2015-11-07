$(document).ready(function(e) {
    demo();
	IMG_scroll('.hot_box','.hot_left','.hot_right','.hot_list',3,false);
	
	$(".numSelect").find(".num>li").click(function() {
		var isVal = $(this).text();
		$(".numSelect").find(".beSelect").text(isVal);
		var order = $(this).attr("isval");
		var style_id = $("#style_id").val();
		var serial_id = $("#serial_id").val();
		var max_value_price = $("#max_value_price").val();
		var min_value_price = $("#min_value_price").val();
		var page = $("#page").val();
		var keyword = $("#keyword").val();
		if(keyword != ""){
			//alert('order='+order+'/keyword='+keyword);
			location.href = "style-list.php?keyword="+keyword+"&max_value_price="+max_value_price+"&min_value_price="+min_value_price+"&order="+order+"&page="+page;
		}else{
			location.href = "style-list.php?style_id="+style_id+"&serial_id="+serial_id+"&max_value_price="+max_value_price+"&min_value_price="+min_value_price+"&order="+order+"&page="+page;
		}
		
	});
	
	$("div.pro_list").hover(
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
