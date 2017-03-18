define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		init:function(){
			$(".finish").on('click',function(){
				var search_key = $("input[name=circle]").val();
				if(search_key == ""){
					layer.alert('请输入搜索信息',{offset:'200px'});
					return;
				}
				handle.join(search_key);
			});	
		},
		
		join:function(search_key){
			var params = {};
			params.key = search_key;
		    $.ajax({
		        type: "POST",
		        url: "/service/searchCircleServ.html",
		        data: params,
		        dataType: "json",
		        success: function (data) {
		            if(data.ret==1){
			            location.href = "/user/interestingCircle.html?key="+search_key;
		            } else if(data.ret == 0){
		                handle.reloadHtml();
		            } else {
			            layer.alert(data.msg,{offset:'200px'});
		            }
		        },
		        error: function (data) {
		            layer.alert('服务器错误,请稍后再试',{offset:'200px'});
		        }
		    });
		},
		
		reloadHtml:function(){
			$('.want-like').css('display','none');
			$('.p3').text('抱歉,没有搜到你想查询的圈子');
			$('.p3').css('color','#00bfb8');
		}	
	};
	
	$(function(){
		handle.init();
	});
});