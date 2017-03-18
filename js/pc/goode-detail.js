define(function(require){
	var $ = require('jquery');
	require('swiper.min.js');
	// require('jquery.jqzoom.js');
	require('/js/pc/prod.js');
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
	var tab_nav = document.getElementById("tab_nav");
	var tab_p = tab_nav.getElementsByTagName("p");
	var tab_box = document.querySelectorAll(".tab_box");
	for(var i =0; i<tab_p.length; i++){
	    tab_p[i].index = i;
	    tab_p[i].onclick = function () {
	        for(var n=0; n<tab_box.length; n++){
	            tab_p[n].className = "";
	            tab_box[n].style.display = "none";
	        };
	        this.className = "act";
	        tab_box[this.index].style.display = "block";
	    };
	};
	var userInfo="";
	var down = $("span.down");
	var input = $("input.text");
	var up = $("span.up");
	var pid=GetQueryString('pid');
	var data1=new Array();
	var pb=new Array();
	var cid=0;
	getSku(pid);
	getBR(pid);
	getUserInfo();
	var ids=new Array();
	//更改产品属性
	var skuid=0;
	var pb=new Array();
	var all=new Array();
	var num=0;
	
	var i = 1;
/*
	window.onload=function(){ 
		var normal=$(".content").attr("value");
		if(normal>0){
			var alert = layer.confirm("产品信息有误，点击确认返回上一界面", {
				title:"温馨提示",
				btn: ['确认'],
				closeBtn:0 //按钮
				}, function(){
					window.history.go(-1);
				});
		}

	} 
*/
	var handle = {
 		init:function() {
	 		
	 		getUserInfo();
	 		// 点击减小
			down.bind("click",function(){
			    if (i<2) return;
			    var val = $(this).parents(".set_p").find("input").val();
			    i = val;
			    i--;
			    $(this).parents(".set_p").find("input").val(i);
			});
			// 点击增加
			up.bind("click",function(){
			    var val = $(this).parents(".set_p").find("input").val();
			    i = val;
			    i++;
			    $(this).parents(".set_p").find("input").val(i);
			});
			layui.use(['layer', 'laypage', 'element'], function(){
				var layer = layui.layer
				,laypage = layui.laypage
				,element = layui.element();
			});
			
			$(".groupbuy").click(function(){
				getUserInfo();
				if(userInfo.type==3){
					layer.alert("您的身份不能购买");
					return;
				}else if(userInfo.type==1&&userInfo.sub_type==0){
					layer.alert("您的身份不能购买");
					return;
				}else if(userInfo.type==2&&userInfo.sub_type==0){
					layer.alert("您的身份不能购买");
					return;
				}
				var id=$(this).attr("value2");
				window.location.href="/shopping/process.html?type=3&id="+id+"&aid="+userInfo['address_id'];
				
			});
							
			$(".province").click(function(){
				var name=$(this).attr('value2');
				var id=$(this).attr('value');
				$(".lab-province").text(name);
				$(".lab-city").text("请选择");
				$(".lab-town").text("请选择");
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/GetCityTownServ.html",
					data:{'aid':id},
					success:function(data){
						if(data.ret == 1){
							var str="";
							var d=data.data;
							//console.log(d);
							for(var a=0;a<d.length;a++){
								str+="<span class=\'city\' value=\'"+d[a].id+"\' value2=\'"+d[a].name+"\'>"+d[a].name+"</span>\
								";
							}
							$('.lab-city-item').find('span').remove();
							$('.lab-city-item').append(str);
						}else{
							
						}
					},
					error:function(data){
						alert("服务器错误,请稍后再试");
					}

				});
			});			
			$(document).on("click",".city",function(){
				var name=$(this).attr('value2');
				cid=$(this).attr('value');
				$(".lab-city").text(name);
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/GetCityTownServ.html",
					data:{'aid':cid},
					success:function(data){
						if(data.ret == 1){
							var str="";
							var d=data.data;
							//console.log(d);
							for(var a=0;a<d.length;a++){
								str+="<span class=\'town\' value=\'"+d[a].id+"\' value2=\'"+d[a].name+"\'>"+d[a].name+"</span>\
								";
							}
							$('.lab-town-item').find('span').remove();
							$('.lab-town-item').append(str);
							$('.lab-town').attr("style","display: inline-block;");
						}else{
							
						}
					},
					error:function(data){
						alert("服务器错误,请稍后再试");
					}
				});
			});
			$(document).on("click",".town",function(){
				var name=$(this).attr('value2');
				$("#place").text(name);
				var oldpid=$('.Add_cart').attr("value");
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/GetProductFreightServ.html",
					data:{'cid':cid,'pid':oldpid},
					success:function(data){
						var str="";
						if(data.ret == 2){
							var d=data.data;
							str+="<li>"+d['mode']+"<d></d></li>\
							";
							$('.freight').find('li').remove();
							$('.freight').append(str);
						}else if(data.ret==1){
							if(data['expressp']>=0){
								str+="<li>快递 ￥"+data['expressp']+"<d></d></li>\
								";
							}
							if(data['emsp']>=0){
								str+="<li>ems ￥"+data['emsp']+"<d></d></li>\
								";
							}
							if(data['mailp']>=0){
								str+="<li>邮寄 ￥"+data['mailp']+"<d></d></li>\
								";
							}
							$('.freight1').text("快递 ￥"+data['expressp']);
							$('.freight').find('li').remove();
							$('.freight').append(str);
						}
					},
					error:function(data){
						alert("服务器错误,请稍后再试");
					}
				});
				
			});
			$(document).on('click','.freight li',function(){
				var v=$(this).text();
				$('.free-con').attr('style',"display:none");
				$('.freight1').text(v);
				
			});
			$(document).on("click","#property li",function(){
				// console.log(pb);
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
				// console.log(pb);
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
			$("#buy_now").click(function(){
				if(userInfo==""){
					window.location.href="/member/login.html";
					return;
				}
				if(userInfo.type==3){
					layer.alert("您的身份不能购买");
					return;
				}else if(userInfo.type==1&&userInfo.sub_type==0){
					layer.alert("您的身份不能购买");
					return;
				}else if(userInfo.type==2&&userInfo.sub_type==0){
					layer.alert("您的身份不能购买");
					return;
				}
				var num=0;
				num=parseInt($('.text').val());
				
				if(skuid==0||skuid==null){
					skuid=data1[0].skuid;
				}
				//skuid=data1[0].skuid;
				for(var i=0;i<data1.length;i++){
					if(skuid==parseInt(data1[i]['skuid'])){
						if(num>parseInt(data1[i]['inventory'])){
							layer.alert("购买数量大于库存数量！");
							return;
						}
					}
				}
				
				var testing=$(".borderColor").attr("value2");
				if(testing==1){
					testing=1;
				}else {
					testing=0;
				}
				var year=$(".layui-this").attr("lay-value");
				if(year>0){
					
				}else{
					year=0;
				} 
				if(pid<=0){
					layer.alert("产品信息有误");
				}else{
					if(skuid<=0&&data1.length>1){
						layer.alert("请选择产品属性");
						return;
					}else{
						if(skuid<=0&&data1.length==1){
							window.location.href="/shopping/process.html?type=2&skuid="+skuid+"&num="+$('.text').val()+"&pid="+pid+"&testing="+testing+"&guarantee="+year+"&aid="+userInfo['address_id'];
						}
					}
					window.location.href="/shopping/process.html?type=2&skuid="+skuid+"&num="+$('.text').val()+"&pid="+pid+"&testing="+testing+"&guarantee="+year+"&aid="+userInfo['address_id'];		
				}
			});	
			$(".more").click(function(){
				var brandid=$(this).attr("value");
				window.location.href="/brand/detail.html?id="+brandid+"&type=1&sort=1&page=1";
			});
	
			$("#add_collect").click(function(){
				
				var tpid=$(this).attr("value");
				addcollection(tpid);
			});	
			$("#add_cart").click(function(){
				if(userInfo==""){
					window.location.href="/member/login.html";
					return;
				}
				if(userInfo.type==3){
					layer.alert("您的身份无购物车功能");
					return;
				}else if(userInfo.type==1&&userInfo.sub_type==0){
					layer.alert("您的身份无购物车功能");
					return;
				}else if(userInfo.type==2&&userInfo.sub_type==0){
					layer.alert("您的身份无购物车功能");
					return;
				}
				var tpid=$(this).attr("value");
				var testing=$(".borderColor").attr("value2");
				if(testing==1){
					testing=1;
				}else {
					testing=0;
				}
				var year=$(".layui-this").attr("lay-value");
				if(year>0){
					
				}else{
					year=0;
				} 
				var num=0;
				num=$('.text').val();
				if(skuid==0||skuid==null){
					skuid=data1[0].skuid;
				}
				//skuid=data1[0].skuid;
				if(pid<=0){
					layer.alert("产品信息有误");
				}else{
					if(skuid<=0&&data1.length>1){
						layer.alert("请选择产品属性");
						return;
					}else{
						$.ajax({
							type:"post",
							dataType:"json",
							url:"/service/addCartServ.html",
							data:{'num':num,'skuid':skuid,'pid':tpid,'testing':testing,'guarantee':year},
							success:function(data){
								if(data.ret == 1){
									layer.alert(data.msg);
								}else{
									layer.alert(data.msg);
								}
							},
							error:function(data){
								alert("服务器错误,请稍后再试");
							}
						});
					}
				}
				
			});
			var skuid=GetQueryString('skuid');
			if(skuid!=null&&skuid>0){
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/getSkuDetailServ.html",
					data:{'skuid':skuid},
					success:function(data){
						if(data.ret == 1){
							var data=data.data;
							var properties=data.properties;
							var pro=new Array();
							pro=properties.split(",");
							for(var i=0;i<pro.length;i++){
								$("#property li").each(function(){
									if($(this).attr("value")==pro[i]){
										$(this).attr("class",'act');
									}
								});
							}
							$("#proprice").text(data.price);
							$(".prd").text("库存("+data.inventory+")");
							pid=data.pid;
							skuid=data.skuid;
							ids=pro;
						}else{
							alert(data.msg);
						}
					},
					error:function(data){
						alert("服务器错误,请稍后再试");
					}
				});
			};		
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
		})
	};
	function getUserInfo(){
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/GetUserInfoServ.html",
			data:{},
			success:function(data){
				if(data.ret==1){
					userInfo=data.data;
				}else if(data.ret==0){
					//window.location.href="/member/login.html";
				}
			},
			error:function(data){
				alert("服务器错误,请稍后再试");
			}

		});
	};
	
	function getSku(pid){
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/GetProductSkuServ.html",
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
	
  	function addcollection(id){
  		$.ajax({
	  		    data:{id:id},
				type:"post",
				dataType:"json",
				url:"/service/AddCollectionServ.html",
				success:function(data){
					if(data.ret == 1){
						var alert = layer.confirm(data.msg, {
							title:"温馨提示",
							btn: ['确认'],
							closeBtn:0 //按钮
						}, function(){
							layer.close(alert);
						}); 
					}else if(data.ret == -1){
						window.location.href ="/member/login.html";
					}else{
						var alert = layer.confirm(data.msg, {
							title:"温馨提示",
							btn: ['确认'],
							closeBtn:0 //按钮
						}, function(){
							window.location.reload(true);
						});
					}
				},
				error:function(data){
					var alert = layer.confirm("服务器错误,请稍后再试", {
						title:"温馨提示",
						btn: ['确认','取消'] //按钮
					}, function(){
						window.location.reload(true);
					}, function(){
		
					});
				},
				complete:function(){
				   
				}
			});
	};
	layui.use('form', function(){
		var form = layui.form();

	});
	$(".layui-input-block").on("click",function () {
		// console.log(1);
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