define(function(require){
	var $ = require('jquery');
	require('common');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	var virtualExchanges = vce;
	var types = ['支付宝','微信'];
	
	var handle = {
		init:function(){
			$("input[type=radio][name=money]:eq(0)").prop("checked",true);
			$("#pay").on("click",function(){
				if(virtualExchanges == undefined){
					return;
				}
				var paymoneyid = $("input:radio[name=money]:checked").val();
				var pay_type = $("input:radio[name=pay_type]:checked").val();
				var coin = "";
				var money = "";
				for(var i in virtualExchanges){
					if(virtualExchanges[i].id == paymoneyid){
						coin = virtualExchanges[i].virtual_currency;
						money = virtualExchanges[i].money;
						break;
					}
				}
				if(coin == "" || money == ""){
					return;
				}
				var type = types[parseInt(pay_type)-1];
				layer.confirm('充值金币：'+coin+"<br/>充值金额："+parseFloat(money)+"<br/>充值方式："+type, {
					title:"温馨提示",
					btn: ['确认','取消'], //按钮
					offset:['220px','40%']
				}, function(index){
					layer.close(index);
					handle.pay(paymoneyid,pay_type);
				});
			});
		},
		pay:function(pay_money_type,pay_type){
			window.location.href = getHost()+"/user/recharge.html?rechargeType=1"+"&payType="+pay_type+"&rechargeOption="+pay_money_type;
		}
	};
	
	;(function(){
		handle.init();
	})();
});
