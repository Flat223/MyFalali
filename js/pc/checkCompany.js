define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	require('cyupload.js');

    var handle = {
        init:function(){			
			$.cyupload({
				elem: '#uploadimg',
				btnName: "请选择",		//按键名称
				infoElementId: "",	//上传状态信息包装元素id
				maxFilesize: 10485760,
				uploadUrl: '/service/uploadimgServ.html',
				fileFilter: '',
				upfileParam: 'upload_file_input',
				success: function (url) {
					$('#faceimage').attr('src', url['file_url']);
				}
			}); 
			$("#faceimage").click(function(){
				$("#uploadimg").find(".upload_file_btn").trigger("click");
			})
			
			$('#confirmUpload').on('click',function(){
				var name = $.trim($('#company_name').val());
// 				var com_type = $.trim($('#company_type').val());
				var image = $("#faceimage").attr('src');
				if(name == ""){
					layer.alert('请填写公司名称',{offset:'200px'});
					return;
				}
/*
				if(com_type == ""){
					layer.alert('请填写公司类型',{offset:'200px'});
					return;
				}
*/
				if(image == "http://d27.ichuk.com/images/pc/upload.jpg"){
					layer.alert('请上传营业执照',{offset:'200px'});
					return;
				}
				
				var mid = $('input[name=mid]').val();
				var check = $('input[name=check]').val();
				var params = {};
				params.mid = mid;
				params.name = name;
				params.type = check;
// 				params.com_type = com_type;
				params.image = image;
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/uploadComInfoOnRegist.html",
					data:params,
					success:function(data){
						if(data.ret == 1){
							layer.alert(data.msg,{offset:'200px'},function(){
								window.location.href="/regist/stepSuc.html?wait=1";
							});
						} else {
							layer.alert(data.msg);
						}
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					},
					complete:function(){
						
					}
				});
			});
        }
    };

    $(function(){
        handle.init();
    });
});