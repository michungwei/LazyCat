/**
 * cully create 20120926
 * 購物車相關JS
 */
$.ajaxSetup({ cache: false });
var serviceurl="do/shoppingservice.php";
/**
 * 將商品相關資訊新增至購物車  
 * @param{productid} 商品編號
 * @param{productsno} 商品流水編號
 * @param{colorsno} 規格流水編號
 * @param{sizesno} 尺寸流水編號
 * @param{productcolorid} 商品顏色編號
 * @param{listprice} 商品定價
 * @param{sellingprice} 商品售價
 * @param{amount} 商品數量
 * @param{subtotal} 商品小計
 * @param{isshow} 是否顯示
 * @param{createtime} 建立時間
 */
function addCar(product_id, product_sno, special_price, sell_price, amount, subtotal, product_name_en, product_name_tw, pic, color){
	if(product_id > 0 && product_sno != ""){
		//alert("hi");
		$.ajax({
			url : serviceurl,
			async : false,
			type : "POST",
			data : {method : "add", 
					product_id : product_id, 
					product_sno : product_sno,
					special_price : special_price,
					sell_price : sell_price,
					amount : amount,
					subtotal : subtotal,
					product_name_en : product_name_en,
					product_name_tw : product_name_tw,
					pic : pic,
					color : color
			},
			dataType : "json",
			success : function(data){
						if(data.result){
							alert('商品已成功加入至購物車\n目前購物車總共有'+data.message+'項商品'+data.total+'元');
							$("li.cart_no span").html(data.message);
						//if(where==0){
//								alert('此商品已成功加入至購物車\n目前購物車總共有'+data[0].message+'項商品');
//								updateCarNum(data[0].message,data[0].total);
//						}
//						if(where==1){
//								alert('此商品已成功加入至購物車\n目前購物車總共有'+data[0].message+'項商品');
//								parent.updateCarNum(data[0].message,data[0].total);
//								//parent.slide();
//						}
//							
//							parent.$.fancybox.close();
						}else{
							alert(data.message);
						}
			}
		});
	}else{
		alert('參數資料傳送錯誤!');
	}
}

/*function updateCarNum(n,t){
	$('.carcount').html(n);
	$('.cartotal').html(t);
	$('.carprice').html(n);
	if(n>0){
	$('.shopcart').show();
	}else{
	$('.shopcart').hide();	
	}
	if(this==top){
		carItemShow();
	}
	else{
		parent.updateCarNum(n,t);
	}
	
}*/

function updateCar(cart_id, num){  //n:第幾樣商品; s=0:移除本列商品
	if(cart_id != ""){
		$.ajax({
			url : serviceurl,
			async : false,
			type : "POST",
			data : {method : "update",
					cart_id : cart_id,
					num : num
			},
			dataType:"json",
			success:function(data){
				if(data.result){
					//alert('此商品已成功刪除\n目前購物車總共有'+data[0].message+'項商品');
					updateCarState(data[0].message[0].amount,data[0].message[0].subtotal,data[0].total,data[0].message[0].freight,data[0].message[0].alltotal,data[0].message[0].discount,data[0].message[0].disbonus,n);
					if(num<=0 && s==0){
						removeCarLine(n,data[0].message[0].carnum,data[0].total);
					}
					else{
						updateCarNum(data[0].message[0].carnum,data[0].total);
					}
				}else{
					alert(data[0].message);
				}
			},
			error:function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}
		});		
	}else{
		alert('參數資料傳送錯誤!');
	}
}

function clearCar(){
	$.ajax({
		url : serviceurl,
		async : false,
		type : "POST",
		data : "method=clear",
		dataType : "json",
		success : function(data){
			if(data){
				if(data[0].result=="true"){

					}else{
						alert(data[0].message);
					}
				}
			}
		});
}

function chkProductStock(product_id, num, color){ 
	if(product_id != ""){
		var res = false;
		$.ajax({
			url : serviceurl,
			async : false,
			type : "POST",
			data : {method : "chkstock",
					product_id : product_id,
					num : num,
					color : color
			},
			dataType:"json",
			success:function(data){
				if(data.result){
					res = true;
				}else{
					alert(data.message);
				}
			},
			error:function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}
		});	
		return res;	
	}else{
		alert('參數資料傳送錯誤!');
	}
}

function chkPromoCode(totalPrice, promoCode){ 
	if(promoCode != ""){
		var res = false;
		$.ajax({
			url : serviceurl,
			async : false,
			type : "POST",
			data : {method : "chkPromoCode",
					promoCode : promoCode
			},
			dataType:"json",
			success:function(data){
				if(data.result){
					res = true;
					if(data.promo_money > 0)
					{
						$('#promo_money').show();
						$('.promo_money').replaceWith("<td class='promo_money'>- "+data.promo_money+"</td>");
					}
					else{
						$('#promo_money').hide();
					}
					if(data.promo_discount < 1)
					{
						$('#promo_discount').show();
						$('.promo_discount').replaceWith("<td class='promo_discount'>x "+data.promo_discount+"</td>");
					}
					else{
						$('#promo_discount').hide();
					}

					var total = Math.ceil((totalPrice - data.promo_money) * data.promo_discount);
					if(total > 0)
						$('.total').replaceWith("<td class='total'>"+total+"</td>");
					else
					{
						$('#promo_discount').hide();
						$('#promo_money').hide();
						$('input[name="recipient_promoCode"]').val("");
						alert("折扣後結帳金額為"+total+"，小於0無法做折扣!\n" + "[折扣金額為"+data.promo_money+" %數為"+data.promo_discount+"]");
					}

				}else{
					$('#promo_discount').hide();
					$('#promo_money').hide();
					$('input[name="recipient_promoCode"]').val("");
					alert(data.message);
				}
			},
			error:function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}
		});	
		return res;	
	}else{
		$('#promo_money').hide();
		$('#promo_discount').hide();
	}
}

function calTotalPrice(totalPrice, promoCode, discharge){ 

		var res = false;
		$.ajax({
			url : serviceurl,
			async : false,
			type : "POST",
			data : {method : "chkPromoCode",
					promoCode : promoCode
			},
			dataType:"json",
			success:function(data){
				if(data.result){
					res = true;
					var total = Math.ceil((totalPrice - data.promo_money) * data.promo_discount) - discharge;
				}else{
					$('#promo_discount').hide();
					$('#promo_money').hide();
					$('input[name="recipient_promoCode"]').val("");
					var total = totalPrice - discharge;
				}
				if(total > 0)
					$('.total').replaceWith("<td class='total'>"+total+"</td>");
				else
				{
					$('.total').replaceWith("<td class='total'>"+total+"</td>");
					$('#promo_discount').hide();
					$('#promo_money').hide();
					$('input[name="recipient_promoCode"]').val("");
					alert("折扣後結帳金額為"+total+"，小於0無法做折扣!\n" + "[折扣金額為"+data.promo_money+" %數為"+data.promo_discount+" 抵用金為"+discharge+"]");
				}
			},
			error:function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}
		});	
		return res;	
}


function updateCarState(amount,subtotal,total,freight,alltotal,discount,disbonus,n){
	$('#subtotal'+n).html(subtotal);
	$("#total").text(total);
	$("#freightt").text(freight);
	$("#freight").val(freight);
	$("#alltotal").text(alltotal);
	$("#totalprice").val(alltotal);
	$("#bonusreward").text(disbonus);
	
	showDisComment('totaltable',discount);
}	

function showDisComment(objid,data){
	$obj=$('#'+objid);
//	$first=$obj.find("tr:first"); 
//	$last=$obj.find("tr:last"); 
	$first=$obj.find("tr.line1"); 
	$second=$obj.find("tr.line2");
	$last=$obj.find("tr.line3"); 
	$obj.empty();
	$obj.append($first);
	for(var i=0,len=data.length;i<len;i++){
		var units=data[i].type==2 ? '紅利' : '-NT$';
		$obj.append('<tr> <td height="40" colspan="2" align="right"><span id="discountcomment">'+data[i].title+'</span></td><td height="40" align="left">優惠折扣<span class="txt14c_09f" style="padding-left:10px;">'+units+'<span id="discount">'+data[i].discount+'</span></span></td></tr>');
	}  
	$obj.append($second);
	$obj.append($last);
}

function removeCarLine(n,carnum,total){
	$obj=$('#sclist'+n);
	$obj.remove();
	parent.updateCarNum(carnum,total);
	if( $('.sclist').size() ==0){
		parent.$.fancybox.close();
	}
}

/*function getBuy(span_id){ 
	var spanobj = $("#"+span_id);
	var pro_id = spanobj.attr("pro_id");
	var pro_sno = spanobj.attr("pro_sno");
	var pro_name = spanobj.attr("pro_name");
	var pro_sellprice = spanobj.attr("pro_sellprice");
	var pro_amount = $("#"+span_id+" "+".amount").val();
	if(pro_amount == ""){
		pro_amount = 0;
	}
	var pro_subtotal = pro_sellprice*pro_amount;
	//alert (pro_subtotal);
	addCar(pro_id, pro_sno, pro_name, pro_sellprice, pro_amount, pro_subtotal);
}*/

function carItemShow(){
		$.ajax({
		url: 'caritem_show.php',
		async:false,
		type: 'POST',
		error: function(){
			alert('發生錯誤');
		},
		success: function(response){
			if(response){
				var show = response;
				$("#caritemshow").html(show);
				//$(".sizebox0").hide();
				slide();
				//setTimeout(function(){slide();},1000);
  		}else{
				$("#caritemshow").html("尚未選擇商品");
  		} 
		}
	});
}