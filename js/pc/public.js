define(function(require){
	var $ = require('jquery');
	require('jquery.SuperSlide.2.1.1')($);
	require('swiper');
	require('md5');
	var userInfo={};
	var handle = {
		init:function(){
			$("div.imgbox").on("click",function(){
				var pid=$(this).parent().attr("value");
				window.location.href="../../goods/detail.html?pid="+hex_md5(pid);
			});
			$(".add_gy").on("click",function(){
				window.location.href="../../regist/stepReg.html";
			});
			$(".slideBox").slide({mainCell:".bd ul",effect:"left",autoPlay:true,duration : 5,easing : 'swing'});
			$(".online").on("click",function(){
				window.open("http://wpa.qq.com/msgrd?v=3&uin=1193218128&site=qq&menu=yes", "_blank");
			});
			$(".tel_item").on("click",function(){
				layer.alert("请拨打: 18015554589");
			});
			//	分类下拉
			var subLi = $(".cate_down>ul>li");
			var categrory = $(".categrory");
			var grand = $(".sub_menu>ul>li");
			//二级菜单
			// categrory.hover(function () {
			// 	$(this).find(".cate_down").show();
			// },function () {
			// 	$(this).find(".cate_down").hide();
			// });
			//三级菜单
			subLi.hover(function () {
				$(this).addClass("active");
				$(this).find(".sub_menu").show();
			},function () {
				$(this).removeClass("active");
				$(this).find(".sub_menu").hide();
			});
			grand.hover(function () {
				$(this).addClass("active");
				$(this).find(".grandchild").show();
			},function () {
				$(this).removeClass("active");
				$(this).find(".grandchild").hide();
			});
			
			
			var swiperContains = Array();
			var swiperContent = $(".swiper-container"); 
			for(var x = 0; x < swiperContent.size(); x++)
			{
				var itemid = "swiper-container-"+x;
				var nextid = "swiper-button-next-"+x;
				var previd = "swiper-button-prev-"+x;
				swiperContent.eq(x).attr("id",itemid);
				swiperContent.eq(x).parent().find(".swiper-button-next").addClass(nextid);
				swiperContent.eq(x).parent().find(".swiper-button-prev").addClass(previd);
				var swiper = new Swiper('#'+itemid, {
					nextButton: '.'+nextid,
					prevButton: '.'+previd,
					loop: true,
					autoplay: 8000
				});
				swiperContains.push(swiper);
			} 
		}
		
	};
	
	function getUserInfo(){
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/GetUserInfoServ.html",
			data:{},
			success:function(data){
				userInfo=data.data;
			},
			error:function(data){
				alert("服务器错误,请稍后再试");
			}

		});
	};
	
	$(function(){
		handle.init();
	});
});
