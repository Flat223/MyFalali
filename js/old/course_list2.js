define(function(require){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	var queryflag = 0;
	var buyflag = 0;
	var ctid = 0;
	var currentpage = 1;
	
	var handle = {
		init:function(){
			$(document).on("click",".pra_page li a",function(){
				if(!$(this).hasClass('act')){
					return;
				}
				if($(this).hasClass("previous")){
					handle.getPractices(currentpage-1);
					return;
				}
				if($(this).hasClass("next")){
					handle.getPractices(currentpage+1);
					return;
				}
			});
			$("#courses_buy").on("click",function(){
				if(buyflag == 1){
					return;
				}
				layer.confirm("确认购买此分类的全部课程？",{icon:3,title:'确认购买',offset:['20%','40%']},function(index){
					layer.close(index);
					handle.buyCourses(ctid);
				});
			});
			$("#practices_buy").on("click",function(){
				if(buyflag == 1){
					return;
				}
				layer.confirm("确认购买此分类的全部练习？",{icon:3,title:'确认购买',offset:['20%','40%']},function(index){
					layer.close(index);
					handle.buyPractices(ctid);
				});
			});
			ctid = $("#ctid").val();
			this.getPractices(1);
		},
		getPractices:function(page){
			if(queryflag == 1){
				return;
			}
			if(ctid == undefined || ctid <= 0){
				return;
			}
			if(page <= 0){
				return;
			}
			queryflag = 1;
			$.ajax({
				type:'post',
				dataType:'json',
				data:{ctid:ctid,page:page},
				url:getHost()+'/service/getPractices.html',
				success:function(data){
					if(data.ret == 1){
						var html = "";
						html += "<p class='cap'><span>练习工程</span>";
						html += "<span class='s2'>难度系数</span>";
						html += "<span>批改币</span></p>";
						html += "<div class='r_con_list clearfix'>";
						if(data.practices.length == 0){
							html += "<div class='pra-no-data'>暂无练习</div>";
						}else{
							currentpage = data.page;
							html += "<ul>";
							var practices = data.practices;
							for(var i in practices){
								html += "<li class='clearfix'>";
								html += "<span class='left name clearfix'><a target='_blank' href='/practice/detail.html?ptid="+practices[i].pt_id+"'>"+practices[i].name+"</a></span>";
								html += "<span class='left star clearfix'>";
								var stars = 0;
								var grayStars = 0;
								var halfStars = 0;
								if(practices[i].difficulty >= 10){
									stars = 5;
									halfStars = 0;
									grayStars = 0;
								}else if(practices[i].difficulty <= 0){
									stars = 0;
									halfStars = 0;
									grayStars = 5;
								}else{
									var difficulty =  parseInt(practices[i].difficulty);
									stars = parseInt(difficulty/2);
									if(difficulty%2 == 0){
										halfStars = 0;
									}else{
										halfStars = 1;
									}
									grayStars = 5 - stars - halfStars;
								}
								for(var j=0;j<stars;j++){
									html += "<img src='/images/star_light.png' />";
								}
								if(halfStars == 1){
									html += "<img src='/images/star_half.png' />";
								}
								for(var j=0;j<grayStars;j++){
									html += "<img src='/images/star_gray.png' />";
								}
								html += "</span><span class='left num'>"+practices[i].marking_fee+"</span></li>";
							}
							html += "</ul>"
							html += "<div class='pra_page'><ul>";
							html += "<li><a class='previous "+(data.previous?'act':'')+"' href='javascript:void(0);'>上一页</a></li>";
							html += "<li>"+data.page+"</li>";
							html += "<li><a class='next "+(data.next?'act':'')+"' href='javascript:void(0);'>下一页</a></li>";
							html += "</ul></div>";
						}
						html += "</div>";
						$(".r_con").html(html);
					}
				},complete:function(){
					queryflag = 0;
				}
			});
		},
		buyCourses:function(ctid){
			if(buyflag == 1){
				return;
			}
			if(ctid == undefined || ctid <= 0){
				return;
			}
			buyflag = 1;
			var index = layer.load(2);
			$.ajax({
				type:'post',
				dataType:'json',
				data:{ctid:ctid},
				url:getHost()+"/service/buyCoursesFullSet.html",
				success:function(data){
					if(data.ret == 1){
						layer.alert("购买成功",{icon:1,title:'成功',offset:['20%','40%']},function(index){
							layer.close(index);
							window.location.reload();
						});
					}else{
						layer.alert(data.msg,{icon:2,title:'错误',offset:['20%','40%']});
					}	
				},error:function(){
					layer.alert("抱歉，服务器错误",{icon:2,title:'错误',offset:['20%','40%']});
				},complete:function(){
					layer.close(index);
					buyflag = 0;
				}
			});
		},
		buyPractices:function(ctid){
			if(buyflag == 1){
				return;
			}
			if(ctid == undefined || ctid <= 0){
				return;
			}
			buyflag = 1;
			var index = layer.load(2);
			$.ajax({
				type:'post',
				dataType:'json',
				data:{ctid:ctid},
				url:getHost()+"/service/buyPracticesFullSet.html",
				success:function(data){
					if(data.ret == 1){
						layer.alert("购买成功",{icon:1,title:'成功',offset:['20%','40%']},function(index){
							layer.close(index);
							window.location.reload();
						});
					}else{
						layer.alert(data.msg,{icon:2,title:'错误',offset:['20%','40%']});
					}	
				},error:function(){
					layer.alert("抱歉，服务器错误",{icon:2,title:'错误',offset:['20%','40%']});
				},complete:function(){
					layer.close(index);
					buyflag = 0;
				}
			});
		}
	};
	(function(){
		handle.init();
	})();
});