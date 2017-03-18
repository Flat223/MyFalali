define(function(require){
	var $ = require('jquery');
	require('common');
	
	var handle = {
		init:function(){
			$("#order_pay").on("click",function(){
				var ordercode = $("#ordercode").val();
				var payType = $("#paytype").val();
				if(ordercode == undefined || ordercode == ""){
					return;
				}
				window.location.href = getHost()+"/user/pay.html?ordercode="+ordercode+"&payType="+payType;
			});
		}
	};
	
	;(function(){
		handle.init();
	})();

});