define(function(require){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	var questflag = 0;
	
	var handle = {
		init:function(){
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				if(page == ""){
					return;
				}
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/markingPractice.html?search="+search+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			$("#practice_search_btn").on("click",function(){
				var searchVal = $.trim($("#practice_search").val());
				if(searchVal == ""){
					return;
				}
				window.location.href = getHost()+"/user/markingPractice.html?search="+searchVal;
			});
			$("#practice_search").keypress(function(e){
				if(e.keyCode == 13){
					$("#practice_search_btn").click();
				}
			});
			$(".downMarking").on("click",function(){
				if(questflag == 1){
					return;
				}
				var id = $(this).attr("ref-data");
				if(id == undefined || id == ""){
					return;
				}
				handle.download(id,1);
			});	
		},
		download:function(id,num){
			if(questflag == 1){
				return;
			}
			if(id == undefined){
				return;
			}
			if(num != 1 && num != 2){
				return;
			}
			var pay = 0;
			if(num == 1){
				pay = 0;
			}else{
				pay = 1;
			}
			questflag = 1;
			var index = layer.load(0);
			$.ajax({
				type:'post',
				dataType:'json',
				data:{id:id,pay:pay},
				url:getHost()+"/service/downloadPractice.html",
				success:function(data){
					questflag = 0;
					if(data.ret == 1){
						window.location.href = data.url;
					}else if(data.ret == 2){
						if(num != 2){
							layer.confirm("第一次下载需支付"+data.coin+"金币，确认支付吗？", {
								title:"温馨提示",
								offset:['20%','40%'],
								btn: ['确认','取消'] //按钮
							},function(mindex){
								layer.close(mindex);
								handle.download(id,2);
							});
						}else{
							layer.alert(data.msg,{offset:['20%','40%']});
						}
					}else{
						layer.alert(data.msg,{offset:['20%','40%']});
					}
				},
				error:function(){
					questflag = 0;
					layer.alert("抱歉，服务器错误",{offset:['20%','40%']});
				},
				complete:function(){
					layer.close(index);
				}
			});	
		}
	};
	(function(){
		handle.init();
	})();
});