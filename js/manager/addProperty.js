$(document).ready(function(){
	var questflag = 0;
	var ptid = $("input[name=ptid]").val();
	
    $(".del_type").on("click",function(){
        var alert = layer.confirm("确定删除？", {
            title:"<b>删除后该类和其属性将消失!!</b>",
            btn: ['确认','取消'] //按钮
        }, function(){
            deletetype();
        }, function(){

        });
    });
    $(".add_property").on("click",function(){
	    var str = "	 <tr>\
		    			<td>新属性</td>\
				        <td>\
					        <input name=\'propertyname\' type=\'text\'>\
					    </td>\
						<td>\
							<span class=\'del_addedPro\'>删除</span>\
						</td>\
					 </tr>";
		$('.property_container').append(str);
    });
	$(document).on('click','.del_addedPro',function(){
		$(this).parent().parent().remove();
	});
	
	$(".del_property").on("click",function(){
        var id = $(this).attr('proid');
        var alert = layer.confirm("确定删除？", {
            title:"温馨提示",
            btn: ['确认','取消'] //按钮
        }, function(){
            deleteProperty(id);
        }, function(){

        });
    });
    
    $(".update").on("click",function(){
        var proid = $(this).attr('proid');
        var proname = $(this).attr('proname');
	    layer.open({
	        type: 1
	        ,title: "修改属性名称"
	        ,area: '330px;'
	        ,shade: 0.5
	        ,btn: ["确定", "取消"]
	        ,btnAlign: 'c'
	        ,content: '<div style="padding: 20px; line-height: 22px; font-weight: 300;">请输入属性名称<input class="proname" type="text" style="line-height: 30px;border:1px solid #ddd;padding-left:5px;margin-left:15px;width:160px" value=" ' + proname + ' "></div>'
	        ,success: function(layero){
	            var btn = layero.find('.layui-layer-btn');
	            btn.find('.layui-layer-btn0').click(function(){
	                var name = $.trim($('.proname').val());
	                if(name == ""){
		            	layer.alert('属性名称为空');
			            return ;    
	                } else {
		                updatePropertyName(proid,name);
	                }
	            });
	        }
	    });
    });
	
	$(".save").on("click",function(){
	    if(questflag == 1){
		    return;
	    }
	    var $propertyName = $('input[name=propertyname]');
	    var len = $propertyName.length;
		if(len == 0){
			layer.alert("请先添加属性");
			return;
		}
		var arr = new Array();
		var flag = true;
		$propertyName.each(function(){
			var name = $.trim($(this).val());
			if(name == ""){
				layer.alert("属性名称存在空值");
				flag = false;
				return false;
			}
			if($.inArray(name,arr) >= 0){
				layer.alert("属性名称存在重复");
				flag = false;
				return false;
			}
			arr.push(name);
		});
		if(!flag){
			return;
		}
		
		questflag = 1;
        var params = {};
        params.ptid = ptid;
        params.propertyNames = arr.join(',');
        $.ajax({
            type: "POST",
            url: "/service/addprotypeServ.html",
            data: params,
            dataType: "json",
            success: function (data) {
                if(data.ret == 1){
	                layer.alert(data.msg,function(){
		            	parent.location.reload();
	                    window.setTimeout(widreload,1000); 
	                });
                }else{
                    layer.alert(data.msg);
                }
            },
            error: function (data) {
                layer.alert("服务器错误");
            },
            complete:function(){
	            questflag = 0;
            }
        });
    });
    
    function deletetype(){
        $.ajax({
            type: "POST",
            url: '/service/deleteprotypeServ.html',
            data: {"ptid":ptid},
            dataType: "json",
            success: function (data) {
                if(data.ret == 1){
                    layer.alert(data.msg,function(){
		            	parent.location.reload();
	                    window.setTimeout(widreload,1000); 
	                });
                }else{
                    layer.alert(data.msg);
                }
            },
            error: function (data) {
                layer.alert("服务器错误");
            }
        });
    };
    
    function deleteProperty(id){
        var url='/service/deletePropertyServ.html';
            $.ajax({
            type: "POST",
            url: url,
            data: {"proid":id,'ptid':ptid},
            dataType: "json",
            success: function (data) {
                if(data.ret==1){
	                layer.alert(data.msg,function(){
		            	location.reload(true);    
	                });
                }else{
                    layer.alert(data.msg);
                }
            },
            error: function (data) {
                layer.alert("服务器错误");
            }
        });
    };
    function updatePropertyName(proid,name){
	    var params = {};
	    params.ptid = ptid;
	    params.proid = proid;
		params.name = name;
	    $.ajax({
	        type: "POST",
	        url: "/service/updatePropertyServ.html",
	        data: params,
	        dataType: "json",
	        success: function (data) {
	            if(data.ret==1){
	                layer.alert(data.msg,function(){
		            	location.reload(true);    
	                });
	            }else{
	                layer.alert(data.msg);
	            }
	        },
	        error: function (data) {
	            layer.alert("服务器错误");
	        }
	    });
	}
    
    function widreload() {
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index);
    }
});