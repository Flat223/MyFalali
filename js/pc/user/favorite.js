define(function(require) {
	var $ = require('jquery');
	var layer;
	require('layui/layui.js');
	require('md5.js');
	if(window.layui){
		layui.config({
			dir: '/layui/'
		});
		layui.use(['layer', 'element'], function(){
			layer = layui.layer;
		});
	}
	layui.use(['layer', 'laypage', 'element'], function(){
		var layer = layui.layer
			,element = layui.element();


		//监听Tab切换
		// element.on('tab(demo)', function(data){
		//   layer.msg('切换了：'+ this.innerHTML);
		//   console.log(data);
		// });

	});
	$(".unfavorite").on("click",function () {
		var id = $(this).attr('value');
		var type = $(this).attr('type');
		console.log(id);
		console.log(type);
		$.ajax({
			type: "post",
			dataType: "json",
			url: "/service/DeleteGoodsServ.html",
			data: {"id":id,"type":type},
			success: function (data) {
				if (data.ret == 1){
					layer.alert(data.msg,{offset:'200px'},function(){
						window.location.reload();
					});
				} else {
					layer.alert(data.msg,{offset:'200px'});
				}
			}
		})
	});

});



