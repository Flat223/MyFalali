define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	var id=GetQueryString('id');
	var type=GetQueryString('type');
	var rank=GetQueryString('sort');
	var page=GetQueryString('page');

	
	var handle = {
		init:function(){
			// showcompare();
			//alert(getCookie('pid'));
			$(".sort ul li").click(function () {
				$(this).addClass("sort-this").siblings().removeClass("sort-this");
				var newrank=$(this).attr('value');
				window.location.href="/brand/detail.html?id="+id+"&type="+type+"&sort="+newrank;
								
			});
			$(".category span").click(function () {
				$(this).addClass("act-a").siblings().removeClass("act-a");
				var newtype=$(this).attr('value');
				window.location.href="/brand/detail.html?id="+id+"&type="+newtype+"&sort=1";
			});
			$(".category span").each(function(){
				var value1=$(this).attr('value');
				if(value1==type){
					$(this).addClass("act-a").siblings().removeClass("act-a");
				}
			});
			$(".sort ul li").each(function(){
				var value1=$(this).attr('value');
				if(value1==rank){
					$(this).addClass("sort-this").siblings().removeClass("sort-this");

				}
			});
			$(".compare").click(function(){
				var id=$(this).attr("value");
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
				var alert = layer.confirm("添加成功", {
								title:"温馨提示",
								btn: ['确认'],
								closeBtn:0, //按钮
							}, function(){
								location.reload(true);
							});
				//alert(getCookie('pid'));
			});
			$("#compare").click(function(){
				var id=getCookie('pid');
				window.location.href="/goods/compare.html?pids="+id;
			});
			$(".clear_compare").click(function(){
				delCookie('pid');
				var alert = layer.confirm("清空成功", {
								title:"温馨提示",
								btn: ['确认'],
								closeBtn:0, //按钮
							}, function(){
								location.reload(true);
							});
			});
			
		}
		
	};
	
	function showcompare(){
		var id=getCookie('pid');
			$.ajax({
				type: "post",//数据提交的类型（post或者get）
		        url:"/service/GetCompareListServ.html",	           
		        dataType: "json",//返回的数据类型
				data:{'ids':id},
				success:function(data){
					var str = "";
					if(data.ret == 1){
						var data=data.data;
						if(data.length>0){
							$(".constr").show();
						}
						for(var x=0;x<data.length;x++){
							var images=data[x].images.split(',');
							str+="<li>\
    		    					<dl>\
    		    						<dt><img src=\'"+images[0]+"\' alt=\'\'></img></dt>\
										<dd>\
											<h3>"+data[x].name+"</h3>\
											<span>"+data[x].price+"</span>\
										</dd>\
									</dl>\
								</li>\
								";
						}
						$("#compare_add").append(str);
						var a=4-data.length;
						var str2="";
						var c=data.length;;
						for(var b=0;b<a;b++){
							if(data.length>0){
								c++;
							}else{
								c=b+1;
							}
							str2+="<li>\
	    		    					<dl>\
	    		    						<dt>\
	    		    							<span>"+c+"</span\>\
											</dt>\
											<dd>\
												<h4>您还可以继续添加</h4>\
											</dd>\
										</dl>\
									</li>\
									";	
						}
						$("#compare_add").append(str2);
					}else{
						layer.alert(data.msg,{offset:'200px'});
					}
				},
				error:function(data){
					layer.alert('服务器错误,请稍后再试',{offset:'200px'});
				}
			});
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
        	document.cookie= name + "="+cval+";expires="+exp.toGMTString();
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