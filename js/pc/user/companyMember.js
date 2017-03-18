define (function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		init:function(){
			$('.check_state li').click(function(){
			    var state = $(this).val();
			    window.location.href = "/user/companyMember.html?state=" + state;
			});
			
			$(".agree").on("click",function(){
				var mid = $(this).parent().attr('mid');
				var alert = layer.confirm('确定通过该绑定申请?', {
		            btn: ['确定','取消']
		        }, function(){
		        	layer.close(alert);
					handle.handleMember(mid,1,'');
		        }, function(){
		            
		        });
			});
			
			$(".refuse").on("click",function(){
				var mid = $(this).parent().attr('mid');
				var alert = layer.open({
		            type: 1
		            ,title: "拒绝绑定申请"
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
			                handle.handleMember(mid,2,reason);
		                });
		            }
		        });
			});
			
			$(".delete").on("click",function() {
		        var mid = $(this).parent().attr('mid');
				var alert = layer.open({
		            type: 1
		            ,title: "删除公司成员"
		            ,area: '350px'
		            ,shade: 0.5
		            ,btn: ["确定", "取消"]
		            ,btnAlign: 'c'
		            ,content: '<div style="padding: 20px; line-height: 22px; font-weight: 300;">\
					            请填写删除理由\
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
				                layer.alert("删除理由为空",{offset:'200px'});
				                return;
			                }
			                handle.handleMember(mid,3,reason);
		                });
		            }
		        });
		    });
		},
		
		handleMember:function(mid,type,reason){
			var params = {};
			params.mid = mid;
			params.type = type;
			if(type != 1){
				params.reason = reason;	
			}
			
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/handleCompanyServ.html",
				data:params,
				success:function(data){
					if(data.ret == 0){
						layer.alert(data.msg,{offset:'200px'});
					} else {
						layer.alert(data.msg,{offset:'200px'},function(){
							location.reload(true)
						});
					}
				},
				error:function(data){
					layer.alert("服务器错误,请稍后再试",{offset:'200px'});
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