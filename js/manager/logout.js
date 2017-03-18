$(document).ready(function(){
	$(".logout").on("click",function(){
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/logoutServ.html",
			success:function(data){
				if(data.ret == 1){
					location.href="/login.html";
				}
			},
			complete:function(){
				
			}
		});
	});
});
$(function () {
	$(".menu-wrapp li").on("click",function() {
		$(this).find("ul").css("display","block");
	});
});