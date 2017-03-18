define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		init:function(){
			$(".add_compare").click(function(){
				var id=$(this).parent().parent().attr("value");
				var cpid=getCookie('pid');
				if(cpid!=null){
					var pidarray=cpid.split(',');
					if(pidarray.length>3){
						layer.alert("对比栏已满！",{offset:'200px'});
						return;
					}
					id=cpid+','+id;
				}
				setCookie('pid',id);
				var cpid1=getCookie('pid');
				window.location.href="/goods/compare.html?pids="+cpid1;
			});
			$(".img").click(function(){
				var id=$(this).parent().parent().attr("value");
				window.location.href="/goods/detail.html?pid="+id;
			});
			$(".more").click(function(){
				var ptid=$(this).attr("value");
				window.location.href="/goods/productList.html?ptid="+ptid;
			});
			$(".add").click(function(){
				var ptid=$(this).attr("value");
				window.location.href="/goods/productList.html?ptid="+ptid;
				
			});
		}	
	};
	
	function setCookie(name,value){
		var exp=new Date();
		exp.setTime(exp.getTime()+30*24*60*60*1000);
		document.cookie=name+"="+escape(value)+";expires="+exp.toGMTString()+";path="+"/";
	};
	
	function getCookie(name){
		var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
		if(arr=document.cookie.match(reg)){
        	return unescape(arr[2]);
		}else{ 
        	return null; 
        }
	};
	
	function delCookie(name) 
	{ 
	    var exp = new Date(); 
	    exp.setTime(exp.getTime() - 1); 
	    var cval=getCookie(name); 
	    if(cval!=null){
        	document.cookie= name + "="+cval+";expires="+exp.toGMTString()+";path="+"/";
        }
	}; 
	
	function GetQueryString(name){
	    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return  unescape(r[2]); return null;
	};

	
	$(function(){
		handle.init();
	});
});
