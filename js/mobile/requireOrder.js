define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		init:function(){
			$('.pur_type a').on('click',function(){
			    var agree = $.trim($(this).attr('agree'));	
			    window.location.href = "../personal/requireOrder.html?agree="+agree;
			});
			
			$('input[name=agree]').on('click',function(){
				var order_code = $(this).parent().attr('order_code');
				var alert = layer.confirm("确定通过此订单",{
								title:"温馨提示",
								btn:["确认","取消"]
							},function(){
								layer.close(alert);
								handle.handleOrder(order_code,1);
							});
			});
			$('input[name=refuse]').on('click',function(){
				var order_code = $(this).parent().attr('order_code');
				var alert = layer.confirm("确定拒绝此订单",{
								title:"温馨提示",
								btn:["确认","取消"]
							},function(){
								layer.close(alert);
								handle.handleOrder(order_code,2);
							});
			});
			
			$(".deleteOrder").on('click',function(){
				var order_code = $(this).attr('order_code');
				var alert = layer.confirm("确定删除此订单",{
								title:"温馨提示",
								btn:["确认","取消"]
							},function(){
								layer.close(alert);
								handle.deleteOrder(order_code);
							});
			});
		},
				
		handleOrder:function(order_code,type){
			var params = {};
			params.order_code = order_code;
			params.type = type; 	
		    $.ajax({
		        type: "POST",
		        url: "/service/handleRequireOrderServ.html",
		        data: params,
		        dataType: "json",
		        success: function (data) {
		            if(data.ret==1){
		                window.location.reload(true);
		            }else{
		                layer.alert(data.msg,{offset:'200px'});
		            }
		        },
		        error: function (data) {
		            layer.alert('服务器错误,请稍后再试',{offset:'200px'});
		        }
		    });
		},
		
		deleteOrder:function(order_code){
			var params = {};
			params.order_code = order_code;	
		    $.ajax({
		        type: "POST",
		        url: "/service/deleteOrderServ.html",
		        data: params,
		        dataType: "json",
		        success: function (data) {
		            if(data.ret==1){
		                window.location.reload(true);
		            }else{
		                layer.alert(data.msg,{offset:'200px'});
		            }
		        },
		        error: function (data) {
		            layer.alert('服务器错误,请稍后再试',{offset:'200px'});
		        }
		    });
		}
	};
	
	$(function(){
		handle.init();
	});
});
