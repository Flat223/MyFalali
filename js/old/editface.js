define(function(require){
	var $ = require('jquery');
	require('common');
	require('cropbox');

	var handle = {
		init:function(){
			$(window).load(function() {
				var submit = $("input[name=submit]");
				var img;
				var options =
				{
					thumbBox: '.thumbBox',
					spinner: '.spinner',
					imgSrc: '/images/avatar.jpg'
				}
				var cropper = $('.imageBox').cropbox(options);
				$('#upload-file').on('change', function(){
					var reader = new FileReader();
					reader.onload = function(e) {
						options.imgSrc = e.target.result;
						cropper = $('.imageBox').cropbox(options);
					}
					reader.readAsDataURL(this.files[0]); 
				})
				$('#btnCrop').on('click', function(){
	
					img = cropper.getDataURL();
					$('.cropped').html('');
					$('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:83px;border-radius:83px;"><p>大头像</p>');
					$('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:50px;border-radius:50px;"><p>中头像</p>');
					$('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:33px;border-radius:33px;" ><p>小头像</p>');
					var a = $('.cropped img').length;
					if(a>0){$(".cropped1").hide()}
				})
				$('#btnZoomIn').on('click', function(){
					cropper.zoomIn();
				})
				$('#btnZoomOut').on('click', function(){
					cropper.zoomOut();
				})

				submit.click(function(){ 
					if(img != undefined)
					{
					    var params = {};
						params.face = img;
						submit.val("正在提交...");
						$.ajax({
							type:"post",
							dataType:"json",
							url:getHost()+"/service/updateuserfaceServ.html",
							data:params,
							success:function(data){ 
								if(data.ret == 1){
									var alert = layer.confirm(data.msg, {
										title:"温馨提示",
										btn: ['确认','取消'] //按钮
									}, function(){
										window.location.href="../../user/myinfo.html";
									}, function(){
						
									}); 
								}else{
									var alert = layer.confirm(data.msg, {
										title:"温馨提示",
										btn: ['确认','取消'] //按钮
									}, function(){
										layer.close(alert);
									}, function(){
						
									});
								}
							},
							error:function(data){
								var alert = layer.confirm("服务器错误,请稍后再试", {
									title:"温馨提示",
									btn: ['确认','取消'] //按钮
								}, function(){
									layer.close(alert);
								}, function(){
					
								});
							},
							complete:function(){
								submit.val("提交");
							}
						});
					}
				})
			});
			
	
		}
	};
	(function(){
		handle.init();
	})();
});