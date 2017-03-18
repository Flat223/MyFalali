define(function(require){
	var $ = require('jquery');
	require('pc/calendar');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var url = "../user/order.html?";
	var handle = {
		init:function(){
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				if(page == ""){
					return;
				}
				var baseurl = $('input[name=baseurl]').val();
				window.location.href = baseurl+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			
			$(".handle_order").on('click',function(){
				var order_code = $(this).attr('order_code');
				var type = $(this).attr('agreement');
				var msg = type == 1 ? "确定通过此订单" : "确定拒绝此订单";
				var alert = layer.confirm(msg,{
								title:"温馨提示",
								btn:["确认","取消"]
							},function(){
								layer.close(alert);
								handle.handleOrder(order_code,type);
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
			
			$('span[name=orderSearch]').on('click',function(){
			    var condition = handle.setLocationUrl();
			    var agree = $.trim($('#status_list li.this_or').val());	
			    condition += '&agree='+agree;
			    
			    if(condition == ""){
				    layer.alert('请填写搜索条件',{offset:'200px'});
			    } else {
					window.location.href = url + condition;
			    }
			});
			
			$('#status_list li').click(function(){
			    var condition = handle.setLocationUrl();
			    var agree = $.trim($(this).val());	
			    condition += '&agree='+agree;
			    window.location.href = url + condition;
			});
		},
		
		setLocationUrl:function(){
			var order_code=$.trim($('input[name=order_code]').val());
		    var applier=$.trim($('input[name=applier]').val());
		    var product=$.trim($('input[name=product]').val());
		    var start_time=$.trim($('#start_time').val());
		    var end_time=$.trim($('#end_time').val());
		   
		    var condition = "";
		    if(order_code != ""){
		        condition+= 'order_code='+order_code;
		    }
		    if(applier != ""){
		        condition+= '&applier='+applier;
		    }
		    if(product != ""){
		        condition+= '&product='+product;
		    }
		    if(start_time != ""){
		        condition+= '&start_time='+start_time;
		    }
		    if(end_time != ""){
		        condition+= '&end_time='+end_time;
		    }
		    
		    return condition;
		},
		
		handleOrder:function(order_code,type){
			var params = {};
			params.order_code = order_code;
			params.type = type; 	
		    $.ajax({
		        type: "POST",
		        url: "/service/handleOrderServ.html",
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
