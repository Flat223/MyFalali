define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	require('cyupload.js');
	
	var order_type = $('input[name=order_type]').val();	
	var order_code = $('input[name=order_code]').val();	
	
	var handle = {
		init:function(){
			$(".cancel").on('click',function(){
				var alert = layer.confirm("您确定取消订单吗?取消订单后不能恢复哦",{
							title:"温馨提示",
							btn:["确认","取消"]
						},function(){
							layer.close(alert);
							handle.cancelOrder();
						});
			});
			$(".pay").on('click',function(){
				window.location.href="/pay/confirmation.html?codes="+order_code+"&otype=1";
			});
			$(".remind").on('click',function(){
				handle.remindMerchant(order_code);
			});
			$(".refund").on('click',function(){
				
			});
			$(".confirm_accept").on('click',function(){
				var alert = layer.confirm("确认收货?",{
							title:"温馨提示",
							btn:["确认","取消"]
						},function(){
							layer.close(alert);
							handle.receiveGoods(order_code);
						});
			});
			$(".comment").on('click',function(){
				var pid = $(this).attr('pid');
				window.location.href="/order/comment.html?pid=" + pid +"&order_code="+order_code;
			});
			
			$.cyupload({
				elem: '#uploadimg',
				btnName: "重新上传",		//按键名称
				infoElementId: "",	//上传状态信息包装元素id
				maxFilesize: 10485760,
				uploadUrl: '/service/uploadimgServ.html',
				fileFilter: '',
				upfileParam: 'upload_file_input',
				success: function (url) {
					$('#voucher').attr('src', url['file_url']);
				}
			});
			 
			$("#voucher").click(function(){
				$("#uploadimg").find(".upload_file_btn").trigger("click");
			});
			
			$('input[name=submit_voucher]').on('click',function(){
				var voucherImg = $("#voucher").attr('src');
				console.log(voucherImg);
				if(voucherImg == "images/pc/b_bg_40.png" || voucherImg == ""){
					layer.alert("请先上传凭证",{offset:'200px'}); 
					return;
				}
				
				var params = {};
				params.voucherImg = voucherImg;
				params.order_code = order_code;
				$.ajax({
					type: "POST",
			        url: "/service/reUploadVoucherServ.html",
			        data: params,
			        dataType: "json",
			        success:function(data){
				        if(data.ret == 1){
						    layer.alert(data.msg,{offset:'200px'},function(){
							 	window.history.back();
						   	});  
				        } else {
					   		layer.alert(data.msg,{offset:'200px'});      
				        }
					   
			        },
			        error:function(data){
				       layer.alert(data.msg,{offset:'200px'}); 
			        }
				});
			});
		},	
		
		remindMerchant:function(code){
			$.ajax({
				type: "POST",
		        url: "/service/RemindMerchantServ.html",
		        data: {'code':code},
		        dataType: "json",
		        success:function(data){
				   layer.alert(data.msg,{offset:'200px'}); 
		        },
		        error:function(data){
			       layer.alert(data.msg,{offset:'200px'}); 
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
				        	window.history.back();
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
		
		cancelOrder:function(){
			var reloadUrl = "";
			if(order_type == 1){
				reloadUrl = "/user/purchase.html";
			} else if(order_type == 2){
				reloadUrl = "/user/collegeOrder.html";
			} else {
				reloadUrl = "/user/personalOrder.html";
			}
			var params = {};
			params.order_code = order_code;
		    $.ajax({
		        type: "POST",
		        url: "/service/cancelOrderServ.html",
		        data: params,
		        dataType: "json",
		        success: function (data) {
		            if(data.ret==1){
		                window.location.href = reloadUrl;
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