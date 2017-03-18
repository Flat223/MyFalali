define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});

	var handle = {
		init:function(){
			$("tr[class=product]").mousemove(function(){
				$(this).addClass("productmove").siblings().removeClass("productmove");
			});
			
			$("button[name=deleteproduct]").on("click",function(){
				var pid=$(this).attr('pid');
				var alert = layer.confirm("是否删除？", {
					title:"温馨提示",
					btn: ['确认','取消'] //按钮
				}, function(){
					layer.close(alert);
					deletepro(pid);
				}, function(){

				});
			});

			function deletepro(id){
				var url="/service/DeleteGroupServ.html";
				$.ajax({
					type: "POST",
					url: url,
					data: {"id":id},
					dataType: "json",
					success: function (data) {
						if(data.ret=='1'){
							var alert = layer.confirm(data.msg, {
								title:"温馨提示",
								btn: ['确认'],
								closeBtn:0 //按钮
							}, function(){
								window.location.reload(-1);
							});
						}else{
							layer.alert(data.msg);
						}
					},
					error: function (data) {
						layer.alert("error");
					}
				});
			};
			$("button[name=modify]").on("click", function (){
				var id=$(this).attr("id");
				window.location.href="/myshop/editGroup.html?id="+id;
			});

		},
		
		modifyBasicPro:function(pid){
				layer.open({
					type: 2,
					title: '修改商品基本信息',
					shadeClose: true,
					shade: 0.8,
					area: ['1300px', '100%'],
					content: '../myshop/modifyproduct.html?pid=' + pid,
				});
		},
		
		modifyPropertyPro:function(pid){
				layer.open({
					type: 2,
					title: '修改商品属性信息',
					shadeClose: true,
					shade: 0.8,
					area: ['1300px', '100%'],
					content: '../myshop/modifyproperty.html?pid=' + pid,
				});
		}
	};

	$(function(){
		handle.init();
	});
});