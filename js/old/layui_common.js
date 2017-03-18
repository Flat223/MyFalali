layui.define(function(exports){
	
	var handle = {
		getHost:function(){
			var path = window.document.location.href;
			var pathName = window.document.location.pathname;
			if(pathName == "/"){
				if(path.indexOf("https://") == 0){
					return "https://" + document.domain;
				}
				return "http://" + document.domain;
			}
			var pos = path.indexOf(pathName);
			var host = path.substring(0, pos);
			return host;
		},
		isEmail:function(email){
			var emailRegExp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			return emailRegExp.test(email);
		},
		isMobile:function(mobile){
			var RegExp_Mobile = /^((\+?86)|(\(\+86\)))?(13[012356789][0-9]{8}|15[012356789][0-9]{8}|18[02356789][0-9]{8}|147[0-9]{8}|1349[0-9]{7})$/;
			return RegExp_Mobile.test(mobile);
		}
	}
	exports('layui_common',handle);
});