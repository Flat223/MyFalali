'use strict';
function getHost(){
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
}

function isEmail(email){
	var emailRegExp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return emailRegExp.test(email);
}

function isMobile(mobile){
	var RegExp_Mobile = /^((\+?86)|(\(\+86\)))?(13[012356789][0-9]{8}|15[012356789][0-9]{8}|18[02356789][0-9]{8}|147[0-9]{8}|1349[0-9]{7})$/;
	return RegExp_Mobile.test(mobile);
}
function back2Home() {
	var location = $ ("#url").html ();
	window.location = location;
}

//是否包含中文
function isChinese(str) {
	var pattern = /[\u4E00-\u9FA5]|[\uFE30-\uFFA0]/gi;
	return pattern.test (str);
}

//是否还有特殊字符
function isSpecialChar(str) {
	var reg = /[@#\$%\^&\*]+/g;
	return reg.test (str);
}

//验证长度
function validateLength(data, minLen, maxLen) {
	var len = data.length;
	return (len >= minLen && len <= maxLen);
}
