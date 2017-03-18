$(document).ready(function(){
	var subTypeArray = new Array();
	getCollegeSubtype();
	$("select[name=user_ident]").on("change",function(){
/*
		if($('.default').css('display') == 'none'){
			$('.default').css('display','table-cell');
			$('.company').css('display','none');
			$('.college').css('display','none');
		}
*/
		
		var user_type = $("select[name=user_ident] option:selected").attr('user_type');
		if(user_type == 1){
			if($("select[name=sub_ident]").length > 0){
				jQuery("select[name=sub_ident]").empty();	
			}
			$("select[name=sub_ident]").append("<option sub_type='0'>高校本身</option>"); 
			for(var i = 0; i < subTypeArray.length; i++){
				var sub_type = subTypeArray[i].sub_type;
				var name = subTypeArray[i].name;
				$("select[name=sub_ident]").append("<option sub_type="+sub_type+">"+name+"</option>"); 	
			}
			$("select[name=sub_ident]").css('visibility','visible');
		} else if(user_type == 2){
			if($("select[name=sub_ident]").length > 0){
				jQuery("select[name=sub_ident]").empty();	
			}
			$("select[name=sub_ident]").append("<option sub_type='0'>公司本身</option>"); 
			$("select[name=sub_ident]").append("<option sub_type='1'>科研人员</option>"); 
			$("select[name=sub_ident]").append("<option sub_type='2'>采购员</option>"); 
			$("select[name=sub_ident]").css('visibility','visible');
		} else {
			if($("select[name=sub_ident]").length > 0){
				jQuery("select[name=sub_ident]").empty();	
			}
			$("select[name=sub_ident]").css('visibility','hidden');
		}
	});
	
/*
	$("select[name=sub_ident]").on("change",function(){
		var sub_type = $("select[name=sub_ident] option:selected").attr('sub_type');
		if(sub_type == 0 || $('.default').css('display') == 'none'){
			return;
		}
		var user_type = $("select[name=user_ident] option:selected").attr('user_type');
		if(user_type == 1){
			$('.default').css('display','none');
			$('.company').css('display','none');
			$('.college').css('display','table-cell');
		} else if(user_type == 2){
			$('.default').css('display','none');
			$('.college').css('display','none');
			$('.company').css('display','table-cell');
		}
	});
*/
	
	$(".add").on('click',function(){
		var params = {};
		var nickname = $.trim($('input[name=nickname]').val());	
		var realname = $.trim($('input[name=realname]').val());	
		var mobile = $.trim($('input[name=mobile]').val());	
		var psw = $.trim($('input[name=psw]').val());	
		var confirm_psw = $.trim($('input[name=confirm_psw]').val());	
		var user_type = $("select[name=user_ident] option:selected").attr('user_type');
		var sub_type = 0;
		if(user_type == 1 || user_type == 2){
			sub_type = $("select[name=sub_ident] option:selected").attr('sub_type');
		}
/*
		var cid = 0;
		if(user_type == 1 && sub_type != 0){
			cid = $("select[name=at_college] option:selected").attr('cid');
			params.cid = cid;
		} else if(user_type == 2 && sub_type != 0){
			cid = $("select[name=at_company] option:selected").attr('cid');
			params.cid = cid;
		}
*/
		
		var showAlert = true;
		var title = "";
		if(realname == ""){
			title = "请填写真实姓名";
		} else if(mobile == ""){
			title = "请填写手机号";
		} else if(psw == ""){
			title = "请填写密码";
		} else if(confirm_psw == ""){
			title = "请确认密码";
		} else if(confirm_psw != psw){
			title = "两次密码输入不一致";
		} else if(psw.length < 6){
			title = "密码长度不低于6位";
		} else {
			showAlert = false;
		}
		
		if(showAlert) {
			layer.alert(title,{offset:'200px'});
			return;
		}
		
		params.nickname = nickname;
		params.realname = realname;
		params.user_type = user_type;
		params.sub_type = sub_type;
		params.mobile = mobile;
		params.password = psw;
		
		$.ajax({
			type:"post",
			dataType:"json",
			data:params,
			url:"/service/addMemberServ.html",
			success:function(data){
				if(data.ret == 1){
					layer.alert(data.msg,{offset:'200px'},function(){
						window.location.href = "../usermanager/memberInfo.html";
					});
				} else {
					layer.alert(data.msg,{offset:'200px'});
				}
			},
			error:function(data){
				layer.alert("服务器错误,请稍后再试",{offset:'200px'});
			},
			complete:function(){
				
			}
		});
	});
	
	$('.reset').on('click',function(){
/*
		var inputs = $('input[type=text]');
		for(var i=0;i<inputs.length;i++){
			$(inputs[i]).val('');
		}
*/
		location.reload(true);
	});
	
	function getCollegeSubtype(){
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/getCollegeSubTypeServ.html",
			success:function(data){
				if(data.ret == 1){
					subTypeArray = data.data;
				} else {
					layer.alert(data.msg,{offset:'200px'});
				}
			},
			error:function(data){
				layer.alert("服务器错误,请稍后再试",{offset:'200px'});
			},
			complete:function(){
				
			}
		});
	};
});