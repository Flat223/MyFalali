define(function(require){
	var $ = require('jquery');
	require('common');
	require('ichuk');
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
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/issuedTaskCpl.html?search="+search+"&page="+page;
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
				window.location.href = getHost()+"/user/issuedTaskCpl.html?search="+searchVal;
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