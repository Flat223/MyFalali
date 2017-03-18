define(function(require,exports,module){
	var $ = require('jquery');
	var cssLoader = require("cssLoader");
	cssLoader.loadCss("/css/pc/dialog.css");
	var cover = "<div class='dialog-cover'></div>";
	
	return {
		info:function(params){
			var dialog = "<div class='dialog'>";
			dialog += "<div class='dialog-title'><span>"+((typeof(params.title)==='string'&&params.title!='')?params.title:'提示')+"</span></div>";
			dialog += "<div class='dialog-content'>";
			dialog += "<div class='dialog-tip'><span class='dialog-icon-info'></span>";
			dialog += "<span class='dialog-msg'>"+((typeof(params.msg)==='string'&&params.msg!='')?params.msg:'')+"</span></div>";
			dialog += "<div style='margin-top:10px;text-align:center;'>";
			dialog += "<a href='javascript:void(0);' class='dialog-button' >"+((typeof(params.buttonlabel)==='string'&&params.buttonlabel!='')?params.buttonlabel:'确定')+"</a></div></div>";
			dialog += "<a title='关闭' class='dialog-close' href='javascript:void(0);'><span></span></a></div>";
			$("body").append(cover).append(dialog);
			$(".dialog-button,.dialog-close").on("click",function(){
				$(".dialog-cover,.dialog").remove();
			});
		},
		confirm:function(params){
			var dialog = "<div class='dialog'>";
			dialog += "<div class='dialog-title'><span>"+((typeof(params.title)==='string'&&params.title!='')?params.title:'确认')+"</span></div>";
			dialog += "<div class='dialog-content'>";
			dialog += "<div class='dialog-tip'><span class='dialog-icon-info'></span>";
			dialog += "<span class='dialog-msg'>"+((typeof(params.msg)==='string'&&params.msg!='')?params.msg:'')+"</span></div>";
			dialog += "<div style='margin-top:10px;text-align:center;'>";
			dialog += "<a href='javascript:void(0);' class='dialog-button dialog-ok'>"+((typeof(params.confirmLabel)==='string'&&params.confirmLabel!='')?params.confirmLabel:'确定')+"</a>";
			if(params.needCancelBtn === true){
				dialog += "<a href='javascript:void(0);' class='dialog-button dialog-cancel'>"+((typeof(params.cancellabel)==='string'&&params.cancellabel!='')?params.cancellabel:'取消')+"</a>";
			}
			dialog += "</div></div>";
			dialog += "<a title='关闭' class='dialog-close' href='javascript:void(0);'><span></span></a></div>";
			$("body").append(cover).append(dialog);
			$(".dialog-cancel,.dialog-close").on("click",function(){
				$(".dialog-cover,.dialog").remove();
				if(typeof(params.cancelCallback) === 'function'){
					params.cancelCallback();
				}
			}); 
			$(".dialog-ok").on("click",function(){
				$(".dialog-cover,.dialog").remove();
				if(typeof(params.confirmCallback) === 'function'){
					params.confirmCallback();
				}
			});
		},
		closeConfirm:function(){
			$(".dialog-cover,.dialog").remove();
		},
		progress:function(params){
			var dialog = "<div class='dialog'>";
			dialog += "<div class='dialog-title'><span>"+((typeof(params.title)==='string'&&params.title!='')?params.title:'操作中')+"</span></div>";
			dialog += "<div class='dialog-content'>";
			dialog += "<div class='dialog-tip'><span class='dialog-icon-info' style='display:none;'></span>";
			dialog += "<span class='dialog-msg' style='margin-top:40px;font-size:21px;margin-left:90px;'>"+((typeof(params.msg)==='string'&&params.msg!='')?params.msg:'')+"</span></div>";
			dialog += "</div>";
			dialog += "<a title='关闭' class='dialog-close' style='display:none;' href='javascript:void(0);'><span></span></a></div>";
			$("body").append(cover).append(dialog);
		},
		clearProgress:function(){
			$(".dialog-cover").remove();
			$(".dialog").fadeOut(500,function(){
				$(this).remove();
			});
// 			$(".dialog").remove();
		},
		changeProgress:function(params){
			if(typeof(params.msg) === 'string' && params.msg != ''){
				$(".dialog-msg").text(params.msg).removeAttr("style");
			}
			if(result === "failed"){
				$(".dialog-icon-info").css({"background-position":"-46px 0","display":"block"});
				var html = "<div style='margin-top:10px;text-align:center;'><a href='javascript:void(0);' class='dialog-button'>确定</a></div>";
				$(".dialog-content").append(html);
				$(".dialog-close").show();
				$(".dialog-button,.dialog-close").on("click",function(){
					$(".dialog-cover,.dialog").remove();
				});
			}else{
				if(params.result === "success"){
					$(".dialog-icon-info").css({"background-position":"0 0","display":"block"});
				}
				var time = 0;
				if(typeof(params.delay) === 'number' && params.delay > 0){
					time = params.delay;
				}
				setTimeout(function(){
					$(".dialog-cover,.dialog").remove();
				}, time);
			}
		},
		error:function(params){
			var dialog = "<div class='dialog'>";
			dialog += "<div class='dialog-title'><span>"+((typeof(params.title)==='string'&&params.title!='')?params.title:'提示')+"</span></div>";
			dialog += "<div class='dialog-content'>";
			dialog += "<div class='dialog-tip'><span class='dialog-icon-info' style='background-position:-46px 0;' ></span>";
			dialog += "<span class='dialog-msg'>"+((typeof(params.msg)==='string'&&params.msg!='')?params.msg:'')+"</span></div>";
			dialog += "<div style='margin-top:10px;text-align:center;'>";
			dialog += "<a href='javascript:void(0);' class='dialog-button' >"+((typeof(params.buttonlabel)==='string'&&params.buttonlabel!='')?params.buttonlabel:'确定')+"</a></div></div>";
			dialog += "<a title='关闭' class='dialog-close' href='javascript:void(0);'><span></span></a></div>";
			$("body").append(cover).append(dialog);
			$(".dialog-button,.dialog-close").on("click",function(){
				$(".dialog-cover,.dialog").remove();
			});
		},
		toast:function(params){
			var dialog = "<div class='dialog'>";
			dialog += "<div class='dialog-title'><span>"+((typeof(params.title)==='string'&&params.title!='')?params.title:'提示')+"</span></div>";
			dialog += "<div class='dialog-content'>";
			dialog += "<div class='dialog-tip'><span class='dialog-icon-info' style='display:none;'></span>";
			dialog += "<span class='dialog-msg' style='margin-top:40px;font-size:21px;'>"+((typeof(params.msg)==='string'&&params.msg!='')?params.msg:'')+"</span></div>";
			dialog += "</div>";
			dialog += "<a title='关闭' class='dialog-close' style='display:none;' href='javascript:void(0);'><span></span></a></div>";
			$("body").append(cover).append(dialog);
			var time = 100;
			if(typeof(params.delay) === 'number' && params.delay > 0){
				time = params.delay;
			}
			setTimeout(function(){
				$(".dialog-cover,.dialog").remove();
			}, time);
		},
		defineContent:function(params){
			var dialog = "<div class='dialog'>";
			dialog += "<div class='dialog-title'><span>"+((typeof(params.title)==='string'&&params.title!='')?params.title:'提示')+"</span></div>";
			dialog += "<div class='dialog-content'>";
			dialog += "<div class='dialog-tip'>";
			dialog += "<span class='dialog-msg' style='margin-left:0;width:100%;text-align:center;' >"+((typeof(params.msg)==='string'&&params.msg!='')?params.msg:'')+"</span></div>";
			dialog += "<div style='text-align:center;'>";
			dialog += (typeof(params.button)==='string'&&params.button!='')?params.button:'';
			dialog += "</div></div>";
			dialog += "<a title='关闭' class='dialog-close' href='javascript:void(0);'><span></span></a></div>";
			$("body").append(cover).append(dialog);
			$(".dialog-close").on("click",function(){
				$(".dialog-cover,.dialog").remove();
			});
		},
		removeDefineContent:function(){
			$(".dialog-cover,.dialog").remove();
		}
		
	};

});