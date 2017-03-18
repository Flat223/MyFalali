define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});

	var order_type = $("input[name=order_type]").val();//2:公司采购订单 3:高校订单 4:个人订单
	
	var url = "";
	url = "../personal/purchase.html";
	
	var handle = {
		init:function(){
			$('.pur_type a').on('click',function(){
			    var state = $.trim($(this).attr('state'));	
			    window.location.href = "../personal/order.html?state="+state+"&order_type="+order_type;
			});
			
			$(".operate").on('click',function(){
				var order_code = $(this).attr('order_code');
				var operate_type = $(this).attr('operate_type');
				var msg = (operate_type == 1) ? "确认取消此订单" : "确定退货";
				var alert = layer.confirm(msg,{
								title:"温馨提示",
								btn:["确认","取消"]
							},function(){
								layer.close(alert);
								handle.operateOrder(order_code,operate_type);
							});
			});
			
			
			$(".deleteOrder").on('click',function(){
				var order_code = $(this).attr('order_code');	
				var alert = layer.confirm("确认删除此订单",{
								title:"温馨提示",
								btn:["确认","取消"]
							},function(){
								layer.close(alert);
								handle.operateOrder(order_code,3);
							});
			});
			
			$(".detail").on('click',function(){
				var order_code = $(this).attr('order_code');
				location.href = "/order/detail.html?type="+order_type+"&order_code="+order_code;
			});
			
			$(".handle_order").on('click',function(){
				var order_code = $(this).attr('order_code');
				var state = $(this).attr('state');
				if(state == 1){//付款
					window.location.href="/pay/confirmation.html?codes="+order_code+"&otype=1";
				} else if(state == 2){//提醒卖家
					handle.remindMerchant(order_code);
				} else if(state == 3){
					var alert = layer.confirm("确认收货?",{
								title:"温馨提示",
								btn:["确认","取消"]
							},function(){
								layer.close(alert);
								handle.receiveGoods(order_code);
							});
				} else {//评价
					
				}
			});
		},
		
		remindMerchant:function(code){
			$.ajax({
				type: "POST",
		        url: "/service/RemindMerchantServ.html",
		        data: {'code':code},
		        dataType: "json",
		        success:function(data){
				   layer.alert(data.msg);
		        },
		        error:function(data){
			       layer.alert(data.msg,{offset:'200px'}); 
		        }
			});
		},
		
		operateOrder:function(order_code,type){
			var myUrl = "";
			if(type == 1){
				myUrl = "/service/cancelOrderServ.html";
			} else if(type == 2){
				myUrl = "/service/orderRefundServ.html";
			} else {
				myUrl = "/service/deleteOrderServ.html";
			}
			var params = {};
			params.order_code = order_code;
		    $.ajax({
		        type: "POST",
		        url: myUrl,
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
		
		receiveGoods:function(order_code){
			var params = {};
			params.order_code = order_code;
		    $.ajax({
		        type: "POST",
		        url:"/service/confirmReceiveGoods.html",
		        data: params,
		        dataType: "json",
		        success: function (data) {
		            if(data.ret==1){
			            layer.alert("已确认收货",{offset:'200px'},function(){
				        	window.location.reload(true);    
			            });   
		            }else{
		                layer.alert(data.msg,{offset:'200px'});
		            }
		        },
		        error: function (data) {
		            layer.alert('服务器错误,请稍后再试',{offset:'200px'});
		        }
		    });
		},
	};
	
	$(function(){
		handle.init();
	});
});