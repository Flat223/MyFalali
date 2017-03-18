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
				var sid = $("#sid").val();
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/uncompleteqa.html?sid="+sid+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			$("#question_btn").on("click",function(){
				window.location.href = getHost()+"/user/question.html";
			});
			
			
			
			
			
			
			
			
			
			
			
			
			
		}
	};
	(function(){
		handle.init();
	})();
});