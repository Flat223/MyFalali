define(function(require){
	var $ = require('jquery');
	require('common');
	require('cyupload');
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
				var sid = $("#sid").val();
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/test.html?sid="+sid+"&search="+search+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			$("#test_search_btn").on("click",function(){
				var searchVal = $.trim($("#test_search").val());
				if(searchVal == ""){
					return;
				}
				var sid = $("#sid").val();
				window.location.href = getHost()+"/user/test.html?sid="+sid+"&search="+searchVal;
			});
			$("#test_search").keypress(function(e){
				if(e.keyCode == 13){
					$("#test_search_btn").click();
				}
			});
			$(".a_up").each(function(){
				var btnName = $(this).attr("ref-name");
				var id = $(this).attr("id");
				var testid = $(this).attr("ref-id");
				var coin = $(this).attr("ref-coin");
				$.cyupload({
					elem:'#'+id,
					uploadUrl:'http://admin.luqiwang.com/Qiniu/upload.html?name=file',
					btnName:btnName,
					upfileParam:"file",
					before:function(){
						if(questflag == 1){
							layer.alert("另一个提交正在进行中",{offset:['20%','40%']});
							return false;
						}
					},
					onClick:function(opts,execQueue){
						layer.confirm('提交需支付'+coin+"金币，确认提交吗？", {
							title:"温馨提示",
							offset:['20%','40%'],
							btn: ['确认','取消'] //按钮
						}, function(index){
							layer.close(index);
							handle.confirmCoin(testid,opts,execQueue);
						}, function(index){
							opts.clickable = false;
							layer.close(index);
							execQueue();
						});	
					},
					fileFilter:/^(zip|rar|7z)$/i,
					maxFilesize:31457280,
					error:function(data){
						layer.alert(data,{offset:['20%','40%']});
					},
					success:function(res){
						handle.commitTest(testid,res.locationurl);
					}
				});
			});		
		},
		commitTest:function(id,file_url){
			if(questflag == 1){
				return;
			}
			questflag = 1;
			$.ajax({
				type:"post",
				dataType:"json",
				data:{id:id,url:file_url},
				url:getHost()+"/service/commitTest.html",
				success:function(data){
					if(data.ret == 1){
						window.location.reload();
					}else{
						layer.alert(data.msg,{offset:['20%','40%']});
					}
				},
				error:function(){
					layer.alert("抱歉，服务器错误",{offset:['20%','40%']});
				},
				complete:function(){
					questflag = 0;
				}
			});
		},
		confirmCoin:function(testid,opts,execQueue){
			if(questflag == 1){
				return;
			}
			questflag = 1;
			var index = layer.load(2);
			$.ajax({
				type:"post",
				dataType:"json",
				async:false,
				data:{testid:testid},
				url:getHost()+"/service/checkCoin.html",
				success:function(data){
					if(data.ret == 1){
						opts.clickable = true;
						execQueue();
					}else{
						layer.alert(data.msg,{offset:['20%','40%']});
					}
				},
				error:function(){
					layer.alert("抱歉，服务器错误",{offset:['20%','40%']});
				},
				complete:function(){
					questflag = 0;
					layer.close(index);
				}
			});	
		}
	};
	(function(){
		handle.init();
	})();
});