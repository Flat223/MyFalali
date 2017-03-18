define(function(require,exports,module){
	var $ = require('jquery');
	require('swiper.min.js');
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
	var a = require('/js/pc/addGroupCall');
	var tab_nav = document.getElementById("tab_nav");
	var tab_box = document.querySelectorAll(".tab_box");
	var pid=GetQueryString('pid');
	var data1=new Array();
	var pb=new Array();
	getSku(pid);
	getBR(pid);
	var ids=new Array();
	//更改产品属性
	var skuid=0;
	var pb=new Array();
	var all=new Array();
	var num=0;
	
	var i = 1;

	var handle = {
 		init:function() {
			layui.use(['layer', 'laypage', 'element'], function(){
				var layer = layui.layer
				,laypage = layui.laypage
				,element = layui.element();
			});
			
			$(".save").click(function(){			
				skuid=data1[0].skuid;
				if(skuid<=0&&data1.length>1){
					layer.alert("请选择产品属性");
					return;
				}
				var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
				a.callback(skuid,index);
/*
				
				parent.layer.close(index);
*/
				
			});
			
			$(document).on("click","#property li",function(){
				$(this).addClass("act").siblings().removeClass("act");
				var style=$(this).css("cursor");
				if(style!='pointer'){
					return false;
				}
			    var id=$(this).attr("value");
				var type=$(this).attr("type");
				var pid=$("input[name=proid]").val();
			    var position=$(this).parent().attr("value");
				ids[position]=id;
		
			    var idarray=ids.toString();
				for(var j=0;j<all.length;j++){
					if($.inArray(all[j],pb)<0){
						$("#"+all[j]).addClass("disable");
					}
					else{
						$("#"+all[j]).removeClass("disable");
					}
				}
				var myarr=new Array();
				var allarr=new Array();
				allarr=idarray.split(",");
				for(var i=0;i<data1.length;i++){
				    if(idarray==data1[i]['properties']){
					   $("#proprice").text(data1[i].price);
					   $(".prd").text("库存("+data1[i].inventory+")");
					   skuid=data1[i]['skuid'];
		
				    };
					var str=(data1[i]['properties']).split(",");
					if($.inArray(id,str) > -1 ) {
						for (var l = 0; l < str.length; l++) {
							myarr.push(str[l]);
							// console.log(str[l]);
						}
					}
			    }
			    // console.log(skuid)
				myarr=delarr(myarr);
				removeByValue(myarr, id)
				// console.log(myarr);
				var broarr=new Array();
			    for(var j=0;j<pb.length;j++){
					if(pb[j]['propertyid']!=type){
						broarr.push(pb[j]);
					}
				}
				for(var k=0;k<broarr.length;k++){
					if($.inArray(broarr[k]['id'],myarr)==-1){
						$("#"+broarr[k]['id']).addClass("disable");
					}else{
						if($("#"+broarr[k]['id']).is(".disable")) {
							$("#" + broarr[k]['id']).addClass("disable");
						}else{
							$("#" + broarr[k]['id']).removeClass("disable");
						}
					}
				}
			});	
			//点击取消
			$(document).on("click","#property li.act",function(){
				$(this).removeClass("act");
				skuid=0;
				var id=$(this).attr("value");
				var type=$(this).attr("type");
				var broarr=new Array();
				for(var j=0;j<pb.length;j++){
					if(pb[j]['propertyid']!=type){
						broarr.push(pb[j]);
					}
				}
				var myarr=new Array();
				for(var i=0;i<data1.length;i++){
					var str=(data1[i]['properties']).split(",");
					if($.inArray(id,str) > -1 ) {
						for (var l = 0; l < str.length; l++) {
							myarr.push(str[l]);
							// console.log(str[l]);
						}
					}
				}
				// console.log(broarr);
				for(var k=0;k<broarr.length;k++){
					if($.inArray(broarr[k]['id'],myarr)==-1){
						$("#"+broarr[k]['id']).removeClass("disable");
					}else{
						// $("#"+broarr[k]['id']).removeClass("disable");
					}
				}
		
			});	
		}
	};
	//去重
	function delarr(arr) {
		var result=[]
		for(var i=0; i<arr.length; i++){
			if(result.indexOf(arr[i])==-1){
				result.push(arr[i])
			}
		}
		return result;
	};
	//删除指定元素
	function removeByValue(arr, val) {
		for(var i=0; i<arr.length; i++) {
			if(arr[i] == val) {
				arr.splice(i, 1);
				break;
			}
		}
	};
	function getBR(id){
		var id=id;
		$.post('/service/GetProductBroServ.html',{"id":id},function(data){
			 pb=eval(data);
		});
	};	
	function getSku(pid){
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/GetProductSkuServm.html",
			data:{pid:pid},
			success:function(data){
				if(data.ret == 1){
					data1=data.data;
				}else{
					//alert(data.msg);
				}
			},
			error:function(data){
				alert("服务器错误,请稍后再试");
			}
		});
	};
	
	layui.use('form', function(){
		var form = layui.form();

	});
	$(".layui-input-block").on("click",function () {
		console.log(1);
		if ($(this).find("input").hasClass("borderColor")){
			$(this).find("input").removeClass("borderColor")
		}else {
			$(this).find("input").addClass("borderColor");
		}

	})


	function GetQueryString(name)
	{
	    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	};	
	
	$(function(){
        handle.init();
    });
});