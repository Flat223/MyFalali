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
				var ctid = $("#ctid").val();
				var sid = $("#sid").val();
				var tid = $("#tid").val();
				var url = getHost()+"/course/list.html?page="+page;
				if(ctid > 0){
					url += "&ctid="+ctid;
				}
				if(sid > 0){
					url += "&sid="+sid;
				}
				if(tid > 0){
					url += "&tid="+tid;
				}
				window.location.href = url;
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