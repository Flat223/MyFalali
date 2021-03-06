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
			
			$("input[name=submit]").on("click",function(){
				var aid = $(this).attr("aid");		
				var category = $("select[name=category] option:selected");
				var id = category.val();
					if(typeof(id) == "undefined"){
						id = -1;
					}
				// var s_province = $("select[name=s_province]");
				// var s_city = $("select[name=s_city]");
				// var s_county = $("select[name=s_county]");
				var images=$("#images")[0].src;
				var title = $("input[name=title]");
				var intro = $("textarea[name=intro]");
				var video_url = $('#proVideo').val();
				var content =ue.getContent();
				if (title == ""){
					layer.alert('请填写标题',{offset:'200px'});
					return ;
				} else if (content == ""){
					layer.alert('请填写文章内容',{offset:'200px'});
					return ;
				}
				var type = $(this).attr('handleType');
				var params = {};
				params.categoryId = id;
				// params.province = s_province.val();
				// params.city = s_city.val();
				// params.country = s_county.val();
				params.images=images;
				params.title = title.val();
				params.intro = intro.val();
				params.content = content;
				params.video = video_url;
				 
				handle.operateArticle(type,params,aid);
			});

			$("#sc").bind("change",function(){
				var id = $(this).val();
				$.ajax({
					type: "post",
					dataType: "json",
					url: "/service/GetArticleTypeServ.html",
					data: {"id":id},
					success: function (data) {
						$("#cc").empty();
						$("#cc").hide();
						if(id != -1){
							$("#cc").show();
							for (var i = 0; i<data.length;i++){
								var str = "<option value='"+data[i].id+"'>"+data[i].name+"</option>";
								$("#cc").append(str);
							}
						}
					},
					error:function (msg) {
						alert(msg);
					}
				});
			});
		},
			
		operateArticle:function(type,params,aid){
			var servUrl = "";
			if (type == 1) {
				servUrl = "/service/publishArticleServ.html";
			} else {
				servUrl = "/service/updateArticleServ.html";	
				params.aid = aid;
				params.type = 1;		
			}				
			$.ajax({
				type:"post",
				dataType:"json",
				url:servUrl,
				data:params,
				success:function(data){
					layer.alert(data.msg,{offset:'200px'},function(){
						location.href = "/user/article.html";
					});
				},
				error:function(data){
					layer.alert("服务器错误,请稍后再试",{offset:'200px'});
				},
				complete:function(){
					
				}
			})
		}
	};
	
	$(function(){
		handle.init();
	});
});
