define(function(require){
	var $ = require('jquery');
	require('common');
	
	var handle = {
		init:function(){
			$("#order_pay").on("click",function(){
				var ordercode = $("#ordercode").val();
				var payType = $("input:radio[name=pay_type]:checked").val();
				if(ordercode == undefined || ordercode == "" || payType == undefined){
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