define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var type = $('input[name=msg_type]').val();
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
			
			$('.msg_type_title li').click(function(){
			    var type = $(this).val();
			    window.location.href = "../myshop/message.html?type=" + type;
			});
			
			$("input[name=selAll]").on("click",function(){
				var boxes = $('input[name=consignee]');
				for(i=0;i<boxes.length;i++){
		        	boxes[i].checked = $(this).is(':checked');
		        }
			});
			
			$('.del').click(function(){
				var rid;
				var order_code;
				if(type == 1){
					rid = $(this).parent().parent().attr('rid');	
					order_code = "";
				} else if(type == 2){
					order_code = $(this).parent().parent().attr('order_code');	
					rid = "";
				}
                var alert = layer.confirm("确认删除此条消息",{
								title:"温馨提示",
								btn:["确认","取消"]
							},function(){
								layer.close(alert);
								handle.delMessgae(rid,order_code);
							});
                
			});	
			
			$(".del_select").on("click",function(){
				var boxes = $('input[name=consignee]');
				var rids = "";
				var order_codes = "";
				if(type == 1){
					for(i=0;i<boxes.length;i++){
						if(boxes[i].checked == true) {
							rid = $(boxes[i]).parent().parent().parent().attr('rid');	
							if (rids == "") {
								rids = rid;
							} else {
								rids = rids + ',' + rid;
							}
						}
			        }
				} else if(type == 2){
					for(i=0;i<boxes.length;i++){
						if(boxes[i].checked == true) {
							order_code = $(boxes[i]).parent().parent().parent().attr('order_code');	
							if (order_codes == "") {
								order_codes = order_code
							} else {
								order_codes = order_codes + ',' + order_code;
							}
						}
			        }	
				}
				
		        if (rids != "" || order_codes != "") {
			    	var alert = layer.confirm("确认删除选中的消息",{
								title:"温馨提示",
								btn:["确认","取消"]
							},function(){
								layer.close(alert);
								handle.delMessgae(rids,order_codes);
							});
		        }
			});
		},
		
		delMessgae:function(rid,order_code){
			var params = {};
			params.rids = rid;
			params.order_codes = order_code;
			$.ajax({
                type:"post",
                dataType:"json",
                url:"/service/deleteShopMessageServ.html",
                data:params,
                success:function(data){
                    if(data.ret=='1'){
                        layer.alert(data.msg,{offset:'200px'},function(){
	                        location.reload(true);
                        });
                    }else{
                        layer.alert(data.msg,{offset:'200px'});
                    }
                },
                error:function(data){
                    layer.alert('服务器错误,请稍后再试',{offset:'200px'});
                }
            });
		}
	};
	
	$(function(){
		handle.init();
	});
});
