define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});

	var mid=$("input[name=mid]").val();
	var friend_mid=$("input[name=friend_mid]").val();
	var dym_count = $('input[name=dym_count]').val();
	var home=$("input[name=home]").val();
	
	var handle = {
		init:function(){
			$(document).on('click','span[name=follow]',function(){
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/AddfriendServ.html",
					data:{"mid2":friend_mid,"mid":mid},
					success:function(data){
						if(data.ret=='1'){
							layer.alert(data.msg,function(){
								window.location.reload(-1);
							});
						}else{
							layer.alert(data.msg);
						}
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					}
				});
			});
			$(document).on('click','span[name=isfollow]',function(){
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/unfollowServ.html",
					data:{"mid":friend_mid},
					success:function(data){
						if(data.ret=='1'){
							layer.alert(data.msg,function(){
								window.location.reload(-1);
							});
						}else{
							layer.alert(data.msg);
						}
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					}
				});
			});
			
			$('.loadmore').on('click',function(){
				var page = $(this).attr('page');
				page ++;
				
				var params = {};
				params.page = page;
				if(home == 1){
					params.mid = mid;		
				} else {
					params.mid = friend_mid;
				}
				
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/loadMoreUserDynamicServ.html",
					data:params,
					success:function(data){
						if(data.ret==1){
							$(".loadmore").attr('page',page);
							var min_height = parseInt($('.wrapper_r').css('min-height'))+parseInt(data.more.length*200)+'px';
							$('.wrapper_r').css('min-height',min_height);
							if(data.more.length>0){
								handle.reloadMore(data.more);	
							}
						}else{
							layer.alert(data.msg,{offset:'200px'});
						}
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					}
				});
			});

			//猜你喜欢换一换
			$(document).on("click","span[name=maybeyoulike]",function(){
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/changeyoulikeServ.html",
					data:{},
					success:function(data){
						$("div[name=maybeyoulike]").html(data);
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					}
				});
			});
			//推荐产品换一换
			$(document).on("click","span[name=hotrecommend]",function(){
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/changeyoulikeServ.html",
					data:{},
					success:function(data){
						$("div[name=hotrecommend]").html(data);
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					}
				});
			});
		},
		
		reloadMore:function(array){
			for(var i in array){
				var dynamic = array[i];
				var time = new Date(dynamic.time * 1000);
                var date = time.getUTCFullYear()+"-"+(time.getUTCMonth()+1)+"-"+time.getUTCDate()+" "+time.getUTCHours()+":"+time.getUTCMinutes()+":"+time.getUTCSeconds();
                
				var str = "<li class=\'clearfix\'>\
							<dl>\
								<dt>\
									<a href=\'/info/article.html?id=" + dynamic.id + " \'>\
										<img src=\' " + dynamic.images + "\' alt=\'\'/>\
									</a>\
								</dt>\
								<dd style=\'width: 550px;\'>\
									<h2 onclick=\'location.href=\'/info/article.html?id="+dynamic.id+" \' \'>"+ dynamic.title +"</h2>\
									<span class=\'desc\'>" + dynamic.intro + "</span>\
									<p class=\'extra\' style=\'position: absolute;top: 137px;margin-top: 0px\'>\
										<span>文</span>\
										<span>/</span>\
										<span class=\'name\'>" + dynamic.author + "</span>\
										<span>" + date + "></span>\
										<span>" + dynamic.category + "</span>\
									</p>\
								</dd>\
							</dl>\
						</li> ";
				$('#dynamicContainer').append(str);
				if(dym_count <= $('#dynamicContainer').children('li').length){
					$('.more_item').css('display','none');
				}
			}
		},
	};

	$(function(){
		handle.init();
	});
});