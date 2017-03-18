define(function(require,exports,module){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	
	var questflag = 0;
	var validFlag = 0;
	
	var handle = {
		
		init:function(){
			//	分类下拉
			var subLi = $(".cate_down>ul>li");
			var categrory = $(".categrory");
			//二级菜单
			categrory.hover(function () {
				$(this).find(".cate_down").show();
			},function () {
				$(this).find(".cate_down").hide();
			});
			//三级菜单
			subLi.hover(function () {
				$(this).addClass("active");
				$(this).find(".sub_menu").show();
			},function () {
				$(this).removeClass("active");
				$(this).find(".sub_menu").hide();
			});
			$(".category_nav .sub_nav li").on("click",function(){
				var target = $(this).attr("target");
				window.location.href = getHost()+target;
			});
			$("#login,#regist").on("click",function(){
				var id = $(this).attr("id");
				layer.open({
					type: 1,
					offset:['40px;','40%'],
					title:"登录/注册",
					skin: 'layui-layer-rim', //加上边框
					area: ['340px', '460px'], //宽高
					content: $(".login")
				});
				if(id == "login"){
					$(".login>ul>li[ref='quick_login']").click();
				}else{
					$(".login>ul>li[ref='quick_regist']").click();
				}
			});
			$(".login>ul>li").on("click",function(){
				if($(this).hasClass("act")){
					return;
				}
				$(this).addClass("act").siblings().removeClass("act");
				var ref = $(this).attr("ref");
				$("."+ref).show().siblings(".tab").hide();
				$("#login_label,#regist_label").text("").hide();
				$("#login_mobile,#login_psd").val("");
				$("#reg_mobile,#reg_validcode,#reg_psd,#reg_psd2").val("");
			});
			$("#login_btn").on("click",function(){
				if(questflag == 1){
					return;
				}
				var mobile = $.trim($("#login_mobile").val());
				var psd = $("#login_psd").val();
				if(mobile == ""){
					$("#login_label").text("请填写手机号码").show();
					return;
				}
				if(psd == ""){
					$("#login_label").text("请填写密码").show();
					return;
				}
				var params = {};
				params.mobile = mobile;
				params.password = psd;
				questflag = 1;
				$("#login_btn").val("正在登录...");
				$.ajax({
					type:"post",
					dataType:"json",
					url:getHost()+"/service/loginServ.html",
					data:params,
					success:function(data){
						if(data.ret == 1){
							location.reload(true);
						}else{
							$("#login_label").text(data.msg).show();
						}
					},
					error:function(data){
						$("#login_label").text("服务器错误,请稍后再试").show();
					},
					complete:function(){
						questflag = 0;
						$("#login_btn").val("登录");
					}
				});
			});
			$("#login_mobile,#login_psd").keypress(function(e){
				if(e.keyCode == 13){
					$("#login_btn").click();
				}
			});
			$("#reg_btn").on("click",function(){
				if(questflag == 1){
					return;
				}
				var mobile = $.trim($("#reg_mobile").val());
				var validcode = $.trim($("#reg_validcode").val());
				var psd = $("#reg_psd").val();
				var psd2 = $("#reg_psd2").val();
				var agree = $("#protocol_ag").prop("checked");
				if(mobile == ""){
					$("#regist_label").text("请填写手机号码").show();
					return;
				}
				if(validcode == ""){
					$("#regist_label").text("请填写短信验证码").show();
					return;
				}
				if(psd == ""){
					$("#regist_label").text("请填写密码").show();
					return;
				}
				if(psd2 == ""){
					$("#regist_label").text("请填写确认密码").show();
					return;
				}
				if(psd != psd2){
					$("#regist_label").text("两次密码填写不一致").show();
					return;
				}
				if(!agree){
					$("#regist_label").text("请同意《用户协议》").show();
					return;
				}
				var params = {};
				params.mobile = mobile;
				params.validcode = validcode;
				params.password = psd;
				questflag = 1;
				$("#reg_btn").val("正在注册...");
				$.ajax({
					type:"post",
					dataType:"json",
					url:getHost()+"/service/registServ.html",
					data:params,
					success:function(data){
						if(data.ret == 1){
							location.reload(true);
						}else{
							$("#regist_label").text(data.msg).show();
						}
					},
					error:function(data){
						$("#regist_label").text("服务器错误,请稍后再试").show();
					},
					complete:function(){
						questflag = 0;
						$("#reg_btn").val("注册");
					}
				});
			});
			$("#logout").on("click",function(){
				if(questflag == 1){
					return;
				}
				$.ajax({
					type:"post",
					dataType:"json",
					url:getHost()+"/service/logoutServ.html",
					success:function(data){
						if(data.ret == 1){
							location.reload(true);
						}
					},
					complete:function(){
						questflag = 0;
					}
				});
			});
			$("#reg_mobile,#reg_validcode,#reg_psd,#reg_psd2").keypress(function(e){
				if(e.keyCode == 13){
					$("#reg_btn").click();
				}
			});
			$("#valid_btn").on("click",function(){
				if(questflag == 1){
					return;
				}
				if(validFlag == 1){
					return;
				}
				var mobile = $.trim($("#reg_mobile").val());
				if(mobile == ""){
					$("#regist_label").text("请填写手机号码").show();
					return;
				}
				questflag = 1;
				validFlag = 1;
				$("#valid_btn").text("正在获取...");
				$.ajax({
					type:"post",
					dataType:"json",
					url:getHost()+"/service/queryValidCodeServ.html",
					data:{mobile:mobile},
					success:function(data){
						if(data.ret == 1){
							$("#regist_label").text("验证码已发送").show();
						}else{
							$("#regist_label").text(data.msg).show();
						}
					},
					error:function(data){
						$("#regist_label").text("服务器错误,请稍后再试").show();
					},
					complete:function(){
						questflag = 0;
						$("#valid_btn").addClass("inactive");
						handle.colddown();
					}
				});				
			});
			$("#fast_regist").on("click",function(){
				$(".login>ul>li[ref='quick_regist']").click();
			});
		},
		colddown:function(){
			var time = 60;
			var cold = setInterval(function(){
				if(time <= 0){
					validFlag = 0;
					$("#valid_btn").text("重新获取").removeClass("inactive");
					clearInterval(cold);
					return;
				}
				time--;
				$("#valid_btn").text(time+"秒后重新获取");
			}, 1000);
			
		}
	};
	(function(){
		handle.init();
	})();	
});