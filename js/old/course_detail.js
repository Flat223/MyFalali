define(function(require){
	var $ = require('jquery');
	require('common');
	var jplayer = require('jplayer');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	var questflag = 0;
	
	var handle = {
		init:function(){
			setTimeout(function(){ 
				$("#jquery_jplayer_1").jPlayer({
					ready: function () {
						$(this).jPlayer("setMedia",videoobject);
					},
					swfPath: "../../js/jplayer/jplayer",
					supplied: "webmv, ogv, m4v",
					size: {
						width: "830px",
						height: "504px",
						cssClass: "jp-video-360p"
					},
					useStateClassSkin: true,
					autoBlur: false,
					smoothPlayBar: true,
					keyEnabled: true,
					remainingDuration: true,
					toggleDuration: true
				});
			},500);
			$(".a_now").on("click",function () {
				var fee = $("#cfee").val();
				var cid = $("#cid").val();
				var index = layer.confirm('学习此视频需要'+fee+'金币，是否确认支付？', {
					title:"学习课程视频",
					btn: ['确认支付','取消'] //按钮
				}, function(){
					handle.buyCourse(cid);
					layer.close(index);
				});
			});
		},
		buyCourse:function(cid){
			if(questflag == 1){
				return;
			}
			questflag = 1;
			$.ajax({
				type:"post",
				dataType:"json",
				data:{cid:cid},
				url:getHost()+"/service/buyCourse.html",
				success:function(data){
					if(data.ret == 1){
						window.location.reload();
					}else if(data.ret == -1){
						window.location.href = getHost()+"/handle/login.html?redirect="+encodeURIComponent(window.location.href);
					}else{
						handle.showErr(data.msg);
					}
				},
				error:function(){
					handle.showErr("服务器错误");
				},
				complete:function(){
					questflag = 0;
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