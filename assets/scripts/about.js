$(document).ready(function(e) {
	 $("#content").val("your message...");
	
	$.validator.addMethod("is_email", function(value, element) {
		var email = $('#email').val();
		return this.optional(element) || (email == "your email") ? false : true;
	}, "請填寫您的Email");
	
	$.validator.addMethod("is_content", function(value, element) {
		var content = $('#content').val();
		return this.optional(element) || (content == "your message...") ? false : true;
	}, "請填寫您的內容");
	
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
	
	$('#cont_btn').click(function() {
			$('#contact_form').submit();
		});
	$("#contact_form").validate({
			rules: {
				email: {
					required: true,
					is_email: true,
					email: "請填寫正確的Email格式"
				},
				content: {
					required: true,
					is_content: true
				}				
			},
			messages: {
				email: {
					required: '請填寫您的email',
					email: "請填寫正確的Email格式"
				},
				content:{
					required: '請填寫您的內容'
				}
			},

			showErrors: showErrors,
			submitHandler: function(form) {
				$('#cont_btn').hide();
				$.ajax({
					url : "do/contact_save.html",
					//async : false,
					cache: false,
					type : "POST",
					data : {email : $("#email").val(), 
							content : $("#content").val()
					},
					dataType : "json",
					success : function(data){
						if(data.sResult){
							alert(data.msg);
						}else{
							alert(data.msg);
						}
					},
					error : function(xhr, ajaxOptions, thrownError){
						alert("讀取資料時發生錯誤,請梢候再試"+thrownError);
					},
					complete : function(xhr,status){
						$('#cont_btn').show();
					}
				});
			}
		});
	
});
