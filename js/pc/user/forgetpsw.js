define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var loginType = 1;
	var queryflag = 0;
	
	var totalTime = 60;
	var clickAble = true;
	var pre_mobile = "";
	var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
	
	var handle = {
		init:function(){
			$('#getCode').on('click',function(){
				if(!clickAble){
			        return;
			    }
			    clickAble = false;
				var mobile = $.trim($("#mobile").val());
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
			$("#confirmReset").on("click",function(){
				var mobile = $.trim($("#mobile").val());
				var validcode = $.trim($("#validcode").val());
				var password = $("#password").val();
				var confirmPsd = $("#confirmPsw").val();
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
			    if(password == ""){
					layer.alert('请填写密码',{offset:'200px'});
					return;
				}
				if(confirmPsd == ""){
					layer.alert('请确认密码',{offset:'200px'});
					return;
				}
				if(password != confirmPsd){
					layer.alert('两次密码输入不一致',{offset:'200px'});
					return;
				}
				if(password.length < 6){
					layer.alert('密码长度不低于6位',{offset:'200px'});
					return;
				}
			    
				handle.resetPsw(mobile,validcode,password);
				
			});
		},
		getDynamicCode:function(mobile){
			$.ajax({
		        type: "GET",
		        url: "http://www.ichuk.com/?api/sendsmsverifycode/e75ce5d42105d8e581327164f8e860/1",
		        data:{"stage":"实验圈","mobile":mobile,"platform":"WEB","usage":"forgetpsw"},
		        dataType: "json",
		        success : function(data){
		            if(data.ret == 1){
		                t = setInterval(function(){
		                    totalTime--;
		                    $('#getCode').text(totalTime+"秒后重新获取");
		                    if(totalTime == 0){
		                        $('#getCode').text("获取验证码");
		                        totalTime = 60;
		                        clickAble = true;
		                        clearTimeout(t);
		                    }
		                },1000);
		            } else {
			            layer.alert(data.msg,{offset:'200px'});
		                clickAble = true;
		                pre_mobile = "";
		            }
		        },
		        error:function(data){
		            layer.alert('服务器错误,请稍后再试',{offset:'200px'});
		            pre_mobile = "";
		        },
		    });
		},
		resetPsw:function(mobile,validcode,password){
			var params = {};
		    params.mobile = mobile;
		    params.validcode = validcode;
		    params.password = password;
		    
		    $.ajax({
				type:"post",
				dataType:"json",
				url:"/service/resetPswOnforgetServ.html",
				data:params,
				success:function(data){
					if(data.ret == 1){
						location.href = "/member/resetPswSuc.html";
					}else{
						layer.alert(data.msg,{offset:'200px'});
					}
				},
				error:function(data){
					layer.alert('服务器错误,请稍后再试',{offset:'200px'});
				},
				complete:function(){
					
				}
			});
		}
	};

	$(function(){
		handle.init();
	});
});