define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/',
	});
	
	function uploadPro(id){
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/testUploadProServ.html",
			data:{"id":id},
			success:function(data){
				if(data.ret != -1){
					var pid = data.id;
					pid++;
					if(data.ret == 1){
						$('.wait').hide();
						$('.info').show();
						
						$('.id').text(pid);	
					} else {
						$('.info').hide();
						$('.wait').show();
					}
					if(pid <= 333241){
						uploadPro(pid);
					}	
				} else {
					layer.alert(data.msg);
				}
			},
			error:function(data){
				layer.alert('服务器错误,请稍后再试',{offset:'200px'});
			},
			complete:function(){
				
			}
		});
	}
	
	$(function(){
		var id = $('input[name=pid]').val();
		uploadPro(id);
	});
});