define(function(require){
	var $ = require('jquery');
	require('common');
	require('cyupload');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	var questflag = 0;
	
	var handle = {
		init:function(){
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				if(page == ""){
					return;
				}
				var sid = $("#sid").val();
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/task.html?sid="+sid+"&search="+search+"&page="+page;
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
				window.location.href = getHost()+"/user/task.html?sid="+sid+"&search="+searchVal;
			});
			$("#task_search").keypress(function(e){
				if(e.keyCode == 13){
					$("#task_search_btn").click();
				}
			});
			$(".commit_task").each(function(){
				var btnName = $(this).attr("ref-name");
				var id = $(this).attr("id");
				var pid = $(this).attr("ref-id");
				$.cyupload({
					elem:'#'+id,
					uploadUrl:'http://admin.luqiwang.com/Qiniu/upload.html?name=file',
					btnName:btnName,
					upfileParam:"file",
					before:function(){
						if(questflag == 1){
							layer.alert("另一个提交正在进行中",{offset:['20%','40%']});
							return false;
						}	
					},
					maxFilesize:31457280,
					fileFilter:/^(zip|doc|docx|pdf|xls|xlsx|cvs|rar|7z)$/i,
					error:function(data){
						layer.alert(data,{offset:['20%','40%']});
					},
					success:function(res){
						handle.commitTask(pid,res.locationurl);
					}
				});
			});	
		},
		commitTask:function(pid,url){
			if(questflag == 1){
				return;
			}
			questflag = 1;
			$.ajax({
				type:"post",
				dataType:"json",
				data:{pid:pid,url:url},
				url:getHost()+"/service/commitTask.html",
				success:function(data){
					if(data.ret == 1){
						window.location.reload();
					}else{
						layer.alert(data.msg,{offset:['20%','40%']});
					}
				},
				error:function(){
					layer.alert("抱歉，服务器错误",{offset:['20%','40%']});
				},
				complete:function(){
					questflag = 0;
				}
			});
		}
	};
	(function(){
		handle.init();
	})();
});