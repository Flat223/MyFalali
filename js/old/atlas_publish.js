define(function(require){
	var $ = require('jquery');
	require('ichuk');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
		
	var handle = {
		init:function(){
			 var iChuk = iChukCore.Inital(); 
			 var title = $("input[name=title]");
			 var description = $("textarea[name=description]");
			 var submit = $("input[name=submit]");

			 submit.click(function(){
			    var post = {}; 
				post.title = title.val();
				post.description = description.val();
				if(post.title != "")
				{
					if(post.description != "")
					{
					    iChuk.RequestData("../../do/submitdemand.html","POST",post,function(data){
							if(data.ret == 1)
							{
								layer.msg(data.msg);
								setTimeout(function(){
									window.location.href="../../atlas/list.html";
								},1000)
							}
							else
							{
								layer.msg(data.msg);
							}
						})
					}
					else
					{
						layer.msg('请输入描述');
					}
				}
				else
				{
					layer.msg('请输入标题');
				} 
			 }) 

		}
	};
	(function(){
		handle.init();
	})();
});