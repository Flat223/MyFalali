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
		var college_mid = $(this).parent().attr('college_mid');
        layer.open({
            type: 1
            ,title: "设置科研基金"
            ,area: '300px;'
            ,shade: 0.5
            ,btn: ["确定", "取消"]
            ,btnAlign: 'c'
            ,content: '<div style="padding: 20px; line-height: 22px; font-weight: 300;">设置科研基金 <input class="fund" type="number" style="line-height: 25px;border:1px solid #ddd;padding-left:5px;"></div>'
            ,success: function(layero){
                var btn = layero.find('.layui-layer-btn');
                btn.find('.layui-layer-btn0').click(function(){
	                var fund = $.trim($(".fund").val());
	                operateFund(mid,college_mid,1,fund);
                });
            }
        });
        
        $('.fund').on('input',function(){
	     	var fund = $(this).val();
	     	if(fund < 0){
		     	$(this).val(-fund);
	     	}
	     	if(fund == 0){
		     	$(this).val('');
	     	}
        });
    });
    
    $('.funddetail').on('click',function(){
	    var fund = $(this).attr('fund');
		layer.alert("申请所得金额:"+fund,{offset:'200px'});
    });
		
	$('.refuse').on('click',function(){
		var mid = $(this).parent().attr('mid');
		var college_mid = $(this).parent().attr('college_mid');
		layer.open({
            type: 1
            ,title: "拒绝科研基金申请"
            ,area: '350px'
            ,shade: 0.5
            ,btn: ["确定", "取消"]
            ,btnAlign: 'c'
            ,content: '<div style="padding: 20px; line-height: 22px; font-weight: 300;">\
			            请填写拒绝理由\
						<p style="margin-top: 10px">\
				            <textarea class="refuse_reason" cols="48" rows="7"\
				            	style="line-height: 25px;border:1px solid #ddd;padding-left:5px;">\
				            </textarea>\
				        </p>\
			            </div>'
            ,success: function(layero){
                var btn = layero.find('.layui-layer-btn');
                btn.find('.layui-layer-btn0').click(function(){
	                var reason = $.trim($(".refuse_reason").val());
	                if(reason == ""){
		                layer.alert("拒绝理由为空",{offset:'200px'});
		                return;
	                }
	                operateFund(mid,college_mid,2,reason)
                });
            }
        });
	});
	
	$('.delete').on('click',function(){
		var mid = $(this).parent().attr('mid');
		var college_mid = $(this).parent().attr('college_mid');
		var alert = layer.confirm("确定删除", {
						title:"温馨提示",
						btn: ['确认','取消'] //按钮
					}, function(){
						layer.close(alert);
						deleteFund(mid,college_mid);
					}, function(){
						
					});
	});
	
	function operateFund(mid,collegeMid,type,info){
		var params = {};
		params.mid = mid;
		params.collegeMid = collegeMid;
		params.type = type;
		params.info = info;
		$.ajax({
			type:"post",
			dataType:"json",
			data:params,
			url:"/service/OperateFundServ.html",
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
	
	function deleteFund(mid,collegeMid){
		var params = {};
		params.mid = mid;
		params.collegeMid = collegeMid;
		$.ajax({
			type:"post",
			dataType:"json",
			data:params,
			url:"/service/deleteApplyFundRecordServ.html",
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