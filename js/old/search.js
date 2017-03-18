define(function(require){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
	layer.config({
		path:'/js/'
	});
	
	var handle = {
		init:function(){
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				if(page == ""){
					return;
				}
				//var cid = $("#cid").val();
				var search = $("#search_key").val();
				window.location.href = getHost()+"/search.html?search="+search+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			var search = $("#search_key").val();
			$(".header_search_val").val(search);		
			

			/*var $form_section_a = $(".form_section a");
			$form_section_a.on("click",function () {
				$(this).addClass("act").siblings().removeClass("act");
				// return false;
			})*/
			
		}
	};
	(function(){
		handle.init();
	})();
});