define(function(require){
	var $ = require('jquery');
	require('common');
		
	var handle = {
		init:function(){
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				var sid = $("#sid").val();
				if(page == ""){
					return;
				}
				window.location.href = getHost()+"/software/download.html?sid="+sid+"&page="+page;
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