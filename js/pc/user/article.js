define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
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
			
			$(".delete").on("click",function(){
				var aid = $(this).attr("aid");
				var alert = layer.confirm("确定删除此文章?", {
								title:"温馨提示",
								btn: ['确认','取消'] //按钮
							}, function(){
								layer.close(alert);
								handle.delArticel(aid);
							}, function(){
								
							});
			});
		},
		
		delArticel:function(aid){
			var params = {};
			params.aid = aid;
			params.type = 2;			
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/updateArticleServ.html",
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