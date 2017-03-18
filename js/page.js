define(function(require){
	var $ = require("jquery");
	
	return {
		makePage:function(page,identity){
			if(page == undefined || identity == undefined){
				return;
			}
			var pagehtml = "<ul class='pagination' style='margin: 0 0;'>";
			if(page.hasPrevious){
				pagehtml += "<li><a href='javascript:void(0);' page='"+(page.currentPage-1)+"'>上一页</a></li>";
			}else{
				pagehtml += "<li class='disabled'><a href='javascript:void(0);'>上一页</a></li>";
			}
			if(page.currentPage >= 7){
				pagehtml += "<li><a href='javascript:void(0);' page='1'>1</a></li>";
				pagehtml += "<li><a href='javascript:void(0);' page='2'>2</a></li>";
				pagehtml += "<li><span style='border:none;background:none;'>...</span></li>";
				pagehtml += "<li><a href='javascript:void(0);' page='"+(page.currentPage-2)+"'>"+(page.currentPage-2)+"</a></li>";
				pagehtml += "<li><a href='javascript:void(0);' page='"+(page.currentPage-1)+"'>"+(page.currentPage-1)+"</a></li>";
			}else{
				for(var i =1;i<page.currentPage;i++){
					pagehtml += "<li><a href='javascript:void(0);' page='"+i+"'>"+i+"</a></li>";
				}
			}
			pagehtml += "<li class='active'><a href='javascript:void(0);'>"+page.currentPage+"</a></li>";
			if(page.pageCount > (page.currentPage+5)){
				pagehtml += "<li><a href='javascript:void(0);' page='"+(page.currentPage+1)+"'>"+(page.currentPage+1)+"</a></li>";
				pagehtml += "<li><a href='javascript:void(0);' page='"+(page.currentPage+2)+"'>"+(page.currentPage+2)+"</a></li>";
				pagehtml += "<li><span style='border:none;background:none;'>...</span></li>";
				pagehtml += "<li><a href='javascript:void(0);' page='"+(page.pageCount-1)+"'>"+(page.pageCount-1)+"</a></li>";
				pagehtml += "<li><a href='javascript:void(0);' page='"+page.pageCount+"'>"+page.pageCount+"</a></li>";
			}else{
				for(var i=(page.currentPage+1);i<=page.pageCount;i++){
					pagehtml += "<li><a href='javascript:void(0);' page='"+i+"'>"+i+"</a></li>";
				}
			}
			if(page.hasNext){
				pagehtml += "<li><a href='javascript:void(0);' page='"+(page.currentPage+1)+"'>下一页</a></li>";
			}else{
				pagehtml += "<li class='disabled'><a href='javascript:void(0);'>下一页</a></li>";
			}
			pagehtml += "</ul>";
			$(identity).html(pagehtml);	
		}
	};
	
});