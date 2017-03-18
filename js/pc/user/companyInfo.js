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
						
			$.cyupload({
				elem: '#uploadimg',
				btnName: "请选择",		//按键名称
				infoElementId: "",	//上传状态信息包装元素id
				maxFilesize: 10485760,
				uploadUrl: '/service/uploadimgServ.html',
				fileFilter: '',
				upfileParam: 'upload_file_input',
				success: function (url) {
					$('#bussinessImage').attr('src', url['file_url']);
				}
			}); 
			$("#bussinessImage").click(function(){
				$("#uploadimg").find(".upload_file_btn").trigger("click");
			});
			
			$("#saveChange").on("click",function(){
				var s_province = $(".s_province");
				var s_city = $(".s_city");
				var s_country = $(".s_country");
			
				var name = $.trim($("#name").val());
				var bussinessImage = $.trim($("#bussinessImage").attr('src'));
				var address = $.trim($("#address").val());
				
				var showAlert = true;
				var title = "";
				if (name == "") {
					title = "请填写公司名称";
				} else if (s_province.get(0).selectedIndex == 0){
					title = "请选择所在城市";
				} else if (s_city.get(0).selectedIndex == 0){
					title = "请选择地级市";
				} else if (s_country.get(0).selectedIndex == 0){
					title = "请选择市、县级市";
				} else if (address == ""){
					title = "请填写公司详细名称";
				} else {
					showAlert = false;
				}
					
				if (showAlert) {
					layer.alert(title,{offset:'200px'});
					return ;
				}	
		
				var params = {};
				params.name = name;	
				params.face = bussinessImage;	
				params.province = s_province.val();
				params.city = s_city.val();
				params.country = s_country.val();
				params.address = address;

				params.personal_desc = $("#personal_desc").val();
				params.research_achievement = $("#research_achievement").val();
				params.patent = $("#patent").val();
				params.research_projects = $("#research_projects").val();
				
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