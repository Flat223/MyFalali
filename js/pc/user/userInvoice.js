define(function(require) {
	var $ = require('jquery');
	var layer;
	require('layui/layui.js');
	require('manager/cyupload.js');
	require('pc/user/repassword.js');
	require('pc/user/uploadidentity.js');
	if(window.layui){
		layui.config({
			dir: '/layui/'
		});
		layui.use(['layer', 'element'], function(){
			layer = layui.layer;
		});
	}

	$(".na-ul li").on("click",function(){
		$(this).addClass("na-li-this").siblings().removeClass("na-li-this");
		var type = $(this).attr('invoice_type');
		
		var params = {};
		params.type = type;
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/GetInvoiceListServ.html",
			data:params,
			success:function(data){
				if(data.ret == 0){
					$(".invoice_container").html("");
					$('.invoice_hint').text(data.msg);
					$('.invoice_hint').show();
				} else {
					$('.invoice_hint').text('');
					$('.invoice_hint').hide();
					var invoiceArray = data.invoice;	
					reloadHtml(invoiceArray);
				}
			},
			error:function(data){
				alert("服务器错误,请稍后再试");
			},
			complete:function(){
				
			}
		});	
	});
	
	$('.delete').on('click',function(){
		var params = {};
		var rid = $(this).attr('rid');
		params.rid = rid;
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/deleteInvoiceServ.html",
			data:params,
			success:function(data){
				if(data.ret == 0){
// 					alert(data.msg);
				} else {
					window.location.href = "/user/account.html?type=3";
				}
			},
			error:function(data){
				alert("服务器错误,请稍后再试");
			},
			complete:function(){
				
			}
		});
	});
	
	
	function reloadHtml(invoiceArray){
		var str = "";
		for(var i=0;i<invoiceArray.length;i++){
			var invoice = invoiceArray[i];
			str = str + "<tr>\
						<td>" + invoice.invoice_code + "</td>\
						<td>" + invoice.title + "</td>\
		    			<td></td>\
		    			<td></td>\
		    			<td></td>\
		    			<td class=\'delete\'>删除</td>\
	    			</tr>";
		}
		$(".invoice_container").html(str);
	}

	var tab = 1; //1: 验证码 2：密码;

	$("#radio_login_psw").on("click",function(){
		if(tab == 2) {
			return;
		}

		document.getElementById("input_checkCode").style.display = "none";
		document.getElementById("input_checkCode1").style.display = "none";
		$("#input_psd").show();
		tab = 2;
	});
	$("#radio_login_code").on("click",function(){
		if(tab == 1) {
			return;
		}

		document.getElementById("input_psd").style.display = "none";
		$("#input_checkCode").show();
		$("#input_checkCode1").show();
		tab = 1;
	});

	var type="<?php echo isset($_GET['type'])?$_GET['type']:''; ?>";
	switch(type){
		case '1':
			$('.layui-tab-title li:nth-of-type(1)').addClass("layui-this");
			$('.layui-tab-content div:nth-of-type(1)').addClass("layui-show");
			break;
		case '2':
			$('.layui-tab-title li:nth-of-type(2)').addClass("layui-this");
			$('.layui-tab-content div:nth-of-type(2)').addClass("layui-show");
			break;
		case '3':
			$('.layui-tab-title li:nth-of-type(3)').addClass("layui-this");
			$('.layui-tab-content div:nth-of-type(3)').addClass("layui-show");
			break;
		default:
			$('.layui-tab-title li:nth-of-type(1)').addClass("layui-this");
			$('.layui-tab-content div:nth-of-type(1)').addClass("layui-show");
			break;
	}

	layui.use(['layer', 'element'], function(){

	});

	$.cyupload({
		elem: '#images',
		btnName: "请选择",		//按键名称
		infoElementId: "",	//上传状态信息包装元素id
		maxFilesize: 10485760,
		uploadUrl: '/service/uploadimgServ.html',
		fileFilter: '',
		upfileParam: 'upload_file_input',
		success: function (url) {
			$('#images').attr('src', url['file_url']);
		}
	});

	$("li[name=repassword]").on("click",function(){
		$("li[name=titletip]").text("修改密码");
	})
	$("li[name=identity]").on("click",function(){
		$("li[name=titletip]").text("实名认证");
	})
	$("li[name=myinvoice]").on("click",function(){
		$("li[name=titletip]").text("我的发票");
	})
});