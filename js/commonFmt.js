define(function(require,exports,module){
	
	var validation = require("validation");
	
	//保留小数位，四舍五入
	//num:输入数字,prec:精度
	exports.toDecimal = function(num,prec){
		if(!validation.isFloat(num)){
			return NaN;
		}
		if(prec == undefined || !validation.isInt(prec)){
			prec = 0;
		}
		prec = parseInt(prec);
		if(prec < 0){
			prec = 0;
		}
		var val = parseFloat(num);
		if(prec == 0){
			return Math.round(val);
		}
		if(prec > 10){
			prec = 10;
		}
		var f = 1;
		for(var i=0;i<prec;i++){
			f = f*10;
		}
		return Math.round(val*f)/f;
	};
	
	//格式化数字，四舍五入
	//num:输入数字,prec:精度
	exports.formatNum = function(num,prec){
		var val = parseFloat(num);
		if(isNaN(val)){
			return null;
		}
		if(prec == undefined || !validation.isInt(prec)){
			prec = 0;
		}
		prec = parseInt(prec);
		if(prec < 0){
			prec = 0;
		}
		if(prec == 0){
			return Math.round(val).toString();
		}
		if(prec > 10){
			prec = 10;
		}
		var f = 1;
		for(var i=0;i<prec;i++){
			f = f*10;
		}
		var val2 = (Math.round(val*f)/f).toString();
		var rs = val2.indexOf('.');
		if(rs < 0){
			rs = val2.length;
			val2 += '.';
		}
		var len = rs + prec + 1 - val2.length;
		var i = 0;
		while(i < len){
			val2 += '0';
			i++;
		}
		return val2;		
	};
	
	//右补齐数字0，超出不补，为原数字
	//num:输入数字,prec:精度
	exports.completeNum = function(num,prec){
		var val = parseFloat(num);
		if(isNaN(val)){
			return null;
		}
		if(prec == undefined || !validation.isInt(prec)){
			prec = 0;
		}
		prec = parseInt(prec);
		if(prec < 0){
			prec = 0;
		}
		var val2 = val.toString();
		if(prec == 0){
			return val2;
		}
		var rs = val2.indexOf('.');
		if(rs > 0){
			var zeroNum =  rs + 1 + prec - val2.length;
			if(zeroNum <= 0){
				return val2;
			}
			var i = 0;
			while(i < zeroNum){
				val2 += '0';
				i++;
			}
			return val2;
		}
		val2 += '.';
		var i = 0;
		while(i < prec){
			val2 += '0';
			i++;
		}
		return val2;	
	};
	
	exports.formatDate = function(time,format){
		if(typeof Date.prototype.pattern !== "function"){
			Date.prototype.pattern = function(fmt){
				var o = {
					"M+" : this.getMonth() + 1,
					"d+" : this.getDate(),
					"h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12,
					"H+" : this.getHours(),
					"m+" : this.getMinutes(),
					"s+" : this.getSeconds(),
					"q+" : Math.floor((this.getMonth()+3)/3),	//季度
					"S"  : this.getMilliseconds()					
				};
				var week = {
					"0" : "\u65e5",
					"1" : "\u4e00",
					"2" : "\u4e8c",
					"3" : "\u4e09",
					"4" : "\u56db",
					"5" : "\u4e94",
					"6" : "\u516d"
				};
				if(/(y+)/.test(fmt)){
					fmt = fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4-RegExp.$1.length));
				}
				if(/(E+)/.test(fmt)){
					fmt = fmt.replace(RegExp.$1, ((RegExp.$1.length>1)?(RegExp.$1.length>2? "\u661f\u671f" : "\u5468") : "")+week[this.getDay()+""]);
				}
				for(var k in o){
					if(new RegExp("("+k+")").test(fmt)){
						fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1)?(o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
					}
				}
				return fmt;
			}
		}
		time = parseInt(time);
		if(isNaN(time) || time < 0){
			return "";
		}
		var date = new Date(time*1000);
		return date.pattern(format);	
	};
	
	
	
});