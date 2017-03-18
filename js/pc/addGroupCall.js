var skuids=""; 
var  i=0;
define(function(require,exports,module){
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
	require('cyupload.js');
	var second_type = new Array();
	var third_type = new Array(); 
	exports.callback = function(id,layid){
	    DoCall(id,layid);
	};
});

function DoCall(id,layid)
{
	skuids+=id+",";
	var skuidlist=skuids.split(',');
	if(skuidlist.length>5){
		layer.alert("产品个数已足够");
		return;
	}
	//console.log(skuids);
	var data1={};
	$.ajax({
			type:"GET",
			dataType:"JSON",
			url:"/service/getGroupProServ.html",
			data:{'skuid':id},
			success:function(data){
				if(data.ret == 1){
					
					var dady = window.top.document;
					data1=data.data;
					var images=data1['images'].split(",");
					var h="<li onclick=\'deleteLi(this)\'>\
				            <dl>\
				                <dt>\
				                	<img value=\'\' src=\'"+images[0]+"\' width=\'100px\'  height=\'101px\' alt=\'\' />\
				                	<span value=\'"+data1['skuid']+"\' class=\'deleteimg\'>删除</span>\
				                </dt>\
				                <dd>"+data1['pname']+"</dd>\
				                <dd>￥"+data1['price']+"</dd>\
				            </dl>\
				        </li>\
				        ";
				   var len=$(dady).find("#grouplist li").length;
				   if(len>4){
					   layer.alert("产品个数过多");
					   return;
				   }
					$(dady).find("#grouplist").append(h);
					parent.layer.close(layid);
					i++;
				}else{
					alert(data.msg);
				}
			},
			error:function(data){
				console.log(data);
				alert("服务器错误,请稍后再试1");
			}
		});
		 
}