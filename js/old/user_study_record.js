define(function(require){
	var $ = require('jquery');
	require('common');
		
	var handle = {
		init:function(){
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				if(page == ""){
					return;
				}
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/studyRecord.html?search="+search+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			$("#course_search_btn").on("click",function(){
				var searchVal = $.trim($("#course_search").val());
				if(searchVal == ""){
					return;
				}
				window.location.href = getHost()+"/user/studyRecord.html?search="+searchVal;
			});
			$("#course_search").keypress(function(e){
				if(e.keyCode == 13){
					$("#course_search_btn").click();
				}
			});
			
			
			
			
		}
	};
	(function(){
		handle.init();
	})();
});