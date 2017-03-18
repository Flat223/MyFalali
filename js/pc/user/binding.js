define (function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var bind_type = $(".page_container").attr('bind_type');
	var bind_title = bind_type == 1 ? "高校" : "企业";
	
	var handle = {
		init:function(){
			$(document).keyup(function(event){
				if(event.keyCode == 13){
					$(".com_search").trigger("click");
				}
			});
			$(".com_search").on('click',function(){
				var key = $.trim($("input[name=search_key]").val());

				if(key == ""){
					layer.alert('请输入' + bind_title + '名称',{offset:'200px'});
					return;
				}
				location.href = "/user/binding.html?key=" + key;
			});
			
			$(".apply_bind").on("click",function(){
				var cid = $(this).attr('cid');
				var alert = layer.confirm("确认绑定该"+bind_title, {
								title:"温馨提示",
								btn: ['确认','取消'] //按钮
							}, function(){
								layer.close(alert);
								handle.bindCompany(cid);
							}, function(){
				
							});
			});
			
			$(".unbind").on("click",function(){
				var cid = $(this).attr('cid');
				var alert = layer.confirm("确定解绑该"+bind_title, {
								title:"温馨提示",
								btn: ['确认','取消'] //按钮
							}, function(){
								layer.close(alert);
								handle.unBindCompany(cid);
							}, function(){
				
							});
				
			});
			
			$(".rebind").on("click",function(){//申请被拒绝后重新申请;
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/rebindCompanyServ.html",
					data:"",
					success:function(data){
						if(data.ret == 0){
							layer.alert(data.msg,{offset:'200px'});
						} else {	
							location.reload(true);
						}
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					},
					complete:function(){
						
					}
				});
			});
		},
		
		bindCompany:function(cid){
			var params = {};
			params.cid = cid;
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/bindCompanyServ.html",
				data:params,
				success:function(data){
					if(data.ret == 0){
						layer.alert(data.msg,{offset:'200px'});
					} else {
						var msg;
						if(bind_type == 1){
							msg = "已成功绑定该高校";
						} else {
							msg = "绑定申请提交成功,请等待公司审核";
						}
						layer.alert(msg,{offset:'200px'},function(){
							location.href = "/user/binding.html";
						});
					}
				},
				error:function(data){
					layer.alert("服务器错误,请稍后再试",{offset:'200px'});
				},
				complete:function(){
					
				}
			});
		},
		
		unBindCompany:function(cid){
			var params = {};
			params.cid = cid;
			params.type = bind_type;
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/unBindCompanyServ.html",
				data:params,
				success:function(data){
					if(data.ret == 0){
					 	layer.alert(data.msg,{offset:'200px'});
					} else {
						location.reload(true);
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

