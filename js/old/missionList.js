define(function(require){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	
	var questflag = 0;
	
	var handle = {
		
		init:function(){
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				var search = $.trim($("#search").val());
				if(page == ""){
					return;
				}
				window.location.href = getHost()+"/mission/taskList.html?search="+search+"&page="+page;
			});
			$(".task_search_btn").on("click",function(){
				var search = $.trim($("#task_search_val").val());
				if(search != ""){
					window.location.href = getHost()+"/mission/taskList.html?search="+search+"&page=1";
				}
			});
			$("#task_search_val").keypress(function(e){
				if(e.keyCode == 13){
					$(".task_search_btn").click();
				}
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			$(".btn_apply.active").on("click",function(){
				var id = $(this).parent().parent().attr("data-ref");
				if(id == undefined){
					return;
				}
				var index = layer.confirm('确认要申请此项目吗？', {
					title:"温馨提示",
					btn: ['确认申请','取消'], //按钮
					offset:['220px','40%']
				}, function(){
					layer.close(index);
					handle.apply(id);
				});
			});
		},
		apply:function(id){
			if(questflag == 1){
				return;
			}
			questflag = 1;
			var index = layer.load(0);
			$.ajax({
				type:"post",
				dataType:"json",
				data:{id:id},
				url:getHost()+"/service/applyTask.html",
				success:function(data){
					if(data.ret == 1){
						layer.alert("申请成功",{offset:['220px','40%']},function(index2){
							layer.close(index2);
							window.location.reload();
						});
					}else if(data.ret == -1){
						window.location.href = getHost()+"/handle/login.html?redirect="+encodeURIComponent(window.location.href);
					}else{
						layer.alert(data.msg,{offset:['220px','40%']});
					}
				},
				error:function(){
					layer.alert("抱歉，服务器错误",{offset:['220px','40%']});
				},
				complete:function(){
					questflag = 0;
					layer.close(index);
				}	
			});	
		}
	};
	
	(function(){
		handle.init();
	})();	
	
});