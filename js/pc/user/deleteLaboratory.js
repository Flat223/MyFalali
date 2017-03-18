define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		init:function(){
			$("span[del=delete]").on("click",function(){
				var lid = $(this).attr("lid");
				var alert = layer.confirm("确定删除此实验室?", {
								title:"温馨提示",
								btn: ['确认','取消'] //按钮
							}, function(){
								layer.close(alert);
								handle.deleteLab(lid);
							}, function(){
								
							});
			});
		},
		
		deleteLab:function(lid){
			var params = {};
			params.lid = lid;
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/deleteLaboratoryServ.html",
				data:params,
				success:function(data){
					location.reload(true);
				},
				error:function(data){
					layer.alert("服务器错误,请稍后再试",{offset:'200px'});
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