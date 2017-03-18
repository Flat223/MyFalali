define(function(require){
	var $ = require('jquery');
	require('birthday')($);

	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	
	var handle = {
		
		init:function(){
			$.ms_DatePicker({
				YearSelector: ".sel_year",
				MonthSelector: ".sel_month",
				DaySelector: ".sel_day"
			});
			$.ms_DatePicker();

			var submit = $("input[name=submit]");
			var mobile = $("input[name=mobile]");
			var nickname = $("input[name=nickname]");
			var name = $("input[name=name]");
			var sex = $("input[name=sex]");
			var birth_year = $("select[name=birth_year]");
			var birth_month = $("select[name=birth_month]");
			var birth_day = $("select[name=birth_day]");
			var university = $("input[name=university]");
			var intro = $("textarea[name=intro]");

			submit.click(function(){
			    var params = {};
				params.mobile = mobile.val();
				params.nickname = nickname.val();
				params.name = name.val(); 
				params.sex = $("input[name='sex']:checked").val();
				params.sex = (params.sex == "")?'3':Number(params.sex);
				params.birth_year = birth_year.val(); 
				params.birth_month = birth_month.val();
				params.birth_day = birth_day.val(); 
				params.university = university.val();
				params.intro = intro.val();
				var birthday = new Date(Date.UTC(params.birth_year,params.birth_month - 1,params.birth_day, 0, 0, 0));
				birthday = Math.round(birthday.getTime()/1000);
				params.birthday = birthday;
				console.log(params);
				submit.val("正在提交...");
				$.ajax({
					type:"post",
					dataType:"json",
					url:getHost()+"/service/updateuserServ.html",
					data:params,
					success:function(data){
						if(data.ret == 1){
							var alert = layer.confirm(data.msg, {
								title:"温馨提示",
								btn: ['确认','取消'] //按钮
							}, function(){
								location.reload(true);
							}, function(){
				
							}); 
						}else{
							var alert = layer.confirm(data.msg, {
								title:"温馨提示",
								btn: ['确认','取消'] //按钮
							}, function(){
								layer.close(alert);
							}, function(){
				
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
						submit.val("提交");
					}
				});

			})
		}
	};
	
	(function(){
		handle.init();
	})();	
});