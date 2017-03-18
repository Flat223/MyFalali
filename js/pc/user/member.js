define(function(require){
	var $ = require('jquery');
	var layer;
	require('pc/calendar');
	require('/layui/layui.js');
	var type = 0; //1:月会员 2:季度会员 3:年会员
	
	var url = "../user/order.html?";
	var handle = {
		init:function(){
			if(window.layui){
				layui.config({
				  dir: '/layui/'
				});
				layui.use(['layer', 'element'], function(){
					layer = layui.layer;
				});
			}
			
			$(".sign").on("click",function(){
// 				var that = this;
				layer.open({
					type:1,
					title: $('.sign').text(),
					area: ['526px', '450px'],
					shade: 0.1,
					content: $(".openmember"),
// 					btn: ['继续弹出', '全部关闭'],
					yes: function(){
						
					}
				});
			});
			
			$(".date-choice li").on("click",function(){
				$(this).addClass("border-color").siblings().removeClass("border-color");
				type = $(this).attr('paytype');
			});
			
			$('.pay').on('click',function(){
				if(type == 0){
					layer.alert("请选择充值时间",{offset:'200px'});
					return;
				}
				var params = {};
				params.type = type;
				params.paytype = 1;//支付宝 
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/submitVipOrderServ.html",
					data:params,
					success:function(data){
						if(data.ret == 1){
							window.location.href="/pay/confirmation.html?codes="+data.code+"&otype=2";
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
