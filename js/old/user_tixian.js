define(function(require){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
	require('ichuk');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		init:function(){
			var iChuk = iChukCore.Inital();
			$("input[name=submit]").click(function () {
				var post = {};
				var price = $("input[name=price]");
				var payaccount = $("input[name=payaccount]");
				var paytype = $("select[name=paytype]");
				post.price = price.val();
				post.paytype = paytype.val();
				post.payaccount = payaccount.val();
				if(Number(post.price) > 0)
				{
					if(post.payaccount != "")
					{
						if(post.paytype !="")
						{
							layer.confirm('确认提现'+post.price+'元吗？', {
								title:"温馨提示",
								btn: ['确认','取消'] //按钮
							}, function(){
								iChuk.RequestData("../../do/submitwithdraw.html","POST",post,function(data){
									if(data.ret == 1)
									{
										layer.msg(data.msg);
										setTimeout(function(){
											window.location.href="../../user/myinfo.html";
										},1000)
									}
									else
									{
										layer.msg(data.msg);
									}
								})
							}, function(){
				
							});
						}
						else
						{
						    layer.msg("请输入提现账户类型。");
						}
					}
					else
					{
					    layer.msg("请输入提现账号。");
					}
					
				}
				else
				{
				    layer.msg("请输入提现金额。");
				}
				 
			});
		}
	};
	(function(){
		handle.init();
	})();
});