define(function(require){
	var $ = require('jquery');
	require('common');
	require('ichuk');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		init:function(){
			 var iChuk = iChukCore.Inital();
			 $addmission = $("#addmission"); 
			 $addmission.click(function(){
			    var adddialog;
				var time = Math.floor((Math.random()*100000000)+1);
				LoadTemplets = "../../do/loadplughtml.html?filename=user.issuedtask.addmission.html&rand="+time;
				htmlobj=$.ajax({url:LoadTemplets,async:false});
				var html = "<div id=\'_"+time+"\'>"+htmlobj.responseText+"</div>";

				layer.open({
				  type: 1,
				  scrollbar: false,
                  area: ['650px', 'auto'],
				  skin: 'layui-layer-demo', //样式类名
				  closeBtn: 1, //不显示关闭按钮
				  shift: 2,
				  shadeClose: true, //开启遮罩关闭
				  content: html
				});

			 })
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				if(page == ""){
					return;
				}
				var sid = $("#sid").val();
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/issuedTaskCpl.html?sid="+sid+"&search="+search+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			$("#task_search_btn").on("click",function(){
				var searchVal = $.trim($("#task_search").val());
				if(searchVal == ""){
					return;
				}
				var sid = $("#sid").val();
				window.location.href = getHost()+"/user/issuedTaskCpl.html?sid="+sid+"&search="+searchVal;
			});
			$("#task_search").keypress(function(e){
				if(e.keyCode == 13){
					$("#task_search_btn").click();
				}
			});
			
			
			
			
			
			
			
			
			
			
			
			
			
		}
	};
	(function(){
		handle.init();
	})();
});