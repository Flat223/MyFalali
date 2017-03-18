define(function(require,exports,module){
	require("common");
	
	return {
		loadCss:function(url){
			var newUrl = getHost() + url;
			var links = document.getElementsByTagName("link");
			for(var i=0;i<links.length;i++){
				if(links[i].href.indexOf(newUrl) > -1){
					return;
				}
			}
			var link = document.createElement("link");
			link.type = "text/css";
			link.rel = "stylesheet";
			link.href = newUrl;
			var head = document.getElementsByTagName("head")[0];
			head.insertBefore(link, head.getElementsByTagName("link")[0] || null);
		}	
	}
	
});