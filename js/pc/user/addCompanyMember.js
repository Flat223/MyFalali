define (function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		init:function(){			
			$(".add").on('click',function(){
				var params = {};
				var nickname = $.trim($('input[name=nickname]').val());	
				var realname = $.trim($('input[name=realname]').val());	
				var mobile = $.trim($('input[name=mobile]').val());	
				var psw = $.trim($('input[name=psw]').val());	
				var confirm_psw = $.trim($('input[name=confirm_psw]').val());	
				var sub_type = $("select[name=sub_ident] option:selected").attr('sub_type');
				
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
				
				params.nickname = nickname;
				params.realname = realname;
				params.sub_type = sub_type;
				params.mobile = mobile;
				params.password = psw;
				
				$.ajax({
					type:"post",
					dataType:"json",
					data:params,
					url:"/service/addCompanyMemberServ.html",
					success:function(data){
						if(data.ret == 1){
							layer.alert(data.msg,{offset:'200px'},function(){
								window.location.href = "../user/companyMember.html";
							});
						} else {
							layer.alert(data.msg,{offset:'200px'});
						}
					},
					error:function(data){
						layer.alert("服务器错误,请稍后再试",{offset:'200px'});
					},
					complete:function(){
						
					}
				});
			});
		}																									
	};
	
	$(function(){
		handle.init();
	});
});