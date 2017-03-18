define(function(require) {
	var $ = require('jquery');
	var layer;
	require('layui/layui.js');
	if(window.layui){
		layui.config({
			dir: '/layui/'
		});
		layui.use(['layer', 'element'], function(){
			layer = layui.layer;
		});
	}

	layui.use(['layer', 'laypage', 'element'], function(){
		var layer = layui.layer
			,element = layui.element();

	});

 	var mesClasses = document.getElementsByClassName("detail");
	for (i = 0;i < mesClasses.length;i++){
		var mes = mesClasses[i];
		if($(mes).attr('is_read') == 1) {
			mes.style.color = "gray";	
		}
	}
	
	$(".detail").on("click",function(){
/*
		var sid = $(this).attr('sid');
		$(".message_details").show();
		$(".messagebox").hide();
*/
		var from_id = $(this).attr('from_id');
		var sid = $(this).attr('sid');
		if (sid == "") {
			return ;	
		}
		
		var html = "";
	
		if (from_id == -1) {
			html = "/user/messageDetail.html?sid="+sid;
		} else {
			html = "/user/messageDetail.html?from_id="+from_id+"&sid="+sid;
		}
		window.location.href = html;
	});
	$("input[name=selAll]").on("click",function(){
		var boxes = "";
		if($(this).attr('selType') == 1){
			boxes = document.getElementsByName("selUser");
		} else {
			boxes = document.getElementsByName("selSystem");
		}
		for(i=0;i<boxes.length;i++){
        	boxes[i].checked = $(this).is(':checked');
        }
	});
	
	$(".del").on("click",function(){
		if ($(this).attr('from_id') == -1) {
			delmessage("",$(this).attr('sid'));
		} else {
			delmessage($(this).attr('sid'),"");
		}
	});
	
	$(".tag_submit").on("click",function(){
		var boxes = "";
		if ($(this).attr('delType') == -1) {//系统
			boxes = document.getElementsByName("selSystem");
		} else { //用户
			boxes = document.getElementsByName("selUser");
		}
		var user_msg_id = "";
		var system_msg_id = "";
		for(i=0;i<boxes.length;i++){
			if(boxes[i].checked == true) {
				if($(boxes[i]).attr('from_id') == -1){
					if (system_msg_id == "") {
						system_msg_id = $(boxes[i]).attr('sid');
					} else {
						system_msg_id += ',' + $(boxes[i]).attr('sid');
					}
				} else {
					if (user_msg_id == "") {
						user_msg_id = $(boxes[i]).attr('sid');
					} else {
						user_msg_id += "," + $(boxes[i]).attr('sid');
					}
				}
			}
        }
        if (user_msg_id != "" || system_msg_id != "") {
	    	delmessage(user_msg_id,system_msg_id);    
        }
	});
	
	function delmessage(user_msg_id,system_msg_id){//用户消息id && 系统消息id
		var params = {};
		params.user_msg_id = user_msg_id;			
		params.system_msg_id = system_msg_id;	
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/deleteMessageServ.html",
			data:params,
			success:function(data){
				alert(data.msg);
				location.reload(true);
			},
			error:function(data){
				alert("服务器错误,请稍后再试");
			},
			complete:function(){
				
			}
		});
		return ;
	}
});