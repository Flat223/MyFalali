define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});

	$("#topbarqiandao").click(function () {
		layer.open({
			type: 2,
			title: '签到',
			shadeClose: true,
			shade: 0.8,
			area: ['380px', '30%'],
			content: '/sign/sign.html'
		});
	});

	$(".ideal").on('click',function () {
		layer.alert("请发邮箱至wtt@livsing.com,感谢您的支持！");
	});

	var handle = {
		init:function(){
			/*$("#topbarqiandao").calendars({
		        controlId: "divDate",                                 // 弹出的日期控件ID，默认: $(this).attr("id") + "Calendar"
		        speed: 200,                                           // 三种预定速度之一的字符串("slow", "normal", or "fast")或表示动画时长的毫秒数值(如：1000),默认：200
		        complement: true,                                     // 是否显示日期或年空白处的前后月的补充,默认：true
		        readonly: true,                                       // 目标对象是否设为只读，默认：true
		        upperLimit: new Date(),                               // 日期上限，默认：NaN(不限制)
		        lowerLimit: new Date("2016/01/01"),                   // 日期下限，默认：NaN(不限制)
		        callback: function () {								// 点击选择日期后的回调函数
					var signTime = $("#topbarqiandao").val();
					var date = new Date();
					var seperator1 = "-";
					var year = date.getFullYear();
					var month = date.getMonth() + 1;
					var strDate = date.getDate();
					if (month >= 1 && month <= 9) {
						month = "0" + month;
					}
					if (strDate >= 0 && strDate <= 9) {
						strDate = "0" + strDate;

					}
					var currentdate = year + seperator1 + month + seperator1 + strDate;
					if (signTime != currentdate) {
						layer.alert("只能在今天签到~",{offset:'200px'});
						return false;
					}
					/!*handle.getNowFormatDate(signTime);*!/
		            handle.sign();
		        }
		    });*/


		}

	};
	
	$(function(){
		handle.init();
	});
});