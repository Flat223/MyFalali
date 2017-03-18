define(function(require){
	var $ = require('jquery');
	require('jquery.SuperSlide.2.1.1')($);
	
	var handle = {
		init:function(){
			$(".txtMarquee-top").slide({mainCell:".bd ul",autoPlay:true,effect:"topMarquee",vis:6,interTime:50});
		}
	};
	(function(){
		handle.init();
	})();
});