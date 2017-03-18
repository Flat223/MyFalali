$(document).ready(function(){
	var questflag = 0;
	$(".logout").on("click",function(){
		if(questflag == 1){
			return;
		}
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/logoutServ.html",
			success:function(data){
				if(questflag == 1){
					return;
				}
				questflag = 1;
				if(data.ret == 1){
					var exdate = new Date();
					exdate.setDate(exdate.getDate()-1);
					document.cookie = "auto_login=" + ";expires=" + exdate.toGMTString() + ";path=/";
					location.href="/member/login.html";
				}
			},
			complete:function(){
				questflag = 0;
			}
		});
	});
});