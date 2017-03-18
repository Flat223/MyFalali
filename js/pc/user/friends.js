define(function(require){
	var $ = require('jquery');
	var layer;
	require('/layui/layui.js');
	
	var handle = {
		init:function(){
			if(window.layui){
				layui.config({
				  dir: '/layui/'
				});
				layui.use(['layer', 'element'], function(){
					layer = layui.layer;
				});
			}
			
			$('.layui-tab-title li').on('click',function(){
				if($(this).attr('name') == 'list'){
					$('#page').css('display','none');
				} else {
					$('#page').css('display','block');
				}
			});
			
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				if(page == ""){
					return;
				}
				var baseurl = $('input[name=baseurl]').val();
				window.location.href = baseurl+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			
			$('.unfollow').on('click',function(){
				var mid = $(this).attr('mid');
				var alert = layer.confirm("取消关注此好友?", {
								title:"温馨提示",
								btn: ['确认','取消'] //按钮
							}, function(){
								layer.close(alert);
								handle.unfollow(mid);
							}, function(){
								
							});
							return false;
			});
			
			//搜索好友
			$('.search').on("click",function(){
				var info = $.trim($("input[name=search_key]").val());
				if(info.length < 1) {
					layer.alert("请输入好友名称",{offset:'200px'});
					return;
				}
				window.location.href='/user/friends.html?searchinfo='+info;
			});
			$('input[name=search_key]').keyup(function(event){
				if(event.keyCode == 13){
					$(".search").trigger("click");
				}
			});

		},
		
		unfollow:function(mid){
			var params = {};
			params.mid = mid;
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/unfollowServ.html",
				data:params,
				success:function(data){
					if(data.ret == 1){
						location.reload(true);
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
		}
	};
	
	$(function(){
		handle.init();
	});
});