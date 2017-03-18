
define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var ptid=GetQueryString('ptid');
	var brandid=GetQueryString('brandid');
	var lprice=GetQueryString('m'); 	
	var rprice=GetQueryString('l');
	var sortype=GetQueryString('sort');
	var property=GetQueryString('property');
	var key=GetQueryString('key');
	var handle = {
		init:function(){
			showcompare();
			$(".category p .label-item span.protype").each(function(){
				if($(this).attr('value')==ptid){
					$(this).addClass("act-a");
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
			$("#compare").click(function(){
				var id=getCookie('pid');
				window.location.href="/goods/compare.html?pids="+id;
			});
			$(".category p .label-item span.protype").click(function(){
				$(this).addClass("act-a").siblings().removeClass("act-a");
				ptid=$(this).attr('value');
				var url="/goods/productList.html?ptid="+ptid+"&sort=1";
				if(brandid){
					url+="&brandid="+brandid;
				}
				if(lprice){
					url+="&m="+lprice;
				}
				if(rprice){
					url+="&l="+rprice;
				}
				if(property){
					url+="&property="+property;
				}
				if(key!=null){
					url+="&key="+escape(key);
				}
				window.location.href=url;

			});
			
			$(".category p .label-item1 span").click(function(){				
            	$(this).addClass("act-a").siblings().removeClass("act-a");
				var price=$(this).text();
				var prilist=price.split('-');
				lprice=prilist[0];
				rprice=prilist[1];
				var url="/goods/productList.html?ptid="+ptid;
				if(brandid){
					url+="&brandid="+brandid;
				}
				if(lprice){
					url+="&m="+lprice;
				}
				if(rprice){
					url+="&l="+rprice;
				}
				if(sortype){
					url+="&sort="+sortype;
				}
				if(property){
					url+="&property="+property;
				}
				if(key!=null){
					url+="&key="+escape(key);
				}
				window.location.href=url;
        	});
        	
        	$(".category p .label-item1 span").each(function(){
	        	var price=$(this).text();
	        	var p=lprice+"-"+rprice;
	        	if(p==price){
		        	$(this).addClass("act-a").siblings().removeClass("act-a");
	        	}
        	});
			$(".sort-ul li").click(function(){
				$(this).addClass("sort-this").siblings().removeClass("sort-this");
				var  stype=$(this).attr("value");
				if(sortype==3&&stype==3){
					sortype=5;
				}else if(sortype==5&&stype==5){
					sortype=3;
				}else {
					sortype=stype;
				}
				var url="/goods/productList.html?ptid="+ptid+"&sort="+sortype;
				if(brandid!=null){
					url+="&brandid="+brandid;
				}
				if(lprice){
					url+="&m="+lprice;
				}
				if(rprice){
					url+="&l="+rprice;
				}
				if(property){
					url+="&property="+property;
				}
				if(key!=null){
					url+="&key="+escape(key);
				}
				window.location.href=url;
    		});

			$(".sortprice").on("click",function () {
				// console.log(1);
				if ($(this).find("span").hasClass("sortarrow")){
					// console.log(2);
					$(this).find("span").addClass("sortpricedown");
				}else if($(this).find("span").hasClass("sortpriceup")) {
					console.log(3);
					$(this).find("span").removeClass("sortpriceup");
					$(this).find("span").addClass("sortpricedown");
				}
			})
    		
    		$(".sort-ul li").each(function(){
				var type=$(this).attr("value");
				if(sortype==type){
					if(sortype==3){
						$(this).show();
						$(this).addClass("sort-this").siblings().removeClass("sort-this");
						$(".priceup").hide();
					}else if(sortype==5){
						$(this).show();
						$(this).addClass("sort-this").siblings().removeClass("sort-this");
						$(".pricedown").hide();
					}else {
						$(this).addClass("sort-this").siblings().removeClass("sort-this");
					}
				}			
    		});
    		
    		$(".category p .label-item span.protype1").click(function(){
	    		var pro=$(this).attr("value");
				var pos=$(this).attr("data-position");
				pro = pos+":"+pro;
				//console.log(pro);
				if(property==null){
					property=pro;
				}else{
					var thisPostion = pos;
					var thisPostionArray = pos.split("-");
					var thisPostionName = thisPostionArray[0];
					var thisPostionValue = thisPostionArray[1];
					
					var propertyArray = Array();
					propertyArray = property.split(",");
					var has = false;
					for(var i = 0; i < propertyArray.length;i++)
					{
						var itemsplit = propertyArray[i].split(":");
						var itemPostion = itemsplit[0];
						var itemPostionArray = itemPostion.split("-");
						var itemPostionName = itemPostionArray[0];
						var itemPostionValue = itemPostionArray[1];
						
						if(itemPostionName == thisPostionName)
						{
							propertyArray[i] = pro;
							has = true;
						}
					}
					
					if(!has)
					{
						propertyArray.push(pro);
					}
					property = propertyArray.join(",");
				}
				var url="/goods/productList.html?ptid="+ptid+"&property="+property;
				if(brandid){
					url+="&brandid="+brandid;
				}
				if(lprice){
					url+="&m="+lprice;
				}
				if(rprice){
					url+="&l="+rprice;
				} 
				if(key!=null){
					url+="&key="+escape(key);
				}
				window.location.href=url;
    		})
 
			$(".category p .label-item span.protype1").each(function(){ 
				var pro=$(this).attr("value");
				var propertyarray=property==null|| property =="undefined" ?Array():property.split(',');
				var last=Array();
				for(var b=0;b<propertyarray.length;b++){
					var temp=propertyarray[b].split(":")[1];
					last.push(temp);
				}
				for(var a=0;a<last.length;a++){
					if(pro==last[a]){
						$(this).addClass("act-a").siblings().removeClass("act-a");
					}
				}
			});
    		

			$(".more").on("click",function () {
				if($(".list").hasClass("list")){
					$(".list").removeClass("list").addClass("listauto");
					$(".more").html("收起&and;");
				}else{
					$(".listauto").removeClass("listauto").addClass("list");
					$(".more").html("更多&or;");
				}
			});
			$(".more-classify").on("click",function () {
				if($(".classify").hasClass("h120")){
					$(".classify").removeClass("h120").addClass("hauto");
					$(".more-classify").removeClass("h120").addClass("hauto");
					$(".more-classify").html("收起&and;");     
				}else{
					$(".classify").removeClass("hauto").addClass("h120");
					$(".more-classify").removeClass("hauto").addClass("h120");
					$(".more-classify").html("更多&or;");
				}
			});
			$(".brand-ul li").click(function(){
				$(this).addClass("act-a").siblings().removeClass("act-a");
				var newid=$(this).attr("value");
				var url="/goods/productList.html?ptid="+ptid+"&brandid="+newid;
				if(sortype){
					url+="&sort="+sortype;
				}
				if(lprice){
					url+="&m="+lprice;
				}
				if(rprice){
					url+="&l="+rprice;
				}
				if(property){
					url+="&property="+property;
				}
				if(key!=null){
					url+="&key="+escape(key);
				}
				window.location.href=url;
			});
			$(".brand-ul li").each(function(){
				var newid=$(this).attr("value");
				if(newid==brandid){
					$(this).addClass("act-a").siblings().removeClass("act-a");
				}
			});
			$(".sure").click(function(){
				lprice=$("#lprice").val();
				rprice=$("#rprice").val();
				if(lprice==""){
					lprice=0;
				}
				if(lprice<0){
					layer.alert("请输入正确的价格范围",{offset:'200px'});
				}else{
					var url="/goods/productList.html?m="+lprice+"&l="+rprice;
					if(ptid){
						url+="&ptid="+ptid;
					}
					if(brandid){
						url+="&brandid="+brandid;
					}
					if(sortype){
						url+="&sort="+sort;
					}
					if(property){
						url+="&property="+property;
					}
					if(key!=null){
						url+="&key="+escape(key);
					}
					window.location.href=url;

				}
			})
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
						console.log(data);
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
