define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	require('swiper.min.js');
	require('pc/user/logout.js');
	layer.config({
		path:'/js/layer/'
	});

	var handle = {
		init:function(){
			$(function(){
				//选择地区下拉
				var area = $(".area");
				area.hover(function () {
					$(this).addClass("areahover");
					$(this).find(".areadown").show();
				},function () {
					$(this).removeClass("areahover");
					$(this).find(".areadown").hide();
				});
				var categrory = $(".categrory");

				//初始化分类菜单
				categrory.hover(function () {
					$(this).find(".cate_down").show();
				},function () {
					$(this).find(".cate_down").show();
				});
				/*scrollBottomTest();*/
			});

			$("div[name=bemember]").click(function(){
				window.location.href="../user/member.html";
			});

			$("li[name=hotlab]").click(function(){
				var labid=$(this).attr("laburl");
				window.location.href="../lab/detail.html?labId="+labid;
			});

			$(document).on('click','div[name=link]',function(){
				var link=$(this).attr('link');
				window.location.href=link;
			})

			// $(".friendly").click(function(){
			// 	var url=$(this).attr("jump");
			// 	window.location.href='http://'+'/'+url;
			// });

			$("ul[name=allnews] li").click(function(){
				$(this).addClass("con-active").siblings().removeClass("con-active");
				var $now = $("div[class=list_con] ").eq($("ul[name=allnews] li").index(this));
				// console.log($now);
				$now.addClass("ul_show");
				$now.siblings().removeClass("ul_show");
			})

			$(document).on('click','.jumpartice',function(){
				var id=$(this).attr('urlid');
				var url='../info/article.html?id=';
				window.location.href=url+id;
			})

			$(document).on('click','.categoryli',function(){
				var categoryid=$(this).attr('categoryid');
				/*console.log(categoryid);*/
				var url='/service/GetArticlebyidServ.html';
				$.ajax({
					type:"post",
					dataType:"json",
					url:url,
					data:{"categoryid":categoryid},
					success:function(data){
						if($(data).length < 11){
							$('a[name=lookmore]').hide();
						}else{
							$('a[name=lookmore]').show();
						}
						$("ul[name=info]").html(data);
						$('a[name=lookmore]').attr("categoryid",categoryid);
						$('a[name=lookmore]').attr("pageid",1);
						$("#first a").css("color","#828a92");
						// alert(a);
					},
					error:function(data){
						alert("服务器错误,请稍后再试");
					}
				});

			})

			/*$(document).on('click','#first a',function(){
				var categoryid=0
				var url='/service/GetArticlebyidServ.html';
				$.ajax({
					type:"post",
					dataType:"json",
					url:url,
					data:{"categoryid":categoryid},
					success:function(data){
						$("ul[name=info]").html(data);
						$('a[name=lookmore]').attr("categoryid",categoryid);
						// alert(a);
					},
					error:function(data){
						alert("服务器错误,请稍后再试");
					}
				});

			})*/
			// <a name="lookmore" categoryid="0" page="1">浏览更多</a>
			$(document).on('click','a[name=lookmore]',function(){
				var categoryid=$(this).attr('categoryid');
				var page=$(this).attr('pageid');
				var pagetwo=parseInt(page)+parseInt(1);
				// alert(categoryid+page)
				var url='/service/AddArticlebyidServ.html';
				$.ajax({
					type:"post",
					dataType:"json",
					url:url,
					data:{"categoryid":categoryid,"page":page},
					success:function(data){
						if($(data).length < 11){
							$('a[name=lookmore]').hide();
						}
						$("ul[name=info]").append(data);
						$('a[name=lookmore]').attr("pageid",pagetwo);
						// alert(a);
					},
					error:function(data){
						alert("服务器错误,请稍后再试");
					}
				});

			})
/*
			scrollBottomTest =function(){
				$(document).scroll(function(){
					var $this =$(this),
						viewH =$(window).height(),//可见高度
						contentH =$("body").height(),//内容高度
						scrollTop =$(this).scrollTop();//滚动高度
					//if(contentH - viewH - scrollTop <= 100) { //到达底部100px时,加载新内容
					if(scrollTop/(contentH -viewH)>=0.95){ //到达底部100px时,加载新内容 
						$("a[name=lookmore]").trigger("click");
					}
				});
			}
*/
			// $(document).on('click','.des span',function(){
			// 	var id=$(this).attr('videoid');
			// 	window.location.href='../info/video.html?id='+id;
			// })

			var swiper = new Swiper('.banner .swiper-container', {
				
				// paginationType: 'progress',
				nextButton: '.swiper-button-next',
				prevButton: '.swiper-button-prev',
				scrollbar: '.swiper-scrollbar',
				scrollbarHide:false,
				scrollbarDraggable : true ,
				scrollbarSnapOnRelease : true ,
				// loop: true,
				autoplay: 5000,
			});
			//scrollTop
			var scrollTop = $(".list_con>ul[name=allnews]");
			$(window).scroll(function () {
				var scTop = $(window).scrollTop();
				if(scTop>600){
					scrollTop.addClass("fixedBar")
				}else{
					scrollTop.removeClass("fixedBar")
				}
			});
			$(".list_con>ul[name=allnews]").on('click',function () {
				window.scrollTo(600,600);
			})
		}

	};

	$(function(){
		handle.init();
	});
});