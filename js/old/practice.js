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
			$("#apply").on("click",function(){
				var pt_id = $(this).attr("ref-id");
				if(pt_id == undefined || isNaN(pt_id)){
					return;
				}
				handle.applyPractice(pt_id);
			});
		},
		applyPractice:function(pt_id){
			if(questflag == 1){
				return;
			}
			questflag = 1;
			$("#apply").val("申请中...");
			$.ajax({
				type:"post",
				dataType:"json",
				data:{id:pt_id},
				url:getHost()+"/service/applyPractice.html",
				success:function(data){
					if(data.ret == 1){
						layer.alert("申请成功",{offset:['20%','40%']},function(){
							window.location.reload();
						});
					}else{
						layer.alert(data.msg,{offset:['20%','40%']});
					}
				},
				error:function(){
					layer.alert("抱歉，服务器错误",{offset:['20%','40%']});
				},
				complete:function(){
					questflag = 0;
					$("#apply").val("申请");
				}
			});	
		}
		
		
	};
	(function(){
		handle.init();
	})();
});