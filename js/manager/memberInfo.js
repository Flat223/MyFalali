$(document).ready(function(){	
	var subTypeArray = new Array();
	getCollegeSubtype();
	
	$("select[name=user_ident]").on("change",function(){
		var user_type = $("select[name=user_ident] option:selected").attr('user_type');
		if(user_type == 1){
			var str = "<option sub_type='0'>高校本身</option>";
			for(var i in subTypeArray){
				var sub_type = subTypeArray[i].sub_type;
				var name = subTypeArray[i].name;
				str += "<option sub_type="+sub_type+">"+name+"</option>";
			}
			$("select[name=sub_ident]").html(str); 
			$("select[name=sub_ident]").show();
		} else if(user_type == 2){
			var str = "	<option sub_type='0'>公司本身</option>\
						<option sub_type='1'>科研人员</option>\
						<option sub_type='2'>采购员</option>";
			$("select[name=sub_ident]").html(str); 
			$("select[name=sub_ident]").show();
 		} else {
			$("select[name=sub_ident]").hide();
		}
	});
	
	$("#goto").on("click",function(){
		var page = $.trim($("#page_num").val());
		if(page == ""){
			return;
		}
		var baseurl = $('input[name=baseurl]').val();
		window.location.href = baseurl+"&page="+page;
	});
	$("#page_num").keypress(function(e){
		if(e.keyCode == 13){
			$("#goto").click();
		}
	});
	
	$(".delete").on("click",function(){
		var mid = $(this).attr('mid');
		var alert = layer.confirm("确定删除该用户", {
						title:"温馨提示",
						btn: ['确认','取消'] //按钮
					}, function(){
						layer.close(alert);
						deleteMember(mid);
					}, function(){
						
					});
	});
	
	$(".setVIP").on("click",function(){
		var mid = $(this).attr('mid');
		var name = $(this).attr('name');
		var vip = $(this).attr('vip');
		
		$('.user_name').text(name);
		$('input[name=vip]').each(function(index,item){
			$(this).removeAttr('checked');
		});
		$('input[name=vip]').each(function(index,item){
			if($(this).val() == vip){
				$(this).prop('checked',true);
			}
		}); 
		
		layer.open({
            type: 1
            ,title: "设置用户VIP等级"
            ,area: '400px'
            ,shade: 0.15
            ,btn: ["确定", "取消"]
            ,btnAlign: 'b'
            ,content: $("#SetLevel")
            ,yes: function(){
                var level = $("input[name='vip']:checked").val();
                if(level == '' || level == undefined){
	                return false;
                }
                setVip(mid,level);
            }
        });
	});
	
	
	$(".screen").on("click",function(){
		var user_type = $("select[name=user_ident] option:selected").attr('user_type');
		var sub_type = 0;
		if(user_type == 1 || user_type == 2){
			sub_type = $("select[name=sub_ident] option:selected").attr('sub_type');
		}
		window.location.href = "/usermanager/memberInfo.html?type=" + user_type + "&sub_type=" + sub_type;
	});
	
	$(".search").on("click",function(){
		var key = $.trim($('input[name=user]').val());
		if(key == ""){
			layer.alert("请输入搜索信息",{offset:'200px'});
			return;
		}
		window.location.href = "/usermanager/memberInfo.html?key=" + key;
	});
	
	function deleteMember(mid){
		var params = {};
		params.mid = mid;
		$.ajax({
			type:"post",
			dataType:"json",
			data:params,
			url:"/service/deleteUserServ.html",
			success:function(data){
				if(data.ret == 1){
					layer.alert(data.msg,{offset:'200px'},function(){
						location.reload(true);
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
	}
	
	function setVip(mid,level){
		var params = {};
		params.mid = mid;
		params.level = level;
		$.ajax({
			type:"post",
			dataType:"json",
			data:params,
			url:"/service/setVIPServ.html",
			success:function(data){
				if(data.ret == 1){
					layer.alert(data.msg,{offset:'200px'},function(){
						location.reload(true);
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
	}
	
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
}
});