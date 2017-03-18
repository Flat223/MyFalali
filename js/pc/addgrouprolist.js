define(function(require,exports,module){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	//var b = require('/js/pc/addGroup');
	var a = require('/js/pc/addGroupCall');
	var handle = {
		init:function(){ 
			$("tr[class=product]").mousemove(function(){
				$(this).addClass("productmove").siblings().removeClass("productmove");
			});

			$("a[class=intro]").click(function(){
				var intro=$(this).attr("message");
				if(intro==""){
					intro="没有产品说明";
				}
				layer.alert(intro,{"width":400,"height":400});
			})

			$("a[class=images]").on("click", function () {
				var images=$(this).attr("images");
				layer.open({
					type: 2,
					title: '产品图片',
					shadeClose: true,
					shade: 0.8,
					area: ['720px', '80%'],
					content: '../myshop/images.html?images='+images
				});
			});

			$("button[name=deleteproduct]").on("click",function(){ 
				var index1=parent.layer.getFrameIndex(window.name);
				//a.callback(166);
				//parent.layer.close(index1);
				var pid=$(this).attr('pid');
				layer.open({
					type: 2,
					title: '选择产品属性',
					shadeClose: true,
					shade: 0.8,
					area: ['600px', '50%'],
					content: '../myshop/addGroupProperty.html?pid=' + pid+"&index1="+index1,
				});
				
			});

			$("a[class=property]").on("click", function () {
				var pid=$(this).attr("pid");
				var ptid=$(this).attr('ptid');
				layer.open({
					type: 2,
					title: '分类详情',
					shadeClose: true,
					shade: 0.8,
					area: ['480px', '40%'],
					content: '../myshop/property.html?pid='+pid+'&ptid='+ptid
				});
			});


			$("button[name=modify]").on("click", function (){
				var status = $(this).attr('status');
				if(status == 1){
					layer.alert("该产品已上架,请下架后修改",{offset:'200px'});
					return;
				}
				
				var pid = $(this).attr('pid');
				var name = $(this).attr('pname');
				var alert = layer.confirm(name, {
						title:"修改产品信息",
						btn: ['修改基本信息','修改属性信息'] 
					}, function(){
						layer.close(alert);
						handle.modifyBasicPro(pid);
					}, function(){
						layer.close(alert);
						handle.modifyPropertyPro(pid);
					});
			});

			$("button[name=ping]").on("click", function () {
				var pid=$(this).attr("pid");
				var status=$(this).attr("status");
				var url="/service/UpingProductServ.html";
				$.ajax({
					type: "POST",
					url: url,
					data: {"pid":pid,"status":status},
					dataType: "json",
					success: function (data) {
						if(data.ret=='1'){
							window.location.reload(-1);
						}else{
							layer.alert(data.msg);
						}
					},
					error: function (data) {
						layer.alert("error");
					}
				});
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
	exports.callback = function(index){
		//parent.layer.close(index);
		
	};
	$(function(){
		handle.init();
	});
});