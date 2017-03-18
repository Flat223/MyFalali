define(function(require){
	var $ = require('jquery');
	require('jquery.SuperSlide.2.1.1')($);
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		init:function(){
			var submitbutton = $("input[name=suggest-submit]");
			var submitform = $("textarea[name=feedback]");
			var contactinput = $("input[name=contact]");
			submitbutton.click(function(){
			    var value = submitform.val();
				var contact = contactinput.val();
				if(value == "")
				{
				    var alert = layer.confirm('请输入内容', {
						title:"温馨提示",
						btn: ['确认','取消'] //按钮
					}, function(){
						layer.close(alert);
					}, function(){
		
					});
					return;
				}
				
				if(contact == "")
				{
				    var alert = layer.confirm('请输入联系方式', {
						title:"温馨提示",
						btn: ['确认','取消'] //按钮
					}, function(){
						layer.close(alert);
					}, function(){
		
					});
					return;
				}

				var params = {};
				params.value = value;
				params.contact = contact; 
				submitbutton.val("正在提交...");
				$.ajax({
					type:"post",
					dataType:"json",
					url:getHost()+"/service/submitfeedServ.html",
					data:params,
					success:function(data){
						if(data.ret == 1){
							var alert = layer.confirm(data.msg, {
								title:"温馨提示",
								btn: ['确认','取消'] //按钮
							}, function(){
								location.reload(true);
							}, function(){
				
							}); 
						}else{
							var alert = layer.confirm(data.msg, {
								title:"温馨提示",
								btn: ['确认','取消'] //按钮
							}, function(){
								layer.close(alert);
							}, function(){
				
							});
						}
					},
					error:function(data){
						var alert = layer.confirm("服务器错误,请稍后再试", {
							title:"温馨提示",
							btn: ['确认','取消'] //按钮
						}, function(){
							layer.close(alert);
						}, function(){
			
						});
					},
					complete:function(){
						submitbutton.val("提交");
					}
				});
			})
		}
	};
	(function(){
		handle.init();
	})();
});