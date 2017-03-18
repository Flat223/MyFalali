define(function(require){
	var $ = require('jquery');
	require('common');
	var time = 60;
	var querytime = 100;
	var ordercode;
	
	var handle = {
		init:function(){
			ordercode = $("#ordercode").val();
			this.execTime();
			this.checkPay();
		},
		execTime:function(){
			setTimeout(function(){
				if(time > 0){
					time--;
					$("#sec_count").text(time);
					handle.execTime();
				}else{
					handle.setRefresh();
				}
			}, 1000);	
		},
		setRefresh:function(){
			var html = "二维码已过期，点击<a href='javascript:void(0);' id='refresh' style='color:#0071cf;' >刷新二维码</a>重新获取";
			$("#setRefresh").html(html);
			$("#refresh").on("click",function(){
				location.reload();
			});
		},
		checkPay:function(){
			if(querytime <= 0){
				return;
			}
			if(ordercode == undefined){
				return;
			}
			querytime--;
			setTimeout(function(){
				$.ajax({
					type:"post",
					dataType:"json",
					data:{ordercode:ordercode},
					url:getHost()+"/service/getOrderSt.html",
					success:function(data){
						var dataobj = eval(data);
						if(dataobj.ret == 1){
							handle.checkPay();
						}else if(dataobj.ret == 2){
							var form = "<form action='"+getHost()+"/user/paysuc.html' method='post' id='commit' >";
							form += "<input type='hidden' name='ordercode' value='"+ordercode+"'/>";
							form += "</form>";
							$("body").append(form);
							$("#commit").submit();
						}
					}
				});				
			}, 2000);
		}
	};
	
	;(function(){
		handle.init();
	})();

});