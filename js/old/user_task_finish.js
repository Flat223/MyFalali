define(function(require){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
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
				var sid = $("#sid").val();
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/finishedTask.html?sid="+sid+"&search="+search+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			$("#task_search_btn").on("click",function(){
				var searchVal = $.trim($("#task_search").val());
				if(searchVal == ""){
					return;
				}
				var sid = $("#sid").val();
				window.location.href = getHost()+"/user/finishedTask.html?sid="+sid+"&search="+searchVal;
			});
			$("#task_search").keypress(function(e){
				if(e.keyCode == 13){
					$("#task_search_btn").click();
				}
			});
			
			
			
			
			
			
			
			
			
			
			
			
			
		}
	};
	(function(){
		handle.init();
	})();
});