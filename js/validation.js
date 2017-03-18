define(function(require,exports,module){
	
	//验证整数
	exports.isInt = function(val){
		var pattern = /^-?\d+$/;
		return pattern.test(val);
	};
	
	//验证正整数
	exports.isPositiveInt = function(val){
		var pattern = /^[0-9]*[1-9][0-9]*$/;
		return pattern.test(val);
	}
	
	//验证负整数
	exports.isNegtiveInt = function(val){
		var pattern = /^-[0-9]*[1-9][0-9]*$/;
		return pattern.test(val);
	}
	
	//验证非负整数
	exports.isNonnegativeInt = function(val){
		var pattern = /^\d+$/;
		return pattern.test(val);
	}
	
	//验证非正整数
	exports.isNonPositiveInt = function(val){
		var pattern = /^((-\d+)|(0+))$/;
		return pattern.test(val);
	}
	
	//验证浮点数
	exports.isFloat = function(val){
		var pattern = /^(-?\d+)(\.\d+)?$/;
		return pattern.test(val);
	}
	
	//验证正浮点数
	exports.isPostiveFloat = function(val){
		var pattern = /^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/;
		return pattern.test(val);
	}
	
	//验证负浮点数
	exports.isNegativeFloat = function(val){
		var pattern = /^(-(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*)))$/;
		return pattern.test(val); 
	}
	
	//验证非负浮点数
	exports.isNonnegativeFloat = function(val){
		var pattern = /^\d+(\.\d+)?$/;
		return pattern.test(val);
	}
	
	//验证非正浮点数
	exports.isNonPositiveFloat = function(val){
		var pattern = /^((-\d+(\.\d+)?)|(0+(\.0+)?))$/;
		return pattern.test(val);
	}
	
	//验证邮箱
	exports.isEmail = function(val){
		var pattern = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/;
		return pattern.test(val);
	}
	
	//验证url
	exports.isUrl = function(val){
		var pattern = /^[a-zA-z]+:\/\/(\w+(-\w+)*)(\.(\w+(-\w+)*))*(\?\S*)?$/;
		return pattern.test(val);
	}
	
	//验证账号用（数字、英文字母或者下划线）
	exports.isAccount = function(val){
		var pattern = /^\w+$/;
		return pattern.test(val);
	}
	
	//验证手机
	exports.isMobile = function(val){
		var pattern = /^((\+?86)|(\(\+86\)))?(13[012356789][0-9]{8}|15[012356789][0-9]{8}|18[02356789][0-9]{8}|147[0-9]{8}|1349[0-9]{7})$/;
		return pattern.test(val);
	}
	
	
	
	
});