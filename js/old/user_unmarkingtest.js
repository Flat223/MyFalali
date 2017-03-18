define(function(require){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		init:function(){
			$(".a_up").click(function () {
				layer.confirm('确认支付所需金币？', {
					title:"温馨提示",
					btn: ['确认','取消'] //按钮
				}, function(){
					layer.msg('支付成功！', {time:1000,icon: 1});
				}, function(){
	
				});
				return false;
			});
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				if(page == ""){
					return;
				}
				var sid = $("#sid").val();
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/unmarkingTest.html?sid="+sid+"&search="+search+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			$("#test_search_btn").on("click",function(){
				var searchVal = $.trim($("#test_search").val());
				if(searchVal == ""){
					return;
				}
				var sid = $("#sid").val();
				window.location.href = getHost()+"/user/unmarkingTest.html?sid="+sid+"&search="+searchVal;
			});
			$("#test_search").keypress(function(e){
				if(e.keyCode == 13){
					$("#test_search_btn").click();
				}
			});
			
			
			
			
			
			
			
			
			
			
			
			
			
		}
	};
	(function(){
		handle.init();
	})();
});