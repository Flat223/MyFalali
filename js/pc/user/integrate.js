define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	var handle = {
		init:function(){
			$(".exchange_integrate").on("click",function(){
				layer.open({
			        type: 1,
			        title: false,
			        closeBtn: true,
			        area: '300px;',
			        shade: 0.5,
			        id: 'LAY_layuipro',
			        moveType: 1,
			        content: '<img src=\'/images/temp_pc/code8.png\' alt=\'\' />',
			        yes: function(){
			          
			        }
		        });
			});
			
			$(".sign").on("click",function(){
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/signServ.html",
					success:function(data){
						if(data.ret == 1){
							handle.signSuccess(1,data['sign_count']);
// 							layer.alert(data.msg,{offset:'200px'});
						}else if(data.ret == -1){
							layer.alert(data.msg,{offset:'200px'});
						}else{
							handle.signSuccess(2,0);
// 							layer.alert(data.msg,{offset:'200px'});
						}
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					},
				});
			});
		},
		signSuccess:function(type,count){//1签到成功 2:今天已签到
			var text = "";			
			if(type == 1){
				text = "已连续签到" + count + "天，获得5积分";	
			} else {
				text = "你今天已经签过啦";
			}
			$(".sign_desc").text(text);
			layer.open({
		        type: 1,
		        title: false,
		        closeBtn: true,
		        area: '500px;',
		        shade: 0.5,
		        id: 'LAY_layuipro',
		        moveType: 1,
		        content: $(".sing_success"),
		        yes: function(){
					
		        },
		        cancel:function(){
			        location.reload(true);
		        }
		        
	        });
	    }  
	};
	
	$(function(){
		handle.init();
	});
});