$(document).ready(function(){
	$('.reset').on('click',function(){
		location.reload(true);
	});
	
	$(".add").on('click',function(){
		var nickname = $.trim($('input[name=nickname]').val());	
		var realname = $.trim($('input[name=realname]').val());	
		var mobile = $.trim($('input[name=mobile]').val());	
		var psw = $.trim($('input[name=psw]').val());	
		var confirm_psw = $.trim($('input[name=confirm_psw]').val());	
// 		var rid = $("select[name=role] option:selected").attr('rid');
				
		var showAlert = true;
		var title = "";
		if(realname == ""){
			title = "请填写真实姓名";
		} else if(mobile == ""){
			title = "请填写手机号";
		} else if(psw == ""){
			title = "请填写密码";
		} else if(confirm_psw == ""){
			title = "请确认密码";
		} else if(confirm_psw != psw){
			title = "两次密码输入不一致";
		} else if(psw.length < 6){
			title = "密码长度不低于6位";
		} else {
			showAlert = false;
		}
		
		if(showAlert) {
			layer.alert(title,{offset:'200px'});
			return;
		}
		var params = {};
		params.nickname = nickname;
		params.realname = realname;
		params.mobile = mobile;
		params.password = psw;
		params.rid = $("select[name=role] option:selected").attr('rid');;
		
		$.ajax({
			type:"post",
			dataType:"json",
			data:params,
			url:"/service/addAdminServ.html",
			success:function(data){
				var alert = layer.alert(data.msg,{offset:'200px'},function(){
					if(data.ret == 1){
						window.location.href = "../usermanager/adminInfo.html";	
					} else {
						layer.close(alert);
					}
				});
			},
			error:function(data){
				layer.alert("服务器错误,请稍后再试",{offset:'200px'});
			},
			complete:function(){
				
			}
		});
	});
});