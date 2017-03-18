define(function(require){
	var $ = require('jquery');
	require('jquery.SuperSlide.2.1.1')($);
	
	var handle = {
		init:function(){
			$("div.imgbox").on("click",function(){
				var pid=$(this).parent().attr("value");
				window.location.href="../../goods/detail.html?pid="+pid;		
			});

			$(".slideBox").slide({mainCell:".bd ul",effect:"left",autoPlay:true,duration : 5,easing : 'swing'});
			
			//	分类下拉
			var subLi = $(".cate_down>ul>li");
			var categrory = $(".categrory");
			// 二级菜单
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



		
			//加的效果
			$(".add").click(function(){
				// alert(1);
			var n=$(this).prev().val();
			var num=parseInt(n)+1;
			if(num==0){ return;}
			$(this).prev().val(num);
			});
			//减的效果
			$(".jian").click(function(){
			var n=$(this).next().val();
			var num=parseInt(n)-1;
			if(num==0){ return}
			$(this).next().val(num);
			});	
		}	
	};
	
	
	
	
	$(function(){
		handle.init();
	});
});
