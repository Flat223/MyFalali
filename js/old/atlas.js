define(function(require){
	var $ = require('jquery');
	require('common');
		
	var handle = {
		init:function(){
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				var search = $("#search").val();
				if(page == ""){
					return;
				}
				window.location.href = getHost()+"/atlas/list.html?search="+search+"&page="+page;
			});
			$("#searchbtn").on("click",function(){
				var search = $.trim($("#search_val").val());
				if(search == ""){
					return;
				}
				window.location.href = getHost()+"/atlas/list.html?search="+search+"&page=1";
			});	
			$("#search_val").keypress(function(e){
				if(e.keyCode == 13){
					$("#searchbtn").click();
				}
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
		}
	};
	(function(){
		handle.init();
	})();
});