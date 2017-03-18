define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	require('cyupload.js');
	var imgurl="";
	var handle = {
		init:function(){
			var otype=0;
			otype=GetQueryString('otype');
			$("input[name='pay']").on("click",function(){
				var value=$(this).attr("value");
				if(value==3){
					$(".bank_ol_detail").show();
				}else{
					$(".bank_ol_detail").hide();
				}
			});
			
			$("#gotopay").click(function(){
				var type=$("input[name=pay]:checked").attr("value");
				if(type==3){
					if(imgurl==""){
						layer.alert("请上传线下支付凭证");
						return;
					}
					var order=GetQueryString('codes');
					window.location.href="/service/payServ.html?ordercode="+order+"&payType="+type+"&otype="+otype+"&url="+imgurl;
				}else if(type==1||type==2){
					var order=GetQueryString('codes');
					window.location.href="/service/payServ.html?ordercode="+order+"&payType="+type+"&otype="+otype; 			
				}else {
					layer.alert("请选择支付方式");
				}
			});

			//上传图片 待修改
			$.cyupload({
			    elem: '#voucher_add',
			    btnName: "请选择",  //按键名称
			    infoElementId: "", //上传状态信息包装元素id
			    maxFilesize: 10485760,
			    uploadUrl: '/service/uploadimgServ.html',
			    fileFilter: '',
			    upfileParam: 'upload_file_input',
			    success: function (url) { 
			   		$('#imgvoucher').attr('src', url['file_url']);
			   		 imgurl=url['file_url'];
			    }
			});

			$("#fakeclick").click(function(){
				$("#voucher_add").find(".upload_file_btn").trigger("click");
			})

			
		}	
	};	
	
	function GetQueryString(name){
	    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return  unescape(r[2]); return null;
	};

	
	$(function(){
		handle.init();
	});
});
