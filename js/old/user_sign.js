define(function(require){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	require('birthday')($);
	require('calendar');
	var questflag = 0;
	
	var handle = {
		init:function(){
			$.ms_DatePicker({
				YearSelector: ".sel_year",
				MonthSelector: ".sel_month",
				DaySelector: ".sel_day"
			});
			$.ms_DatePicker();
			var mon_prev = calUtil.showMonth;
			var mon_next = calUtil.showMonth;
			//ajax获取日历json数据
			// var signList=[{"signDay":"2"},{"signDay":""},{"signDay":""},{"signDay":""}];
			var signList=[];
			var d = "";
			calUtil.init(signList);
			 
			console.log(signdays);
			$.each(signdays,function(key,value){ 
				$("#"+value).addClass("on");
			})
		    $("#js-just-qiandao").on("click",function(){
				var status = $(this).attr("data-status");
				if(status == "0")
				{
				    handle.sign();
					var date=new Date();
					var da=date.getDate(); //获取当前日
					var year=date.getFullYear();
					var month=date.getMonth()+1;
					
					  d=da;
					signList.push({"signDay":d})
					calUtil.init(signList);
					//$(this).text("已签到，累计签到19天");
					$(this).css("background","#77c8a9");
					var len = $(".sign .on").length;
					$(".day1").text(len);
				}
				
			
			});


			// tab
			$(".tab_ul li").on("click",function(){
				var index_ = $(this).index();
				$(this).addClass("act").siblings().removeClass("act");
				$(".time>div").eq(index_).show().siblings().hide();
			}); 

			
		},
		sign:function(){
			if(questflag == 1){
				return;
			}
			questflag = 1;
			$("#js-just-qiandao").text("签到中...");
			$.ajax({
				
				type:"post",
				dataType:"json",
				url:getHost()+"/service/signServ.html",
				success:function(data){
					if(data.ret == 1){
						alert("签到成功");
						$("#js-just-qiandao").text("已签到").addClass("signed");
					}else{
						handle.showErr(data.msg);
						$("#js-just-qiandao").text("点击签到");
					}
				},
				error:function(){
					handle.showErr("抱歉，服务器出错");
					$("#js-just-qiandao").text("点击签到");
				},
				complete:function(){
					questflag = 0;
				}
			});
		},
		showErr:function(msg){
			layer.confirm(msg, {
				title:"错误提示",
				btn: ['确认'], //按钮
				offset:['20%','40%']
			});
			return false;
		}
	};
	(function(){
		handle.init();
	})();
});