define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
		
	var area = require('area.js');
	
	var realProvince = $(".s_province").attr('real');
	var realCity = $(".s_city").attr('real');
	var realCountry = $(".s_country").attr('real');
	
	var save_type = $("input[name=save_type]").val();
	var aid = $("input[name=aid]").val();
	var mobile_reg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;  
		
	var handle = {
		init:function(){	
			area.init("s_province","s_city","s_country",realProvince,realCity,realCountry);				
			$(".setDefault").on("click",function(){
				var addressId = $(this).attr('aid');
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/operateShopAddressServ.html",
					data:{'aid':addressId,'type':2},
					success:function(data){
						if(data.ret == 1){
							layer.alert(data.msg,{offset:'200px'},function(){
								location.reload(true);
							});
						}else{
							layer.alert(data.msg,{offset:'200px'});
						}
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					},
					complete:function(){
	
					}
				});
			});
			
			$(".del").on("click",function(){
				var addressId = $(this).attr('aid');
				var alert = layer.confirm("确定删除该地址", {
								title:"温馨提示",
								btn: ['确认','取消'] //按钮
							}, function(){
								layer.close(alert);
								handle.delAddress(addressId);
							}, function(){
								
							});
			});
				
			$(".tag_label").on("click",function(){		
				var s_province = $(".s_province");
				var s_city = $(".s_city");
				var s_country = $(".s_country");
				
				var detail_address = $("textarea[name=detailed_address]").val();
				var name = $("input[name=user_name]").val();
				var mobile = $("input[name=mobile]").val();
				var is_default = $("input[name=is_default]").is(':checked') ? '1' : '0';
				
				var showAlert = true;
				var title = "";
				if(name == ""){
					title = "请填写联系人姓名";
				} else if (s_province.get(0).selectedIndex == 0){
					title = "请选择所在城市";
				} else if (s_city.get(0).selectedIndex == 0){
					title = "请选择地级市";
				} else if (s_country.get(0).selectedIndex == 0){
					title = "请选择市、县级市";
				} else if (detail_address == ""){
					title = "请填写所在街道地址";
				} else if (mobile == ""){
					title = "请填写手机号码";
				} else if (!mobile_reg.test(mobile)){
					title = "请填写有效的手机号码！";
				} else {
					showAlert = false;
				}
				if(showAlert){
					layer.alert(title,{offset:'200px'});
					return;
				}
				
				var params = {};
				if(save_type == 2){
					params.aid = aid;	
				}
				params.type = save_type;
				params.name = name;
				params.detail_address = detail_address;
				params.mobile = mobile;
				params.is_default = is_default;
				params.province = s_province.val();
				params.city = s_city.val();
				params.country = s_country.val();
				
				handle.handleAddress(params);
			});
		},
		
		handleAddress:function(params){
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/saveShopAddressServ.html",
				data:params,
				success:function(data){
					var alert = layer.alert(data.msg,{offset:'200px'},function(){
						if(data.ret == 1){
							layer.close(alert);
							location.reload(true);
						}
					});
				},
				error:function(data){
					layer.alert('服务器错误,请稍后再试',{offset:'200px'});
				},
				complete:function(){

				}
			});
		},
		
		delAddress:function(aid){
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/operateShopAddressServ.html",
				data:{'aid':aid,'type':1},
				success:function(data){
					var alert = layer.alert(data.msg,{offset:'200px'},function(){
						if(data.ret == 1){
							layer.close(alert);
							window.location.href = "/myshop/addressManager.html";
						}
					});
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