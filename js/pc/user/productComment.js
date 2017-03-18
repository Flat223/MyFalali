define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	require('cyupload.js');
	
	var handle = {
		init:function(){
			$.cyupload({
				elem: '#uploadImage',
				btnName: "请选择",		//按键名称
				infoElementId: "",	//上传状态信息包装元素id
				maxFilesize: 10485760,
				uploadUrl: '/service/uploadimgServ.html',
				fileFilter: '',
				upfileParam: 'upload_file_input',
				success: function (url) {
					$('#commentImage').attr('src', url['file_url']);
				}
			}); 
			
			$("#commentImage").click(function(){
				$("#uploadImage").find(".upload_file_btn").trigger("click");
			});
			
			$('.submit-button').on('click',function(){
				var pid = $(this).attr('pid');
				var order_code = $(this).attr('order_code');
			    var comment = $.trim($('#commentIntro').val());
			 	var star = $('#star input').val();
			 	var commentImg = $('#commentImage').attr('src');
			 	if(comment == ""){
				 	layer.alert("请填写产品评论",{offset:'200px'});
				 	return;
			 	}
			 	var params = {};
			 	params.pid = pid;
			 	params.order_code = order_code;
				params.star = star;
				params.comment = comment;
				params.commentImg = commentImg;
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/reviewProductServ.html",
					data:params,
					success:function(data){
						if(data.ret == 1){
							layer.alert(data.msg,{offset:'200px'},function(){
								window.history.back();
							});
						}else{
							layer.alert(data.msg,{offset:'200px'});
						}
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					},
					complete:function(){
	
					}
				});
			});
		},

	};
	
	$(function(){
		handle.init();
	});
});