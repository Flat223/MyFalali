layui.define('form',function(exports){
	var form = layui.form();
	var handle = {
		initDateSelection:function(year,month,day,fromyear,endyear){
			var yearHtml = "<option>选择年份</option>";
			for(var i=fromyear;i<=endyear;i++){
				yearHtml += "<option value='"+i+"'>"+i+"</option>";
			}
			$(year).html(yearHtml);
			var monthHtml = "<option>选择月份</option>";
			$(month).html(monthHtml);
			var dayHtml = "<option>选择日期</option>";
			$(day).html(dayHtml);
			$(year).attr("lay-filter","year");
			$(month).attr("lay-filter","month");
			$(day).attr("lay-filter","day");
			form.render('select');
			var bigMonths = ['1','3','5','7','8','10','12'];
			form.on("select(year)",function(){
				var val_y = $(year).val();
				if(isNaN(val_y)){
					$(month).html("<option>选择月份</option>").find("option:eq(0)").prop("selected",true);
					$(day).html("<option>选择日期</option>").find("option:eq(0)").prop("selected",true);
					form.render('select');
					return;
				}
				var val_m = $(month).val();
				var html = "<option>选择月份</option>";
				for(var i=1;i<=12;i++){
					html += "<option value='"+i+"'>"+i+"</option>";
				}
				$(month).html(html);
				if(isNaN(val_m)){
					form.render('select');
					return;
				}
				$(month).val(val_m);
				var val_d = $(day).val();
				var maxDays = 0;
				if($.inArray(val_m,bigMonths) >= 0){
					maxDays = 31;
				}else if(val_m == '2'){
					if((val_y%4==0 && val_y%100!=0) || val_y%400==0){
						maxDays = 29;
					}else{
						maxDays = 28;
					}
				}else{
					maxDays = 30;
				}
				var html2 = "<option>选择日期</option>";
				for(var i=1;i<=maxDays;i++){
					html2 += "<option value='"+i+"'>"+i+"</option>";
				}
				$(day).html(html2);
				if(isNaN(val_d)){
					$(day).find("option:eq(0)").prop("selected",true);
				}else{
					val_d = parseInt(val_d);
					if(val_d > maxDays){
						val_d = maxDays;
					}
					$(day).val(val_d);
				}
				form.render('select');
			});
			form.on("select(month)",function(){
				var val_m = $(month).val();
				if(isNaN(val_m)){
					$(day).html("<option>选择日期</option>").find("option:eq(0)").prop("selected",true);
					form.render('select');
					return;
				}
				var val_y = $(year).val();
				if(isNaN(val_y)){
					return;
				}
				var val_d = $(day).val();
				var maxDays = 0;
				if($.inArray(val_m,bigMonths) >= 0){
					maxDays = 31;
				}else if(val_m == '2'){
					if((val_y%4==0 && val_y%100!=0) || val_y%400==0){
						maxDays = 29;
					}else{
						maxDays = 28;
					}
				}else{
					maxDays = 30;
				}
				var html = "<option>选择日期</option>";
				for(var i=1;i<=maxDays;i++){
					html += "<option value='"+i+"'>"+i+"</option>";
				}
				$(day).html(html);
				if(isNaN(val_d)){
					$(day).find("option:eq(0)").prop("selected",true);
				}else{
					val_d = parseInt(val_d);
					if(val_d > maxDays){
						val_d = maxDays;
					}
					$(day).val(val_d);
				}
				form.render('select');
			});	
		}	
	};
	exports('layui_year_month_day',handle);
});