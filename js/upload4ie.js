define(function(require,exports,module){
	var uploadInstanceNum = 0;
	var uploadParams4Instance = {};
	
	return function(jquery){
		(function($){
			$.fn.extend({
				myUpload:function(data){
					uploadInstanceNum++;
					var uploadInstance = uploadInstanceNum;
					var defaultParams = {btnName:"请选择",
										 infoElementId:"upload_info_content",
										 iBytesUploaded:0,
										 iBytesTotal:0,
										 iPreviousBytesLoaded:0,
										 maxFilesize:10485760,
										 oTimer:0,
										 sResultFileSize:'',
										 fileErr:'',
										 isUploading:false,
										 uploadUrl:'',
										 fileFilter:''
										 };
					var opts = $.extend(defaultParams,data);
					var html = "<div class='upload_file'><span class='upload_file_btn' style='position:relative;' id='upload_file_btn_"+uploadInstance+"' >"+opts.btnName;
					html += "<form id='upload_form_"+uploadInstance+"' ><input class='upload_file_input' id='upload_file_input_"+uploadInstance+"' type='file' ";
					html += "style='position:absolute;left:0;top:0;width:100%;height:100%;z-index:1005;opacity:0;filter:alpha(opacity=0);cursor:pointer;margin-top:0;' name='upload_file_input'/></form></span></div>";
					$(this).html(html);
					var infoHtml = "<div class='upload_file_info' id='upload_file_info_"+uploadInstance+"' style='width: 70%;'>";
					infoHtml += "<div class='file_prop'><div class='file_prop_name'>名称：</div><div class='file_prop_val' id='file_name_"+uploadInstance+"'></div></div>";
					infoHtml += "<div class='file_prop'><div class='file_prop_name'>大小：</div><div class='file_prop_val' id='file_size_"+uploadInstance+"'></div></div>";
					infoHtml += "<div class='file_prop'><div class='file_prop_name'>类型：</div><div class='file_prop_val' id='file_type_"+uploadInstance+"'></div></div>";
					infoHtml += "<div class='file_prop'><div class='file_prop_name'>状态：</div><div class='file_prop_val' id='file_upload_status_"+uploadInstance+"'></div></div>";
					infoHtml += "<div class='progress_info' id='progress_info_"+uploadInstance+"' ><div class='progress'><div class='progress_done' id='progress_done_"+uploadInstance+"' ></div></div>";
					infoHtml += "<div class='progress_pecent' id='progress_percent_"+uploadInstance+"' ></div><div class='clear'></div><div class='progress_else_info'><div class='speed' id='speed_"+uploadInstance+"' ></div>";
					infoHtml += "<div class='remain_time' id='remain_time_"+uploadInstance+"' ></div></div><div class='uploaded_size' id='uploaded_size_"+uploadInstance+"' ></div></div></div>";
					$("#"+opts.infoElementId).html(infoHtml);
					uploadParams4Instance[uploadInstance-1] = opts;
					$(document).on("change","#upload_file_input_"+uploadInstance,function(){
						selectedFile(uploadInstance);
					});
/*
					$("#upload_file_input_"+uploadInstance).on("change",function(){
						selectedFile(uploadInstance);
					});
*/
				}
			});	
		})(jquery);	
	};
	
	function selectedFile(uploadInstance){
		var opts = uploadParams4Instance[uploadInstance-1];
		if($.isFunction(opts.begin)){
			var ret = opts.begin();
			if(ret != undefined && !ret){
				return;
			}
		}
		selectedFile2(uploadInstance);
	}
	
	function selectedFile2(uploadInstance){
		var opts = uploadParams4Instance[uploadInstance-1];
		var fielName = $('#upload_file_input_'+uploadInstance).val();  
		$("#file_name_"+uploadInstance).text(fielName);
		//$("#file_size_"+uploadInstance).text(bytesToSize(fs));
		var rFilter = /^(doc|docx|xls|xlsx|pdf)$/i;
		var rFilter2 = /^(bmp|gif|jpeg|png|tiff|jpg)$/i;
		var strs = fielName.split(".");
		var suffix = strs[strs.length-1];
		if(rFilter.test(suffix)){
	        $("#file_type_"+uploadInstance).text("文档");
	    }else if(rFilter2.test(suffix)) {
	        $("#file_type_"+uploadInstance).text("图片");
	    }else{
		     $("#file_type_"+uploadInstance).text(suffix+"文件");
	    }
	    $("#upload_file_info_"+uploadInstance).css("display","block");
		$("#"+opts.infoElementId).css("display","block");
		if(opts.fileFilter != "" && opts.fileFilter != undefined){
			if(!opts.fileFilter.test(suffix)){
				$("#file_upload_status_"+uploadInstance).text("未上传文件");
			    opts.fileErr = "上传文件的类型不正确";
			    if($.isFunction(opts.error)){
				    opts.error(opts.fileErr);
			    }
			    $("#upload_form_"+uploadInstance)[0].reset();
			    return;
			}
		}
	    /*
	if(fs > opts.maxFilesize){
		    $("#file_upload_status_"+uploadInstance).text("未上传文件");
		    opts.fileErr = "文件不要超过10M大小";
		    if($.isFunction(opts.error)){
			    opts.error(opts.fileErr);
		    }
		    $("#upload_form_"+uploadInstance)[0].reset();
		    return;
	    }
	*/
		//opts.sResultFileSize = bytesToSize(fs);
		$.ajaxFileUpload({
			url:opts.uploadUrl,
			secureuri:false,
			fileElementId:'upload_file_input_'+uploadInstance,
			dataType: 'text',
			success:function(data,status){
				var dataobj = eval("("+data+")");
				if(dataobj.ret == 1){
					$("#progress_percent_"+uploadInstance).text("100%");
					$("#progress_done_"+uploadInstance).css("width","100%");
					//$("#uploaded_size_"+uploadInstance).text(opts.sResultFileSize);
					if($.isFunction(opts.success)){
						opts.success(dataobj.file_url);
					}
					$("#file_upload_status_"+uploadInstance).text("已上传完成");
					$("#upload_form_"+uploadInstance)[0].reset();
				}else{
					$("#file_upload_status_"+uploadInstance).text("未上传文件");
				    opts.fileErr = dataobj.msg;
				    if($.isFunction(opts.error)){
				    	opts.error(opts.fileErr);
					}
				}
				opts.isUploading = false;
				$("#upload_form_"+uploadInstance)[0].reset();
			},
			error:function(data){
				$("#file_upload_status_"+uploadInstance).text("未上传文件");
			    opts.fileErr = "上传发生错误";
			    if($.isFunction(opts.error)){
			    	opts.error(opts.fileErr);
				}
				var form = $("#upload_form_"+uploadInstance)[0];
				$("#upload_form_"+uploadInstance)[0].reset();
			}
		});
	
	};
	
	function bytesToSize(bytes) {
	    var sizes = ['Bytes', 'KB', 'MB'];
	    if (bytes == 0) return 'n/a';
	    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
	}

});