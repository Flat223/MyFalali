define(function(require){
	var $ = require('jquery');
	require('ichuk');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
		
	var handle = {
		init:function(){
             var input_photoalbum = "photoalbum";
			 var iChuk = iChukCore.Inital();
			 
			$(function(){ 
				iChuk.UploadImage('#_file_upload',data,function(url){
					var filename = url.split("/");
					$("#_file").text("已上传"); 
					$("input[name=fileurl]").val(url);
					 
				},"http://admin.luqiwang.com/Qiniu/upload.html");
				
				$('#_file').click(function(){
					$('#_file_upload').trigger('click');
				}) 
			})
			 $(function(){
				$("input[name=submit]").click(function(){
				    var photoalbum = $('input[name='+input_photoalbum+']').val();
					var title = $("input[name=name]").val();
					var fileurl = $("input[name=fileurl]").val();
					var post = {};
					post.photoalbum = photoalbum;
					post.title = title;
					post.fileurl = fileurl;
					if(post.title != "")
					{
						if(post.photoalbum != "")
						{
							if(post.fileurl !="")
							{
							    console.log(post);
								iChuk.RequestData("../../do/submitatlas.html","POST",post,function(data){
									if(data.ret == 1)
									{
										layer.msg(data.msg);
										setTimeout(function(){
											window.location.href="../../atlas/list.html";
										},1000)
									}
									else
									{
										layer.msg(data.msg);
									}
								})
							}
					        else
							{
							    layer.msg('请上传图集文件');
							}
						}
						else
						{
						    layer.msg('请上传图片');
						}
					}
					else
					{
					    layer.msg('请输入标题');
					}
					
				})

				iChuk.UploadImage('#_litpic_upload',data,function(url){
					var x_album_item = "preview-image";
			        var photoalbum = Array();
					var _photo = $('input[name='+input_photoalbum+']').val();
					var _append = false;
					var _main = false;
					var prompt = layer.prompt({
					  title: '输入图片描述',
					  formType: 2 //prompt风格，支持0-2
					}, function(c){
					    console.log(c);
						var tempitem = {};
						tempitem.title = String(c);
						tempitem.url = url;
						layer.close(prompt);
						if(_photo == "")
						{
							tempitem.type = "main";
							photoalbum.push(tempitem);
							var photoalbumstr = iChuk.json2str(photoalbum);
							$('input[name='+input_photoalbum+']').val(photoalbumstr);
							_append = !_append;
							_main = !_main;
						}
						else
						{
							var _value_arr = eval("("+_photo+")");
							$.each(_value_arr,function(key,value){
								photoalbum.push(value);
							})
							tempitem.type = "son";
							var has = false;
							for(x in photoalbum)
							{
								if(photoalbum[x].title == tempitem.title)
								{
									has = true;
								}
							}
							if(!has)
							{
								tempitem.type = "son";
								photoalbum.push(tempitem);
								var photoalbumstr = iChuk.json2str(photoalbum);
								$('input[name='+input_photoalbum+']').val(photoalbumstr);
								_append = !_append;
							}
						}

						if(_append)
						{ 
							var _html = '<li>\
											 <div class=\"delete\" rel=\"'+url+'\">删除</div>\
											 <img style=\'width:100%;\' src=\"'+url+'\" />';
							_html += _main?'<div class="type-main"><i>主图</i></div>':'<div class="type-son"><i>次图</i></div>';
							_html +=       '<div class=\"description\" >\
												<input type=\"text\" name=\"description\" data-litpic=\"'+url+'" data-description=\"'+tempitem.title+'\" value='+tempitem.title+' placeholder=\"图片描述\" />\
											</div>';
							_html +=    '</li>';
							$("#"+x_album_item).append(_html);
							
							$("input[name=description]").bind("change",function(){
								var changeinput =$(this);
							    var _url = changeinput.attr("data-litpic");
								var description = changeinput.attr("data-description");
								var _description = changeinput.val();
								if(description != _description)
								{
									var photoalbum = Array();
									var _photo = $('input[name='+input_photoalbum+']').val();
									var _value_arr = eval("("+_photo+")");
									$.each(_value_arr,function(key,value){
										photoalbum.push(value);
									})
								    for(x in photoalbum)
									{
										if(photoalbum[x].url == _url)
										{
											changeinput.attr("data-description",_description);
											photoalbum[x].title=_description;
										}
									}
									var photoalbumstr = iChuk.json2str(photoalbum);
									$('input[name='+input_photoalbum+']').val(photoalbumstr);
								}
							});
							$("#"+x_album_item).find(".delete").click(function(){
								var _src = $(this).attr("rel");
								var _value = $('input[name='+input_photoalbum+']').val();
								var _value_arr = eval("("+_value+")"); 
								var _photoalbum_ = Array();
								var _type_;
								$.each(_value_arr,function(key,value){
									if(value.url != _src)
									{
										_photoalbum_.push(value);
									}
									else{
										_type_ = value.type;
									}
								})
								$(this).parent().detach();
								if(_type_ == "main" && _photoalbum_.length > 0)
								{
									_photoalbum_[0].type = "main";
									$("#"+x_album_item).find("li").eq(0).find("div").eq(1).text("主图");
								}
								var _photoalbumstr_ = iChuk.json2str(_photoalbum_);
								$('input[name='+input_photoalbum+']').val(_photoalbumstr_);
							});
						}  
					}); 
					 
				});
				 
				
				$('#_litpic').click(function(){
					$('#_litpic_upload').trigger('click');
				}) 
			})
		}
	};
	(function(){
		handle.init();
	})();
});