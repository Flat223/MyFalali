define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var queryflag = 0;
	
	var handle = {
		init:function(){
			$(document).keyup(function(event){
				if(event.keyCode == 13){
					$("#login_btn").trigger("click");
				}
			});
			$("#login_btn").on("click",function(){
				var mobile = $.trim($("#mobile2").val());
				var password = $("#password").val();
				if(mobile == ""){
					layer.alert('请填写手机号',{offset:'200px'});
					return;
				}
				if(password == ""){
					layer.alert('请填写密码',{offset:'200px'});
					return;
				}
				handle.login(mobile,password);
			});
		},
		login:function(mobile,password){
			if(queryflag == 1){
				return;
			}
			queryflag = 1;
			$("#login_btn").val("正在登录...");
			var params = {};
			params.mobile = mobile;
			params.password = password;
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/adminLoginServ.html",
				data:params,
				success:function(data){
					if(data.ret == 1){
						var redirect = $('input[name=redirect]').val();
						if(redirect == ""){
							window.location.href = "/index.html";
						} else {
							window.location.href = redirect;
						}
					} else {
						queryflag = 0;
						$("#login_btn").val("立即登录");
						layer.alert(data.msg,{offset:'200px'});
					}
				},
				error:function(data){
					queryflag = 0;
					$("#login_btn").val("立即登录");
					layer.alert('服务器错误,请稍后再试',{offset:'200px'});
				},
				complete:function(){
					queryflag = 0;
					$("#login_btn").val("立即登录");
				}
			});
		}
	};

	$(function(){
		handle.init();
	});
});