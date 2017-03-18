$(document).ready(function(){	
	$(".search").on("click",function(){
		var key = $.trim($('input[name=admin]').val());
		if(key == ""){
			layer.alert("请输入搜索信息",{offset:'200px'});
			return;
		}
		window.location.href = "/usermanager/adminInfo.html?key=" + key;
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
		var aid = $(this).parent().attr('aid');
		var alert = layer.confirm("确定删除该管理员", {
						title:"温馨提示",
						btn: ['确认','取消'] //按钮
					}, function(){
						layer.close(alert);
						deleteAdmin(aid);
					}, function(){
						
					});
	});
	
	$('.setIdent').on("click",function(){
		var aid = $(this).parent().attr('aid');
		$('.setIdentBody').show();
		layer.open({
            type: 1
            ,title: "设置管理员身份"
            ,area: '300px;'
            ,shade: 0.5
            ,btn: ["确定", "取消"]
            ,btnAlign: 'c'
            ,content: $('.setIdentBody')
            ,success: function(layero){
                var btn = layero.find('.layui-layer-btn');
                btn.find('.layui-layer-btn0').click(function(){
					var rid = $('select[name=setIdent] option:selected').attr('rid');
					setAdminIdent(aid,rid);
                });
            }
        });
	});
	
	function deleteAdmin(aid){
		$.ajax({
			type:"post",
			dataType:"json",
			data:{aid:aid},
			url:"/service/deleteAdminServ.html",
			success:function(data){
				var alert = layer.alert(data.msg,{offset:'200px'},function(){
					layer.close(alert);
					if(data.ret == 1){
						location.reload(true)
					} 
				});
			},
			error:function(data){
				layer.alert("服务器错误,请稍后再试",{offset:'200px'});
			},
			complete:function(){
				
			}
		});
	}
	
	function setAdminIdent(aid,rid){
		$.ajax({
			type:"post",
			dataType:"json",
			data:{aid:aid,rid:rid},
			url:"/service/setAdminIdentServ.html",
			success:function(data){
				var alert = layer.alert(data.msg,{offset:'200px'},function(){
					layer.close(alert);
					if(data.ret == 1){
						location.reload(true)
					} 
				});
			},
			error:function(data){
				layer.alert("服务器错误,请稍后再试",{offset:'200px'});
			},
			complete:function(){
				
			}
		});
	}
});