layui.define(['layer','form','layui_year_month_day','layui_common','layui_cyupload'],function(exports){
	var layer = layui.layer;
	var $ = layui.jquery;
	var year_month_day = layui.layui_year_month_day;
	var common = layui.layui_common;
	var form = layui.form();
	var questflag = 0;
	var upload_attchment_url = "";
	
	var handle = {
		init:function(){
			$.cyupload({
				elem:'#upload_attchment',
				uploadUrl:'http://admin.luqiwang.com/Qiniu/upload.html?name=file',
				btnName:"上传附件",
				upfileParam:"file",
				fileFilter:/^(zip|rar|7z)$/i,
				error:function(data){
					layer.alert(data,{offset:['20%','40%']});
				},
				success:function(res){
					upload_attchment_url = res.locationurl;
					$("#upload_st").text("文件已上传");
				}
			});
			year_month_day.initDateSelection('.year','.month','.day',2016,2020);
			$("#submission").on("click",function(){
				handle.subMission();
			});
		},
		subMission:function(){
			if(questflag == 1){
				return;
			}
			var name = $.trim($("#mission_name").val());
			var area = $.trim($("#mission_area").val());
			var specialties = new Array();
			$("input[type='checkbox'][name='specialty']:checked").each(function(){
				specialties.push($(this).val());
			});
			var year = $(".year").val();
			var month = $(".month").val();
			var day = $(".day").val();
			var mobile = $("#mission_mobile").val();
			var intro = $.trim($("#mission_intro").val());
			if(name == ""){
				layer.tips("请填写项目名称","#mission_name");
				return;
			}
			if(specialties.length == 0){
				layer.tips("请选择专业","#specialties");
				return;
			}
			specialties = specialties.join(",");
			if(isNaN(year) || isNaN(month) || isNaN(day)){
				layer.tips("请选择项目完成日期","#complete_date");
				return;
			}
			if(mobile == ""){
				layer.tips("请填写联系电话","#mission_mobile");
				return;
			}
			if(upload_attchment_url == ""){
				layer.tips("请上传附件","#upload_attchment");
				return;
			}
			if(intro == ""){
				layer.tips("请填写项目描述","#mission_intro");
				return;
			}
			var data = {};
			data.name = name;
			data.area = area;
			data.specialties = specialties;
			data.endDate = year+"-"+month+"-"+day;
			data.mobile = mobile;
			data.intro = intro;
			data.fileurl = upload_attchment_url;
			questflag = 1;
			$("#submission").text("正在提交...");
			$.ajax({
				type:"post",
				dataType:"json",
				data:data,
				url:common.getHost()+"/service/releaseMissionServ.html",
				success:function(data){
					if(data.ret == 1){
						window.location.href = getHost()+"/mission/releaseSuc.html";
					}else{
						layer.alert(data.msg,{offset:['20%','40%']});
					}
				},
				error:function(){
					layer.alert("抱歉，服务器错误",{offset:['20%','40%']});
				},
				complete:function(){
					questflag = 0;
					$("#submission").text("确认提交");
				}
			});	
		}	
	};
	(function(){
		handle.init();
	})();	
	exports('release_mission',{});
});