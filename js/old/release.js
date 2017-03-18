define(function(require){
	var $ = require('jquery');
	require('common');
// 	require('cyupload');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	
	var questflag = 0;
	var handle = {
		init:function(){
			$("#submission").on("click",function(){
				handle.subMission();
			});
/*
			$("#upload_attchment").cyupload({
				uploadUrl:getHost()+"/user/upload.html?type=release",
				btnName:"上传附件",
				fileFilter:/^(zip|doc|docx|pdf|xls|xlsx|cvs)$/i,
				error:function(data){
					handle.showErr(data);
				},
				success:function(url){
					$("#upload_url").val(url);
				}
			});
*/
		},
		subMission:function(){
			if(questflag == 1){
				return;
			}
			var name = $.trim($("#mission_name").val());
			var area = $.trim($("#mission_area").val());
			var software = $("#mission_software").val();
			var expecttime = $("#mission_expecttime").val();
			var mobile = $("#mission_mobile").val();
			var intro = $.trim($("#mission_intro").val());
			var url = $("#upload_url").val();
			if(name == ""){
				this.showErr("请填写项目名称");
				return;
			}
			if(mobile == ""){
				this.showErr("请填写联系电话");
				return;
			}
			if(intro == ""){
				this.showErr("请填写项目描述");
				return;
			}
			if(url == ""){
				this.showErr("请上传附件");
				return;
			}
			var data = {};
			data.name = name;
			data.area = area;
			data.software = software;
			data.expecttime = expecttime;
			data.mobile = mobile;
			data.intro = intro;
			data.fileurl = url;
			questflag = 1;
			$("#submission").text("正在提交...");
			$.ajax({
				type:"post",
				dataType:"json",
				data:data,
				url:getHost()+"/service/releaseMissionServ.html",
				success:function(data){
					if(data.ret == 1){
						window.location.href = getHost()+"/mission/releaseSuc.html";
					}else{
						handle.showErr(data.msg);
					}
				},
				error:function(){
					handle.showErr("抱歉，服务器错误");
				},
				complete:function(){
					questflag = 0;
					$("#submission").text("确认提交");
				}
			});	
		},
		showErr:function(msg){
			layer.confirm(msg, {
				title:"错误提示",
				btn: ['确认'], //按钮
				offset:['20%','40%']
			});
			return false;
		}
	};
	(function(){
		handle.init();
	})();
});