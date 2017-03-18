define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	require('cyupload.js');
	var area = require('area.js');
	
	var realProvince = $(".s_province").attr('real');
	var realCity = $(".s_city").attr('real');
	var realCountry = $(".s_country").attr('real');
		
	var reg = /^\d{17}([0-9]|X)$/;
	var handle = {
		init:function(){
			area.init("s_province","s_city","s_country",realProvince,realCity,realCountry);
			
			$(".interest_labels").on("mouseleave",'.added_label',function(){
				$(this).children(".del_label").hide();
			}).on("mouseenter",'.added_label',function(){
				$(this).children(".del_label").show();
			});
			
			$(".interest_labels").on('click','.del_label',function(){
				$(this).parent().remove();
			});		
			$("#addLab").on("click",function(){
				var currLab = $("#all_lab option:selected");
				var selectLab = $('.interest_labels').children('li');
				for(var x = 0; x < selectLab.length;x++){
					if(currLab.attr('lid') == $(selectLab[x]).attr('lid')) {
						layer.alert("该兴趣标签已添加",{offset:'200px'});
						return;
					}		
				}
				
				var lid = currLab.attr('lid');
				var labelName = currLab.text();	
				var str = "<li class=\'added_label\' lid=\'" + lid + "\'>\
				      		<span class=\'tag_label\'>" + labelName + "</span>\
				      		<i class=\'del_label\' lid=\'" + lid + "\'></i>\
						  </li>"
				$(".interest_labels").append(str);	              
			});
				
			$.cyupload({
				elem: '#uploadimg',
				btnName: "请选择",		//按键名称
				infoElementId: "",	//上传状态信息包装元素id
				maxFilesize: 10485760,
				uploadUrl: '/service/uploadimgServ.html',
				fileFilter: '',
				upfileParam: 'upload_file_input',
				success: function (url) {
					$('#faceimage').attr('src', url['file_url']);
				}
			}); 
			$("#faceimage").click(function(){
				$("#uploadimg").find(".upload_file_btn").trigger("click");
			});
			
			$("#saveChange").on("click",function(){
			//必填
				var s_province = $(".s_province");
				var s_city = $(".s_city");
				var s_country = $(".s_country");
			
				var name = $("#username").val();
				var face = $("#faceimage").attr('src');
				var nickname = $("#nickname").val();
				var sex = $("input[name=sex]:checked").val();
// 				var education = $("#education").val();
				var identity_num = $("#identity_num").val();
// 				var city = $("#city").val();
// 				var residential_district = $("#residential_district").val();
				
				var showAlert = true;
				var title = "";
				if (name == "") {
					title = "请输入姓名";
				} else if (nickname == "") {
					title = "请输入昵称";
				} else if (sex == undefined) {
					title = "请选择性别";
				} else if (identity_num == "") {
					title = "请输入身份证号";
				} else if (identity_num.length != 18){
					title = "身份证输入不合法";
				} else if (!reg.test(identity_num)){
					title = "身份证输入不合法";
				} else if (s_province.get(0).selectedIndex == 0){
					title = "请选择所在城市";
				} else if (s_city.get(0).selectedIndex == 0){
					title = "请选择地级市";
				} else if (s_country.get(0).selectedIndex == 0){
					title = "请选择市、县级市";
				} else {
					showAlert = false;
				}
					
				if (showAlert) {
					layer.alert(title,{offset:'200px'});
					return ;
				}
		
				var interest_labels = "";//兴趣标签
				var selectLab = $('.interest_labels').children('li');
				for(var x = 0; x < selectLab.length;x++){
					if (interest_labels == "") {
						interest_labels = $(selectLab[x]).attr('lid'); 
					} else {
						interest_labels = interest_labels + "," + $(selectLab[x]).attr('lid'); 
					}
				}	
		
				var params = {};
				params.name = name;	
				params.face = face;	
				params.nickname = nickname;
				params.sex = Number(sex);
				params.identity_num = identity_num;
				params.province = s_province.val();
				params.city = s_city.val();
				params.country = s_country.val();
				
// 				params.education = education;
// 				params.city = city;
// 				params.residential_district = residential_district;//居住区域

				params.career = $("#career").val();
				params.university = $("#university").val();
				params.political_identity = $("#political_identity").val();//政治面貌

				params.personal_desc = $("#personal_desc").val();
				params.education_experience = $("#education_experience").val();
				params.work_experience = $("#work_experience").val();
				params.research_achievement = $("#research_achievement").val();
				params.patent = $("#patent").val();
				params.research_projects = $("#research_projects").val();
				params.interest_labels = interest_labels;
				
				handle.updateUserInfo(params);
			});
		},
		
		updateUserInfo:function(params){
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/UpdateUserServ.html",
				data:params,
				success:function(data){
					if(data.ret == 1){
						layer.alert(data.msg,{offset:'200px'},function(){
							window.location.reload(true);
						});
					} else {
						layer.alert(data.msg,{offset:'200px'});
					}
				},
				error:function(data){
					layer.alert('服务器错误,请稍后再试',{offset:'200px'});
				},
				complete:function(){
					
				}
			});
		}
	};
	
	$(function(){
		handle.init();
	});
});