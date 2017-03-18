define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var expressArr;//快递公司arr
	var url = "../myshop/orderManager.html?";
	
	var handle = {
		init:function(){
			$ (".btn-close").on ('click', function () {
				$ ('#previewImg-modal2').hide ();
				$ (".modal-body").find ('p').empty ();
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
			
			$('.search').on('click',function(){
			    var condition = handle.setLocationUrl();
			    var state = $('#status_list li.chart-this').val();
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
			$('.payerInfo').on('click',function(){
				var mid = $(this).attr('mid');
				var aid = $(this).attr('aid');
				var params = {};
				params.aid = aid;
				params.mid = mid;
				console.log(params);
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/getBuyerInfoServ.html",
					data:params,
					success:function(data){
						if(data.ret == 1){
							userInfo = data.userInfo;
							handle.showBuyerInfoAlert(userInfo);
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
			});
			
			$('.refundReason').on('click',function(){
				var pid = $(this).attr('pid');
				var order_code = $(this).attr('order_code');
				var params = {};
				params.pid = pid;
				params.order_code = order_code;
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/GetOrderRefundInfoServ.html",
					data:params,
					success:function(data){
						if(data.ret == 1){
							var refundInfo = data.refund;
							handle.showRefundInfoAlert(refundInfo);
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
			});
			
			$('.sendGoods').on('click',function(){
				var order_code = $(this).parent().attr('order_code');
				layer.open({
		            type: 1
		            ,title: "设置物流信息"
		            ,area: '400px;'
		            ,shade: 0.5
		            ,btn: ["确定", "取消"]
		            ,btnAlign: 'c'
		            ,content: $('.popup_express')
		            ,success: function(layero){
		                var btn = layero.find('.layui-layer-btn');
		                btn.find('.layui-layer-btn0').click(function(){
			                var logistics = $("select[name=express] option:selected").text();
			                var logistics_code = $.trim($("input[name=express]").val());
			                
			                if(logistics_code == ""){
				                layer.alert("快递单号为空",{offset:'200px'});
				                return;
			                }
			                
			                handle.sendGoods(order_code,logistics,logistics_code);
		                });
		            }
		        });				
			});
			
			$(".check").on("click", function(){
				var voucher = $(this).attr("voucher");
				layer.open({
					type: 2,
					title: '订单线下支付凭证',
					shadeClose: true,
					shade: 0.5,
					area: ['720px', '80%'],
					content: '../myshop/images.html?images='+voucher
				});
			});
			
			$('.agree').on('click',function(){
				var order_code = $(this).parent().attr('order_code');
				var alert = layer.confirm("确认通过此线下付款凭证?", {
					title:"温馨提示",
					btn: ['确认','取消'] //按钮
				}, function(){
					layer.close(alert);
					handle.checkVoucher(order_code,1,"");
				}, function(){
					
				});
			});
			
			$('.refuse').on('click',function(){
				var order_code = $(this).parent().attr('order_code');
				layer.open({
		            type: 1
		            ,title: "拒绝线下付款凭证"
		            ,area: '400px'
		            ,shade: 0.5
		            ,btn: ["确定", "取消"]
		            ,btnAlign: 'c'
		            ,content: '<div style="padding: 20px; line-height: 22px; font-weight: 300;">\
					            请填写拒绝理由\
								<p style="margin-top: 10px">\
						            <textarea class="refuse_reason" cols="48" rows="7"\
						            	style="line-height: 25px;border:1px solid #ddd;padding-left:5px;">\
						            </textarea>\
						        </p>\
					            </div>'
		            ,success: function(layero){
		                var btn = layero.find('.layui-layer-btn');
		                btn.find('.layui-layer-btn0').click(function(){
			                var reason = $.trim($(".refuse_reason").val());
			                if(reason == ""){
				                layer.alert("拒绝理由为空",{offset:'200px'});
				                return;
			                }
			                handle.checkVoucher(order_code,2,reason);
		                });
		            }
		        });
			});
			
			$('.agreeRefund').on('click',function(){
				var order_code = $(this).parent().attr('order_code');
				var pid = $(this).parent().attr('pid');
				var alert = layer.confirm("确认同意该退款申请?", {
					title:"温馨提示",
					btn: ['确认','取消'] //按钮
				}, function(){
					layer.close(alert);
					handle.handleRefund(order_code,pid,1,"");
				}, function(){
					
				});
			});
			
			$('.refuseRefund').on('click',function(){
				var order_code = $(this).parent().attr('order_code');
				var pid = $(this).parent().attr('pid');
				layer.open({
		            type: 1
		            ,title: "拒绝退款申请"
		            ,area: '400px'
		            ,shade: 0.5
		            ,btn: ["确定", "取消"]
		            ,btnAlign: 'c'
		            ,content: '<div style="padding: 20px; line-height: 22px; font-weight: 300;">\
					            请填写拒绝理由\
								<p style="margin-top: 10px">\
						            <textarea class="refuse_reason" cols="48" rows="7"\
						            	style="line-height: 25px;border:1px solid #ddd;padding-left:5px;">\
						            </textarea>\
						        </p>\
					            </div>'
		            ,success: function(layero){
		                var btn = layero.find('.layui-layer-btn');
		                btn.find('.layui-layer-btn0').click(function(){
			                var reason = $.trim($(".refuse_reason").val());
			                if(reason == ""){
				                layer.alert("拒绝理由为空",{offset:'200px'});
				                return;
			                }
			                handle.handleRefund(order_code,pid,2,reason);
		                });
		            }
		        });
			});
		
		},
		
		setLocationUrl:function(){
			var order_code=$('input[name=order_code]').val();
		    var product=$('input[name=product]').val();
		    var start_time=$('input[name=start_time]').val();
		    var end_time=$('input[name=end_time]').val();
		    
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
		
		showBuyerInfoAlert:function(userInfo){
			var zip = userInfo.zip;
			if(userInfo.zip == 0){
				zip = '未填写';
			}
			var modal = $("#previewImg-modal2");
			var modalBody = modal.find('.modal-body');
			modalBody.empty ();
			var element = '';
			element += "<p><span>联系人：</span><span>" + userInfo.name + "</span></p>";
			element += "<p><span>省市区地址：</span><span>" + userInfo.province_name + "-" + userInfo.city_name + "-" + userInfo.country_name + "</span></p>";
			element += "<p><span>详细地址：</span><span>" + userInfo.detail_address + "</span></p>";
			element += "<p><span>手机号码：</span><span>" + userInfo.mobile + "</span></p>";
			element += "<p><span>邮编：</span><span>" + zip + "</span></p>";
			modalBody.append (element);
			modal.show ();
		},
		
		showRefundInfoAlert:function(refundInfo){
			var otherReason = refundInfo.otherReason;
			if(otherReason == ""){
				otherReason = '无';
			}
			var image = refundInfo.image;
			var modal = $("#previewImg-modal2");
			var modalBody = modal.find('.modal-body');
			modalBody.empty ();
			var element = '';
			element += "<p><span>退款原因：</span><span>" + refundInfo.reason + "</span></p>";
			element += "<p><span>其他：</span><span>" + otherReason + "</span></p>";
			if(image == ""){
				element += "<p><span>相关图片：</span><span>无</span></p>";
			} else {
				element += "<p><span>相关图片：</span><img class='refundImg' src='" + image + "' /></p>";	
			}
			modalBody.append (element);
			modal.show ();
		},
		
		sendGoods:function(order_code,logistics,logistics_code){
			var params = {};
			params.order_code = order_code;
			params.logistics = logistics;
			params.logistics_code = logistics_code;
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/sendGoodsServ.html",
				data:params,
				success:function(data){
					if(data.ret == 1){
						layer.alert('发货成功',{offset:'200px'},function(){
							location.reload(true);
						});
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
		},
		
		checkVoucher:function(order_code,type,reason){
			var params = {};
			params.order_code = order_code;
			params.type = type;
			if(type == 2){
				params.reason = reason;	
			}
			
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/checkOrderVoucherServ.html",
				data:params,
				success:function(data){
					if(data.ret == 1){
						var title = type == 1 ? '已通过该线下付款凭证':'已拒绝该线下付款凭证'
						layer.alert(title,{offset:'200px'},function(){
							location.reload(true);
						});
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
		},
		
		handleRefund:function(order_code,pid,type,reason){
			var params = {};
			params.order_code = order_code;
			params.pid = pid;
			params.type = type;
			if(type == 2){
				params.reason = reason;	
			}
			
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/handleRefundServ.html",
				data:params,
				success:function(data){
					if(data.ret == 1){
						var title = type == 1 ? '已同意该退款申请':'已拒绝该退款申请'
						layer.alert(title,{offset:'200px'},function(){
							location.reload(true);
						});
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
		},

	};
	
	$(function(){
		handle.init();
	});
});
