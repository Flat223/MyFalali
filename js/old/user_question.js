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
			$("#commit_question").on("click",function(){
				handle.commitQuestion();
			});	
			
		},
		commitQuestion:function(){
			if(queryflag == 1){
				return;
			}
			var sid = $("#software").val();
			var title = $.trim($("#question_tit").val());
			var content = $("#question_cnt").val();
			if(sid == undefined || isNaN(sid) || sid <= 0){
				this.showErr("参数有错误");
				return;
			}
			if(title == ""){
				this.showErr("请填写问题");
				return;
			}
			queryflag = 1;
			$("#commit_question").val('提交中...');
			$.ajax({
				type:"post",
				dataType:"json",
				url:getHost()+"/service/saveQuestionServ.html",
				data:{sid:sid,title:title,content:content},
				success:function(data){
					if(data.ret == 1){
						window.location.href = getHost()+"/user/uncompleteqa.html"
					}else if(data.ret == -1){
						window.reload();
					}else{
						handle.showErr(data.msg);
					}
				},
				error:function(data){
					handle.showErr("抱歉，服务器错误");
				},
				complete:function(){
					queryflag = 0;
					$("#commit_question").val('提问');
				}
			});
			
			
		},
		showErr:function(msg){
			layer.confirm(msg, {
				title:"错误提示",
				btn: ['确认'], //按钮
				offset:['20%','40%']
			});
			return false;
		}
	};
	(function(){
		handle.init();
	})();
});