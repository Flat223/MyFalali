layui.define(['layer','form','layui_year_month_day','layui_common','layui_cyupload'],function(exports){
	var layer = layui.layer;
	var $ = layui.jquery;
	var year_month_day = layui.layui_year_month_day;
	var common = layui.layui_common;
	var form = layui.form();
	var questflag = 0;
	var upload_attchment_url = "";
	var types;
	
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
			$("#subtask").on("click",function(){
				handle.subTask();
			});
			form.on("select(specialty)",function(){
				var specialtyid = $("#specialty").val();
				if(isNaN(specialtyid) || types == undefined){
					$("#sub_specialty").html("<div class='layui-form-mid layui-word-aux'>请先选择专业</div>");
					$("#software").html("<option>请先选择专业</option>");
					form.render();
					return;
				}
				for(var i in types.specialties){
					if(types.specialties[i].spe_id == specialtyid){
						var subSpecialties = types.specialties[i].subSpecialties;
						var softwares = types.specialties[i].softwares;
						var html = "";
						for(var j in subSpecialties){
							html += "<input type='checkbox' name='subSpecialty' title='"+subSpecialties[j].name+"' value='"+subSpecialties[j].spe_id+"' />";	
						}
						$("#sub_specialty").html(html);
						var html2 = "<option>选择软件</option>";
						for(var j in softwares){
							html2 += "<option value='"+softwares[j].sid+"'>"+softwares[j].name+"</option>";
						}
						$("#software").html(html2);
						form.render();
						break;
					}
				}
			});
			this.getSpecialties();
		},
		subTask:function(){
			if(questflag == 1){
				return;
			}
			var name = $.trim($("#task_name").val());
			var specialties = new Array();
			$("input[type='checkbox'][name='subSpecialty']:checked").each(function(){
				specialties.push($(this).val());
			});
			var software = $("#software").val();
			var year = $(".year").val();
			var month = $(".month").val();
			var day = $(".day").val();
			var pay = $("#task_pay").val();
			var intro = $.trim($("#task_intro").val());
			if(name == ""){
				layer.tips("请填写项目名称","#task_name");
				return;
			}
			if(specialties.length == 0){
				layer.tips("请选择子专业","#sub_specialties_item");
				return;
			}
			if(isNaN(software)){
				layer.tips("请选择软件","#software_item");
				return;
			}
			specialties = specialties.join(",");
			if(isNaN(year) || isNaN(month) || isNaN(day)){
				layer.tips("请选择项目完成日期","#complete_date_item");
				return;
			}
			if(isNaN(pay) || pay <= 0){
				layer.tips("请设置报酬","#task_pay");
				return;
			}
			if(upload_attchment_url == ""){
				layer.tips("请上传附件","#upload_attchment");
				return;
			}
			if(intro == ""){
				layer.tips("请填写项目描述","#task_intro");
				return;
			}
			var data = {};
			data.name = name;
			data.specialties = specialties;
			data.sid = software;
			data.endDate = year+"-"+month+"-"+day;
			data.pay = pay;
			data.intro = intro;
			data.fileurl = upload_attchment_url;
			questflag = 1;
			$("#subtask").text("正在提交...");
			$.ajax({
				type:"post",
				dataType:"json",
				data:data,
				url:common.getHost()+"/do/addtask.html",
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
					$("#subtask").text("确认提交");
				}
			});	
		},
		getSpecialties:function(){
			$.ajax({
				type:"get",
				dataType:"json",
				url:common.getHost()+"/do/gettypes.html",
				success:function(data){
					if(data.ret == 1){
						types = data.courseTypes;
						var specialties = data.courseTypes.specialties;
						var html = "<option>请选择专业</option>";
						for(var i in specialties){
							html += "<option value='"+specialties[i].spe_id+"'>"+specialties[i].name+"</option>";
						}
						$("#specialty").html(html);
						form.render();
					}
				}
			});
		}	
	};
	(function(){
		handle.init();
	})();	
	exports('release_task',{});
});