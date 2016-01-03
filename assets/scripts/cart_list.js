function chkStock(){
	var result = true;
	$("td.cart_list_td").each(function(index, element) {
		var product_id = $(this).children('#numSelect').attr("product_id");
		var num = $(this).children('select').find("option:selected").text();
		var color = $(this).attr("color");
		console.log("[chkStock] num = " + num +" color = " + color);

		var res = chkProductStock(product_id, num, color);
		if(!res){
			result = false;
		}
	});
	return result;
}
$(document).ready(function(e) {
	var isHaveBag = $('input[name="haveBag"]').val();
	var totalPrice = $('input[name="totalPrice"]').val();
	var wayOption = $('input[name="recipient_wayOption"]').val();
	var freightLimit = 1000;
	var freight = 0;
	var promo_code = "";
	var disSum = $('input[name="discharge_amount"]').val();
	//console.log(totalPrice + "   " + freightLimit + "    " +wayOption);
	if(wayOption < 3 && totalPrice >= 1000 )
	{
		freight = 0;
	}
	else if(wayOption == 1)
		freight = 60;
	else if(wayOption == 2)
		freight = 100;
	else if(wayOption == 3 && !isHaveBag)
		freight = 200;
	else if(wayOption == 3 && isHaveBag)
		freight = 580;
	else if(wayOption == 4 && !isHaveBag)
		freight = 460;
	else if(wayOption == 4 && isHaveBag)
		freight = 610;
	totalPrice = parseInt(totalPrice) + freight;
	$('.freight').replaceWith("<td class='freight'>"+freight+"</td>");
	//$('.total').replaceWith("<td class='total'>"+totalPrice+"</td>");

	$(".cart_del_btn").click(function(e) {
		var cart_id = $(this).attr("cart_id");
		var num = 0;
		updateCar(cart_id, num);
		location.reload();
    });
	
	$("span.cost").click(function(e) {
		var cart_id = $(this).attr("cart_id");
		var sell_price = $(this).parent("td").siblings("td.sell_price_td").find("span.sell_price").text();
		
		var num = parseInt($(this).siblings(".num").text());
		var total_price = 0;
		if(num >= 2){
			num -= 1;
			$(this).siblings("span.num").text(num);
		}
		$(this).parent("td").siblings("td.subtotal_price_td").find("span.subtotal_price").text(num*sell_price);
		$("td.subtotal_price_td").each(function(index, element) {
            var subtotal_price = parseInt($(this).find("span.subtotal_price").text());
            total_price += subtotal_price;
        });
		$("span.total_price").text(total_price);
		//console.log(total_price);
    });
	
	$("span.plus").click(function(e) {
		var cart_id = $(this).attr("cart_id");
		var sell_price = $(this).parent("td").siblings("td.sell_price_td").find("span.sell_price").text();
		var total_price = 0;
		var num = parseInt($(this).siblings(".num").text());
		num += 1;
		$(this).siblings("span.num").text(num);
		$(this).parent("td").siblings("td.subtotal_price_td").find("span.subtotal_price").text(num*sell_price);
		$("td.subtotal_price_td").each(function(index, element) {
			var subtotal_price = parseInt($(this).find("span.subtotal_price").text());
            total_price += subtotal_price;
        });
		$("span.total_price").text(total_price);
		//console.log(total_price);
    });
	
	$("#update_cart_btn").click(function(e) {
		if(chkStock()){
			//alert("hi");
			$("td.cart_list_td").each(function(index, element) {
				var cart_id = $(this).find("span.cost").attr("cart_id");
				var num = $(this).find("span.num").text();
				updateCar(cart_id, num);
        	});
		}
		location.reload();
    });

    $("td.cart_list_td select").change(function(e)
    {
    	//console.log("change!");
    	var cart_id = $(this).attr("cart_id");
		var sell_price = $(this).parent("td").siblings("td.sell_price_td").find("span.sell_price").text();
		var total_price = 0;
    	var num = $(this).find('option:selected').text();
    	//console.log("number select : sell_price = " + sell_price + "num = " + num);
    	$(this).parent("td").siblings("td.subtotal_price_td").find("span.subtotal_price").text(num*sell_price);
		$("td.subtotal_price_td").each(function(index, element) {
			var subtotal_price = parseInt($(this).find("span.subtotal_price").text());
            total_price += subtotal_price;
        });
		$("span.total_price").text(total_price);
    });
	
	$("#cheakout_btn").click(function(e) {
		if(chkStock()){
			$("td.cart_list_td").each(function(index, element) {
				var cart_id = $(this).children('select').attr("cart_id");
				//var cart_id = $(this).find("select.cost").attr("cart_id");
				//var num = $(this).find("span.num").text();
				var num = $(this).children('select').find('option:selected').text();
				updateCar(cart_id, num);
        	});
			location.href = "order-step2.html";
		}else{
			//location.reload();
		}
    });
	
	
	/*****order-step2驗證*****/	
	var myMember = new coderMember();
	
	$.validator.addMethod("isMobile", function(value, element) {
		var length = value.length;
		return this.optional(element) || (length == 10 && /^09[0-9]{8}$/.test(value));
	}, "請正確填寫手機號碼");

	$.validator.addMethod("isChooseStore", function(value, element) {
		return this.optional(element) || value != "選擇門市";
	}, "請選擇取貨門市");

	$.validator.addMethod("isFamily", function(value, element, param) {
		return $(param).val() == "TFM";
	}, "請選擇全家門市");
	
	var showErrors = function(errorMap, errorList){
		// Clean up any tooltips for valid elements
		$.each(this.validElements(), function (index, element){
			var $element = $(element);
			$element.data("title", "") // Clear the title - there is no error associated anymore
					.removeClass("error")
					.tooltip("destroy");
		});
		// Create new tooltips for invalid elements
		$.each(errorList, function (index, error){
			var $element = $(error.element);
			
			$element.tooltip("destroy")// Destroy any pre-existing tooltip so we can repopulate with new tooltip content
					.data("title", error.message)
					.addClass("error")
					.tooltip(); // Create a new tooltip based on the error messsage we just set in the title
		});
	}
	if (document.getElementById("order_pay_form")) {
		$('#order_pay_btn').click(function() {
			$('#order_pay_form').submit();
		});
		//$('#join_member_table').tooltip();
		$("#order_pay_form").validate({
			rules: {
				recipient_name: {
					required: true
				},
				recipient_mobile: {
					required: true,
					isMobile: true
				},
				recipient_email: {
					required: true,
					email: true
				},
				recipient_address: {
					required: true
				},
				payment_type: {
					required: true
				},
				recipient_wayOption: {
					required: true
				},
				ezship_choose: {
					isChooseStore: true,
					isFamily: '#ezship_cate'
				},
				discharge: {
					required: false,
				}
			},
			messages: {
				recipient_name: {
					required: "請輸入收件人姓名"
				},
				recipient_mobile: {
					required: "請輸入收件人手機號碼"
				},
				recipient_email: {
					required: "請輸入收件人的email",
					email: "請填寫正確的Email格式"
				},
				recipient_address: {
					required: "請輸入收件人地址"
				},
				payment_type: {
					required: "請選擇付款方式"
				},
				recipient_wayOption: {
					required: "請選擇運送方式"
				}
			},

			showErrors: showErrors,
			
			submitHandler: function(form) {
				//alertify.log("訂單送出中...請稍待!");
				$('#order_pay_btn').hide();
				var act = "do/order_save.php";
				var data = $("#order_pay_form").serialize();
				$.ajax({
					url : act,
					cache : false,
					data : data,
					type : 'POST',
					//async : false,
					dataType : "json",
					success : function(response){
						//console.log(response);
						if(response.result){
							if(response.payment_type == 1){//刷卡
								location.href = "do/payment.html";
							}
							if(response.payment_type == 2){//ATM
								location.href = "do/paymentatm.php";
							}
							if(response.payment_type == 3){//超商代收
								location.href = "do/paymentcs.php";
							}
							if(response.payment_type == 4){//7-11ibon / 全家FamiPort / 萊爾富Life-ET / OK 超商OK-go'
								location.href = "do/paymentmmk.php";
							}
							if(response.payment_type == 9){//貨到付款
								location.href = "http://www.lazycatshop.com/order-step3.html";
							}
						}else{
							//alert(response.result);
							alert(response.message);
							$('#order_pay_btn').show();
						}
					},
					error : function(xhr, ajaxOptions, thrownError){
						alert("讀取資料時發生錯誤,請梢候再試"+thrownError);
					}
				});
				/*//var myMember = new coderMember();
				var _item = new Object();
				_item.account = $('#acc').val();
				_item.password = $('#pwd').val();
				_item.name = $('#name').val();
				_item.email = $('#email').val();
				_item.birthday = $('#ymd_year').val()+padLeft($('#ymd_month').val(), 2)+padLeft($('#ymd_day').val(), 2);
				_item.mobile = $('#mobile').val();
				_item.address = $('#address').val();
				//console.log(_item.account);
				setTimeout(
					function() {
						if (myMember.insert(_item, 'insert')) {
							alert("會員加入完成!");
							if(myMember.MemberLogin(_item)){
								window.location.href = "index.html";
							}
						} else {
							$('#order_pay_btn').show();
							alert("會員加入失敗！");
						}
					}, 1000);*/
			}
		});
	}
	$('#recipient_optionElse').hide();
	$('#recipient_optionTW').show();
	
	$('#recipient_way').change(function() {
		var selectVal = $( "#recipient_way option:selected" ).val();
		if (selectVal == 1)
		{
			$('#recipient_optionTW').show();
			$('#recipient_optionElse').hide();
			$('.chk-way').show();
			$('.buy_hint').show();
		}
		else if(selectVal == 2)
		{
			$('#recipient_optionTW').hide();
			$('#recipient_optionElse').show();
			$('.chk-way').not(':eq(0)').hide();
			$('.buy_hint').hide();
		}
	});
	//$('#ezship_choose').hide();
	$('input[name="recipient_wayOption"]').change(function() {
		var isHaveBag = $('input[name="haveBag"]').val();
		var totalPrice = $('input[name="totalPrice"]').val();
		var freightLimit = 1000;
		var freight = 0;
		var disSum = $('input[name="discharge_amount"]').val();
		if(this.value == 1)
		{
			$('#ezship_choose').show();
			/*$('input[name="ezship_choose"]').val("選擇門市");
			$('input[name="ezship_name"]').val("選擇門市");
			$('input[name="ezship_cate"]').val("TFM");
			$('input[name="ezship_code"]').val("0");*/
		}
		else
		{	
			$('#ezship_choose').hide();
			/*$('input[name="ezship_choose"]').val("無");
			$('input[name="ezship_name"]').val("無");
			$('input[name="ezship_cate"]').val("TFM");
			$('input[name="ezship_code"]').val("0");*/
		}
		//console.log(totalPrice + "   " + freightLimit);
		if(this.value < 3 && totalPrice >= 1000 )
		{
			freight = 0;
		}
		else if(this.value == 1)
			freight = 60;
		else if(this.value == 2)
			freight = 100;
		else if(this.value == 3 && !isHaveBag)
			freight = 200;
		else if(this.value == 3 && isHaveBag)
			freight = 580;
		else if(this.value == 4 && !isHaveBag)
			freight = 460;
		else if(this.value == 4 && isHaveBag)
			freight = 610;
		totalPrice = parseInt(totalPrice) + freight;
		$('.freight').replaceWith("<td class='freight'>"+freight+"</td>");
		$('.total').replaceWith("<td class='total'>"+totalPrice+"</td>");
		chkPromoCode(totalPrice, promo_code);
		calTotalPrice(totalPrice, promo_code, disSum);
	});
	$('#promo_money').hide();
	$('#promo_discount').hide();

	$('input[name="recipient_promoCode"]').change(function() {
		var totalPrice = $('input[name="totalPrice"]').val();
		var disSum = $('input[name="discharge_amount"]').val();
		chkPromoCode(totalPrice, this.value);
		promo_code = this.value;
		calTotalPrice(totalPrice, promo_code, disSum);
	});
});
