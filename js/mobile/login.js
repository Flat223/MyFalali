define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var loginType = 2;
	var queryflag = 0;
	var clickAble = true;
	var pre_mobile = "";
	var totalTime = 60;
	var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
	
	var handle = {
		init:function(){
			$('#dynamic_code').keyup(function(event){
				if(event.keyCode == 13){
					$('#login_btn').click();
				}
			});
			$(document).keyup(function(event){
				if(event.keyCode != 13){
					return;
				}
				if(loginType != 2){
					return;
				}
				var mobile = $.trim($("#mobile").val());
				var password = $("#password").val();
				if(mobile == "" || password == ""){
					return;
				}
				$('#login_btn').click();
			});
			
			$('.login_tit span').on('click',function(){
				$(this).addClass('select').siblings().removeClass('select');
				var curr_type = $(this).attr('login_type');
				if(loginType == curr_type){
					return;
				}
				if(curr_type == 2){
					$('.traditional_login').show();
					$('.dynamic_login').hide();
				} else {
					$('.traditional_login').hide();
					$('.dynamic_login').show();
				}
				loginType = curr_type;
			});
			$('#dynamic_code_fetch').on('click',function(){
				if(!clickAble){
			        return;
			    }
			    clickAble = false;
				var mobile = $.trim($("#mobile2").val());
				if(mobile == ""){
					layer.alert('请填写手机号',{offset:'200px'});
					clickAble = true;
					return;
				}
				if(!reg.test(mobile)) {
				    layer.alert('请填写正确的手机号码',{offset:'200px'});
			        clickAble = true;
			        return;
			    }
			    pre_mobile = mobile;
				handle.getDynamicCode(mobile);
			});
			
			$("#login_btn").on("click",function(){
				if(queryflag == 1){
					return;
				}
				var data = {};
				data.logintype = loginType;
				if(loginType == 1){
					var mobile = $.trim($("#mobile2").val());
					var validcode = $.trim($("#dynamic_code").val());
					if(mobile == ""){
						layer.alert('请填写手机号',{offset:'200px'});
						return;
					}
					if(validcode == ""){
						layer.alert('请填写验证码',{offset:'200px'});
						return;
					}
					if(pre_mobile != "" && pre_mobile != mobile){
					    layer.alert('验证码错误',{offset:'200px'});
				        return;
				    }
				    data.mobile = mobile;
				    data.validcode = validcode;
				}else{
					var mobile = $.trim($("#mobile").val());
					var password = $("#password").val();
					if(mobile == ""){
						layer.alert('请填写手机号',{offset:'200px'});
						return;
					}
					if(password == ""){
						layer.alert('请填写密码',{offset:'200px'});
						return;
					}
					data.mobile = mobile;
					data.password = password;	
				}
				queryflag = 1;
				$("#login_btn").val("正在登录...");
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/loginServ.html",
					data:data,
					success:function(data){
						if(data.ret == 1){
							var redirect = $('input[name=redirect]').val();
							if(redirect == ""){
								window.location.href = "/personal/personalCenter.html";
							} else {
								window.location.href = redirect;
							}
						}else{
							layer.alert(data.msg,{offset:'200px'});
						}
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					},
					complete:function(){
						queryflag = 0;
						$("#login_btn").val("立即登录");
					}
				});
		    });
		},
		
		getDynamicCode:function(mobile){
			$.ajax({
		        type: "GET",
		        url: "http://www.ichuk.com/?api/sendsmsverifycode/e75ce5d42105d8e581327164f8e860/1",
		        data:{"stage":"实验圈","mobile":mobile,"platform":"WEB","usage":"mobileLogin"},
		        dataType: "json",
		        success : function(data){
		            if(data.ret == 1){
		                t = setInterval(function(){
		                    totalTime--;
		                    $('#dynamic_code_fetch').text(totalTime+"秒后重新获取");
		                    if(totalTime == 0){
		                        $('#dynamic_code_fetch').text("获取验证码");
		                        totalTime = 60;
		                        clickAble = true;
		                        clearTimeout(t);
		                    }
		                },1000);
		            }else{
			            layer.alert(data.msg,{offset:'200px'});
		                clickAble = true;
		                pre_mobile = "";
		            }
		        },
		        error:function(data){
		            layer.alert('服务器错误,请稍后再试',{offset:'200px'});
		            pre_mobile = "";
		            clickAble = true;
		        },
		    });
		},
	}	
	
	$(function(){
		handle.init();
	});
});