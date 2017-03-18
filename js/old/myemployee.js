define(function(require){
	var $ = require('jquery');
	require('ichuk');
	require('birthday')($);

	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		init:function(){
		     var iChuk = iChukCore.Inital();
			 $addmember = $("#addmember");
			 $handle = $(".handle-partner");
			 $addmember.click(function(){
			    var adddialog;
				var time = Math.floor((Math.random()*100000000)+1);
				LoadTemplets = "../../do/loadplughtml.html?filename=console.userauthority.addmember.html&rand="+time;
				htmlobj=$.ajax({url:LoadTemplets,async:false});
				var html = "<div id=\'_"+time+"\'>"+htmlobj.responseText+"</div>";

				layer.open({
				  type: 1,
                  area: ['70%', '70%'],
				  skin: 'layui-layer-demo', //样式类名
				  closeBtn: 1, //不显示关闭按钮
				  shift: 2,
				  shadeClose: true, //开启遮罩关闭
				  content: html
				});

			 })

		     $handle.click(function(){
				 var c = confirm("确认删除吗？");
				 if(c)
				 {
				     var post={};
					 post.kind = $handle.attr("data-kind");
					 post.type = $handle.attr("data-type");
					 post.id = $handle.attr("data-mid");

					 iChuk.RequestData("../../do/handlemember.html","POST",post,function(data){
				         if(data.ret == 1)
						 {
						     window.location.reload();
						 }
						 else
						 {
						     layer.msg(data.msg);
							 setTimeout(function(){
							    window.location.reload(); 
							 },1000);
						 }
					 });
				 }
				 
			 })
		}
	};
	
	(function(){
		handle.init();
	})();	
});