define(function(require){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	
	var queryflag = 0;
	
	var handle = {
		init:function(){
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				if(page == ""){
					return;
				}
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/issuedTask.html?search="+search+"&page="+page;
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
				window.location.href = getHost()+"/user/issuedTask.html?search="+searchVal;
			});
			$("#task_search").keypress(function(e){
				if(e.keyCode == 13){
					$("#task_search_btn").click();
				}
			});
			$(".td_a a").on("click",function(){
				var id = $(this).parent().attr("data");
				if(id == undefined || isNaN(id) || id <= 0){
					return;
				}
				var opt = $(this).attr("opt");
				if(opt == 'complete'){
					var index = layer.confirm('确认完成？', {
						title:"温馨提示",
						btn: ['确认','取消'], //按钮
						offset:['220px','40%']
					}, function(){
						layer.close(index);
						handle.changeMissionSt(id,4);
					});	
				}else if(opt == 'unpass'){
					layer.confirm('确认不通过？', {
						title:"温馨提示",
						btn: ['确认','取消'], //按钮
						offset:['220px','40%']
					}, function(index){
						layer.close(index);
						handle.changeMissionSt(id,2);
					});	
				}else if(opt == 'payFunds'){
					handle.confirmPayFunds(id);
				}
			});	
		},
		changeMissionSt:function(id,st){
			if(queryflag == 1){
				return;
			}
			queryflag = 1;
			var index = layer.load(2);
			$.ajax({
				type:"post",
				dataType:"json",
				data:{ms_id:id,result_status:st},
				url:getHost()+"/service/changeMission.html",
				success:function(data){
					if(data.ret == 1){
						window.location.reload();
					}else if(data.ret == -1){
						window.location.href = getHost()+"/handle/login.html?redirect="+encodeURIComponent(window.location.href);
					}else{
						layer.alert(data.msg);
					}
				},error:function(){
					layer.alert('抱歉，服务器错误');
				},
				complete:function(){
					queryflag = 0;
					layer.close(index);
				}
			});
		},
		confirmPayFunds:function(id){
			layer.confirm("确定要托管资金吗？",{icon:3,title:'提示',btn:['托管','取消']},function(index){
				window.location.href = getHost()+"/user/mkorder.html?id="+id;
				layer.close(index);
			});	
		}
	};
	(function(){
		handle.init();
	})();
});