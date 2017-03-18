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
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/practice.html?search="+search+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			$("#practice_search_btn").on("click",function(){
				var searchVal = $.trim($("#practice_search").val());
				if(searchVal == ""){
					return;
				}
				window.location.href = getHost()+"/user/practice.html?search="+searchVal;
			});
			$("#practice_search").keypress(function(e){
				if(e.keyCode == 13){
					$("#practice_search_btn").click();
				}
			});
			$(".a_up").each(function(){
				var btnName = $(this).attr("ref-name");
				var id = $(this).attr("id");
				var practiceid = $(this).attr("ref-id");
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
					fileFilter:/^(rar|zip|7z)$/i,
					maxFilesize:31457280,
					error:function(data){
						layer.alert(data,{offset:['20%','40%']});
					},
					success:function(res){
						handle.commitPractice(practiceid,res.locationurl);
					}
				});
			});			
		},
		commitPractice:function(id,file_url){
			if(questflag == 1){
				return;
			}
			questflag = 1;
			$.ajax({
				type:"post",
				dataType:"json",
				data:{id:id,url:file_url},
				url:getHost()+"/service/commitPractice.html",
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