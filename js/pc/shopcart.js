define(function(require){
	var $ = require('jquery');	
	window.onload = function(){  
        var url = window.location.href;  
        var ps = url.split("#");  
        try{  
            if(ps[1] != 1){  
                url += "#1";  
            }else{  
                window.location = ps[0];  
            }  
        }catch(ex){  
            url += "#1";  
        }  
          
        window.location.replace(url);  
	};  
	var userInfo="";
	getUserInfo();
	var handle = {
		init:function(){
			// 设置购买数量
			var down = $("span.down");
			var input = $("input.text");
			var up = $("span.up");
			// 点击减小
			var i = 1;
			down.bind("click",function(){
				var val = $(this).parents(".set_p").find("input").val();
				i = val;
				if (i<2) return;
				i--;
				$(this).parents(".set_p").find("input").val(i);
				var id=$(this).parent().attr("value");
				handle.change1(id,i);
				var single=$(this).parents('.one_shop').find('.singlep').text();
				var sum=single*i;
				sum=sum.toFixed(2);
				$(this).parents('.one_shop').find('.price').text(sum);
				$(this).parents('.one_shop').find('.price').attr('value',sum);
				$(this).parents('.one_shop').find('.tol').attr('value2',sum);
				var total=0;
				$("input.btn").each(function(){
					if($(this).is(':checked')){
						total+=parseFloat($(this).attr("value2"));
					}
				});
				$(".checkprice").text("￥"+total);			
			});
			// 点击增加
			up.bind("click",function(){
				var val = $(this).parents(".set_p").find("input").val();
				i = val;
				i++;
				$(this).parents(".set_p").find("input").val(i);
				var id=$(this).parent().attr("value");
				handle.change1(id,i);
				var single=$(this).parents('.one_shop').find('.singlep').text();
				var sum=single*i;
				sum=sum.toFixed(2);
				$(this).parents('.one_shop').find('.price').text(sum);
				$(this).parents('.one_shop').find('.price').attr('value',sum);
				$(this).parents('.one_shop').find('.tol').attr('value2',sum);
				var total=0;
				$("input.btn").each(function(){
					if($(this).is(':checked')){
						
						total+=parseFloat($(this).attr("value2"));
					}
				});
				total=total.toFixed(2);
				$(".checkprice").text("￥"+total);
			});
			input.change(function(){
				total=0;
				var num=$(this).val();
				var single=$(this).parents('.one_shop').find('.singlep').text();
				var sum=num*single;	
				sum=sum.toFixed(2);			
				$(this).parents('.one_shop').find('.price').text(sum);
				$(this).parents('.one_shop').find('.price').attr('value',sum);
				$(this).parents('.one_shop').find('.tol').attr('value2',sum);
				$(this).parents('.one_shop').find('.btn').attr("value2",sum);
				$(this).attr("value2");
				$("input.btn").each(function(){
					if($(this).is(':checked')){
						total+=parseFloat($(this).attr("value2"));
						i++;
					}
				});
				total=total.toFixed(2);
				$(".checkprice").text("￥"+total);

				var id=$(this).parent().attr("value");
				var num=$(this).val();
				handle.change1(id,num);
				
			});
			$("input.btnall").click(function(){
				var isChecked=$(this).prop("checked");
				$('input.btn').prop("checked",isChecked);
				$('input.btnshop').prop("checked",isChecked);
				$($("input.btnall")).prop("checked",isChecked);
			});
			
			$("input.btnshop").click(function(){
				var isChecked=$(this).prop("checked");
				$(this).parent().parent().find(':checkbox').prop("checked",isChecked);
			});
			
			$(".btn").click(function(){
				var all=false;
				$($(this).parent().parent().parent().parent().parent().find(".btn")).each(function(){
					all=$(this).prop("checked");
					if(all==false){
						return false;
					}
				});
				$(this).parent().parent().parent().parent().parent().find(".btnshop").prop("checked",all);
			});
			$(".btnshop").click(function(){
				var all=false;
				$(this).parent().parent().parent().parent().find(".btnshop").each(function(){
					all=$(this).prop("checked");
					if(all==false){
						return false;
					}
				});
				$(".btnall").prop("checked",all);
			});
			$("#delete_all").click(function(){
				var ids=new Array();
				$(".btn").each(function(){
					if($(this).is(':checked')){
						ids.push($(this).attr("value"));		
					}
				});
				for(var a=0;a<ids.length;a++){
					handle.deleteCart(ids[a]);
				}
			});
			//删除购物车
			$(".delete").click(function(){
				var id=$(this).parent().parent().parent().attr("value");
				handle.deleteCart(id);
			});
			//添加收藏夹
			$(".collection").click(function(){
				var id=$(this).parent().parent().parent().attr("value2");
				handle.addcollection(id);
			});
			$(".collection_all").click(function(){
				var ids=new Array();
				$(".btn").each(function(){
					if($(this).is(':checked')){
						ids.push($(this).attr("value"));		
					}
				});
				for(var a=0;a<ids.length;a++){
					handle.addcollection(ids[a]);
				}
			});
			$("input").click(function(){
				 total=0;
				 var i=0;
				$("input.btn").each(function(){
					if($(this).is(':checked')){
						total+=parseFloat($(this).attr("value2"));
						i++;
					}
				});
				total=total.toFixed(2);
				$(".checkprice").text("￥"+total);
				$(".settlement_total").text("总共"+i+"件");
			});
			$(document).on("click","ul li.li1 label input",function(){
				$(this).attr("checked",this.checked);
			});	
			$(document).on("click","div.settlement span.submit",function(){
						var first=0;
						var ids="";
						$("input.btn").each(function(){
							if($(this).is(':checked')){
								if(first==0){
									ids+=$(this).attr("value");
									first++;
								}else{
									ids+=","+$(this).attr("value");
								}
							}
						});
						var first=0;
						if(ids==""){
							alert("请选择要购买的产品!");
							return;
						}
						self.location.href="/shopping/process.html?type=1&id="+ids+"&aid="+userInfo['address_id'];
			});

		},
		change1:function(id,num){
	  		$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/UpdateUserCartServ.html",
				data:{id:id,num:num},
				success:function(data){
					if(data.ret == 1){
					
					}else if(data.ret == -1){
				        window.location.href ="/member/login.html";
			        }else{
						$("input.text").text(data.msg).show();
					}
				},
				error:function(data){
					$("input.text").text("服务器错误,请稍后再试").show();
				},
				complete:function(){
							
				}
			});
  		},
  		deleteCart:function(id){
	  		$.ajax({
		  		    data:{id:id},
					type:"post",
					dataType:"json",
					url:"/service/DeleteCartServ.html",
					success:function(data){
						if(data.ret == 1){
							var alert = layer.confirm(data.msg, {
								title:"温馨提示",
								btn: ['确认'] //按钮
							}, function(){
								location.reload(true);
							}); 
						}else if(data.ret == -1){
							window.location.href ="/member/login.html";
						}else{
							var alert = layer.confirm(data.msg, {
								title:"温馨提示",
								btn: ['确认'] //按钮
							}, function(){
								layer.close(alert);
							});
						}
					},
					error:function(data){
						var alert = layer.confirm("服务器错误,请稍后再试", {
							title:"温馨提示",
							btn: ['确认','取消'] //按钮
						}, function(){
							layer.close(alert);
						}, function(){
			
						});
					},
					complete:function(){
					   
					}
				});
  		},
  		addcollection:function(id){
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
  		}	 	
	 	
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
	function GetQueryString(name){
	    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return  unescape(r[2]); return null;
	};

	
	$(function(){
		handle.init();
	});
});