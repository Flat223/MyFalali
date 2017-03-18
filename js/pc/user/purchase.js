define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});

	var order_type = $("input[name=order_type]").val();//1:公司采购订单 2:高校订单 3:个人订单
	
	var url = "";
	if(order_type == 1){//公司采购订单
		url = "../user/purchase.html?";
	} else if(order_type == 2){//高校订单
		url = "../user/collegeOrder.html?";
	} else {//个人订单
		url = "../user/personalOrder.html?";
	}
	
	var handle = {
		init:function(){
			$(".selectAll").on('click',function(){
				var boxes = $("input[type=checkbox]");
				for(i=0;i<boxes.length;i++){
		        	boxes[i].checked = $(this).is(':checked');
		        }
			});
			
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
			
			$(".paySelect").on('click',function(){
				var boxes = document.getElementsByName("seleSingle");
				var seleOrder = "";
				var isSelect = false;
				for(i=0;i<boxes.length;i++){
					if(boxes[i].checked == true) {
						isSelect = true;
						if($(boxes[i]).attr('state') == 1){
							if(seleOrder == ""){
								seleOrder = $(boxes[i]).attr('order_code');
							} else {
								seleOrder += "," + $(boxes[i]).attr('order_code');
							}	
						}
					}
				}
				if(isSelect == false){
					layer.alert('请选择订单进行操作,可以选择多个',{offset:'200px'});
					return;
				}
				if(seleOrder == ""){
					layer.alert('没有需要付款的订单',{offset:'200px'});
					return;
				}
				window.location.href="/pay/confirmation.html?codes="+seleOrder+"&otype=1";
			});
			
			$(".cancel").on('click',function(){
				var order_code = $(this).attr('order_code');
				var alert = layer.confirm("您确定取消订单吗?取消订单后不能恢复哦",{
								title:"温馨提示",
								btn:["确认","取消"]
							},function(){
								layer.close(alert);
								handle.operateOrder(order_code,1);
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
				var state = $(this).attr('state');
				var order_code = $(this).attr('order_code');
				var str = "type="+order_type+"&order_code="+order_code;
				if(state == 11){
					var pid = $(this).attr('pid');
					str = str + "&pid="+pid;
				} 
				
				window.location.href = "/order/detail.html?" + str;
			});
			$(".handle_order").on('click',function(){
				var order_code = $(this).attr('order_code');
				var pid = $(this).attr('pid');
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
				} else if(state == 4){
					window.location.href="/order/comment.html?pid=" + pid +"&order_code="+order_code;
				} else if(state == 7){
					window.location.href = "/order/detail.html?type="+order_type+"&order_code="+order_code;
				} else if(state == 11){
					window.location.href = "/order/refund.html?order_code="+order_code+"&pid="+pid;
				}
			});
			
			$('span[name=orderSearch]').on('click',function(){
			    var condition = handle.setLocationUrl();
			    var state = $('#status_list li.this_or').val();
				condition += '&state='+state;
				
			    if(condition == ""){
				    layer.alert('请填写搜索条件',{offset:'200px'});
			    } else {
					window.location.href = url + condition;
			    }
			});
			
			$('#status_list li').click(function(){
			    var condition = handle.setLocationUrl();
			    var state = $(this).val();
				condition += '&state='+state;
			    window.location.href = url + condition;
			});
		},
		
		setLocationUrl:function(){
			var order_code=$.trim($('input[name=order_code]').val());
		    var product=$.trim($('input[name=product]').val());
		    var start_time=$.trim($('#start_time').val());
		    var end_time=$.trim($('#end_time').val());
		    
		    var condition = "";
		    if(order_code != ""){
		        condition+= 'order_code='+order_code;
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
		
		remindMerchant:function(code){
			$.ajax({
				type: "POST",
		        url: "/service/remindMerchantServ.html",
		        data: {'code':code},
		        dataType: "json",
		        success:function(data){
				    if(data.ret==1){
			            layer.alert(data.msg,{offset:'200px'},function(){
				        	window.location.reload(true);    
			            });   
		            }else{
		                layer.alert(data.msg,{offset:'200px'});
		            }
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
			            layer.alert(data.msg,{offset:'200px'},function(){
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