define(function(require){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	
	
	var handle = {
		init:function(){
			$(".demand-item").click(function(){
			    var description = $(this).attr("data-description");
				var form = layer.confirm(description, {
				  btn: ['确定',"取消"] //按钮
				}, function(){
				   layer.close(form);
				}, function(){
				   
				});
			})

		}
	};
	(function(){
		handle.init();
	})();
});