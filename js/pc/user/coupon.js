define(function(require) {
	var $ = require('jquery');
	var layer;
	require('layui/layui.js');
	if(window.layui){
		layui.config({
			dir: '/layui/'
		});
		layui.use(['layer', 'element'], function(){
			layer = layui.layer;
		});
	}

	var couponArray  = "";
	var type = 1;
	$(".na-ul li").on("click",function(){
		$(this).addClass("na-li-this").siblings().removeClass("na-li-this");
		type = $(this).parent().attr('coupon_type');
		var use_status = $(this).attr('use_status');
		
		var params = {};
		params.type = type;
		params.use_status = use_status;	

		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/getCouponListServ.html",
			data:params,
			success:function(data){
				if(data.ret == 0){
					if(type == 1) {
						$(".coupon_container").html("");
						$('.coupon_hint').text(data.msg);
						$('.coupon_hint').show();
					} else {
						$(".crash_container").html("");
						$('.crash_hint').text(data.msg);
						$('.crash_hint').show();
					}
				} else if (data.ret == -1){
					layer.alert(data.msg,{offset:'200px'});
				} else {
					couponArray = data.coupon;	
					reloadHtml();
				}
			},
			error:function(data){
				layer.alert("服务器错误,请稍后再试",{offset:'200px'});
			},
			complete:function(){
				
			}
		});		
	});
	
	$('.apply').on('click',function(){	
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/applyResearchFundServ.html",
			success:function(data){
				if(data.ret == 1){
					layer.alert(data.msg,{offset:'200px'},function(){
						location.reload(true);	
					});
				} else {
					layer.alert(data.msg,{offset:'200px'});
				}
			},
			error:function(data){
				layer.alert("服务器错误,请稍后再试",{offset:'200px'});
			},
			complete:function(){
				
			}
		});	
	});
	
	function reloadHtml(){
		var str = "";
		for(var i=0;i<couponArray.length;i++){
			var coupon = couponArray[i];
			str = str + "<tr>\
						<td>" + coupon.number + "</td>\
						<td>" + coupon.name + "</td>\
		    			<td>" + coupon.money + "</td>\
		    			<td>" + coupon.validity + "</td>\
		    			<td>" + coupon.intro + "</td>\
	    			</tr>";
		}
		
		if (type == 1) {
			$(".coupon_container").html(str);
			$('.coupon_hint').hide();
		} else {
			$(".crash_container").html(str);
			$('.crash_hint').hide();
		}
	}
});



