define(function(require,exports,module){
	var $ = require('jquery');
	var layer;
 	require('layui/layui.js');
 	if(window.layui){
    	layui.config({
			dir: '/layui/'
		});
		layui.use(['layer', 'element'], function(){
			layer = layui.layer;
    	});
	}
	require('cyupload.js');
	var skuids="";
	var second_type = new Array();
	var third_type = new Array();
	var handle = {
		init:function(){
			$("#uping").click(function(){
					layer.open({
					type: 2,
					title: '产品列表',
					shadeClose: true,
					shade: 0.8,
					area: ['950px', '90%'],
					content: '../myshop/addGroupProductList.html',
				});
			});
			$(".nextStep").click(function(){
				var id=$(this).attr("value");
				var name=$("#groupname").val();
				var price=$("#groupprice").val();
				var skuids="";
				$(".deleteimg").each(function(){
					skuids+=$(this).attr('value')+",";
				});

				skuids=skuids.substring(0,skuids.length-1);
				$.ajax({
					type:"get",
					dataType:"json",
					url:"/service/updateGroupServ.html",
					data:{'skuids':skuids,'name':name,'price':price,'id':id},
					success:function(data){
						if(data.ret == 1){
							var alert = layer.confirm(data.msg, {
								title:"温馨提示",
								btn: ['确认'],
								closeBtn:0 //按钮
							}, function(){
								window.history.go(-1);
							});
						}else{
							layer.alert(data.msg);
						}
					},
					error:function(data){
						layer.alert("服务器错误,请稍后再试");
					}
				});
			});
		}		
	};
		
	$(function(){
		handle.init();
	});
});