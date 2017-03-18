$(document).ready(function(){
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
	
	$(".agree").on("click",function () {
		var mid = $(this).parent().attr('mid');
		var company_type = $(this).parent().attr('company_type');
		var alert = layer.confirm("确定通过该公司审核", {
						title:"温馨提示",
						btn: ['确认','取消'] //按钮
					}, function(){
						layer.close(alert);
						operateCompany(mid,1,company_type);
					}, function(){
						
					});
    });
    
    $(".screen").on("click",function(){
		var company_type = $("select[name=user_ident] option:selected").attr('company_type');
		window.location.href = "/checkManager/companyCheckManager.html?company_type=" + company_type;
	});
   
	$('.refuse').on('click',function(){
		var mid = $(this).parent().attr('mid');
		var company_type = $(this).parent().attr('company_type');
		var alert = layer.confirm("确定拒绝该公司审核", {
						title:"温馨提示",
						btn: ['确认','取消'] //按钮
					}, function(){
						layer.close(alert);
						operateCompany(mid,2,company_type)
					}, function(){
						
					});
	});
	
	$('.delete').on('click',function(){
		var mid = $(this).parent().attr('mid');
		var company_type = $(this).parent().attr('company_type');
		var alert = layer.confirm("确定删除该条申请记录", {
						title:"温馨提示",
						btn: ['确认','取消'] //按钮
					}, function(){
						layer.close(alert);
						deleteCompany(mid,company_type);
					}, function(){
						
					});
	});
	
	function operateCompany(mid,type,company_type){
		var params = {};
		params.mid = mid;
		params.type = type;
		params.company_type = company_type;
		$.ajax({
			type:"post",
			dataType:"json",
			data:params,
			url:"/service/CheckCompanyServ.html",
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
	};
	
	function deleteCompany(mid,company_type){
		var params = {};
		params.mid = mid;
		params.company_type = company_type;
		$.ajax({
			type:"post",
			dataType:"json",
			data:params,
			url:"/service/delCompanyCheckServ.html",
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
	};
});