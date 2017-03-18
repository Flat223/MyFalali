(function(factory){
	if(typeof define == 'function' && (define.amd || define.cmd)){
		define('cyupload',['jquery'],function(require,exports,modules){
			factory(require('jquery'));
			return jQuery;
		});
	}else{
		factory(jQuery);
	}
}(function($){
	var uploadInstanceNum = 0;
	var uploadParams4Instance = {};
	var myupload = {
		fileSelected:function(id){
			var oFile = document.getElementById("upload_file_input_"+id).files[0];
			if(oFile == undefined || oFile == null){
				return;
			}
			var params = uploadParams4Instance[id-1];
			if($.isFunction(params.before)){
				var ret = params.before(uploadParams4Instance[id-1]);
				if(ret != undefined && !ret){
					return;
				}
			}
			params.fileErr = "";
			var strs = oFile.name.split(".");
			var suffix = strs[strs.length-1];
			if(params.showInfo){
				$("#file_name_"+id).text(oFile.name);
				$("#file_size_"+id).text(this.bytesToSize(oFile.size));
				var rFilter = /^(doc|docx|xls|xlsx|pdf|zip|rar|7z)$/i;
				var rFilter2 = /^(bmp|gif|jpeg|png|tiff|jpg)$/i;
				if(rFilter.test(suffix)){
			        $("#file_type_"+id).text("文档");
			    }else if(rFilter2.test(suffix)) {
			        $("#file_type_"+id).text("图片");
			    }else{
				     $("#file_type_"+id).text(suffix+"文件");
			    }
			    $("#upload_file_info_"+id).css("display","block");
				$("#"+params.infoElementId).css("display","block");
			}
			if(params.fileFilter != "" && params.fileFilter != undefined){
				if(!params.fileFilter.test(suffix)){
					if(params.showInfo){
						$("#file_upload_status_"+id).text("未上传文件");
					}
				    params.fileErr = "上传文件的类型不正确";
				    if($.isFunction(params.error)){
					    params.error(params.fileErr);
				    }
				    return;
				}
			}
		    if(oFile.size > params.maxFilesize){
			    if(params.showInfo){
				    $("#file_upload_status_"+id).text("未上传文件");
			    }
			    var size = this.bytesToSize(params.maxFilesize);
			    params.fileErr = "文件不要超过"+size+"大小";
			    if($.isFunction(params.error)){
				    params.error(params.fileErr);
			    }
			    return;
		    }
			params.sResultFileSize = this.bytesToSize(oFile.size);
			var oReader = new FileReader();
			oReader.readAsDataURL(oFile);
			this.startUploading(id);
		},
		bytesToSize:function(bytes){
			var sizes = ['Bytes', 'KB', 'MB'];
		    if (bytes == 0) return 'n/a';
		    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
		},
		startUploading:function(id){
			var params = uploadParams4Instance[id-1];
			if(params.fileErr != ''){
				return;
			}
			params.isUploading = true;
			if(params.showInfo){
				$("#progress_info_"+id).fadeIn(100);
			}
			$("#upload_file_btn_"+id).text("上传中...").removeClass("upload_file_btn").addClass("upload_file_btn_undo");
			var vFD = new FormData(document.getElementById('upload_form_'+id));
		    // create XMLHttpRequest object, adding few event listeners, and POSTing our data
		    var oXHR = new XMLHttpRequest();       
		    oXHR.upload.addEventListener('progress', function(event){myupload.uploadProgress(event,id)}, false);
		    oXHR.addEventListener('load', function(event){myupload.uploadFinish(event,id)}, false);
		    oXHR.addEventListener('error', function(event){myupload.uploadError(event,id)}, false);
		    oXHR.addEventListener('abort', function(evnet){myupload.uploadAbort(event,id)}, false);
		    oXHR.open('POST', params.uploadUrl);
		    oXHR.send(vFD);
		    params.iBytesUploaded = 0;
		    params.iPreviousBytesLoaded = 0;
		    // set inner timer
		    params.oTimer = setInterval(function(){myupload.doInnerUpdates(id)}, 300);
		},
		doInnerUpdates:function(id){
			var params = uploadParams4Instance[id-1];
			var iCB = params.iBytesUploaded;
		    var iDiff = iCB - params.iPreviousBytesLoaded;
		    // if nothing new loaded - exit
		    if (iDiff == 0)return;
		    params.iPreviousBytesLoaded = iCB;
		    iDiff = iDiff * 2;
		    var iBytesRem = params.iBytesTotal - params.iPreviousBytesLoaded;
		    var secondsRemaining = iBytesRem / iDiff;
		    // update speed info
		    var iSpeed = iDiff.toString() + 'B/s';
		    if (iDiff > 1024 * 1024) {
		        iSpeed = (Math.round(iDiff * 100/(1024*1024))/100).toString() + 'MB/s';
		    } else if (iDiff > 1024) {
		        iSpeed =  (Math.round(iDiff * 100/1024)/100).toString() + 'KB/s';
		    }
		    if(params.showInfo){
			    $("#speed_"+id).text(iSpeed);
				$("#remain_time_"+id).text(secondsToTime(secondsRemaining)); 
		    }
		},
		uploadProgress:function(e,id) { // upload process in progress
		    if (e.lengthComputable) {
		    	var params = uploadParams4Instance[id-1];
		        params.iBytesUploaded = e.loaded;
		        params.iBytesTotal = e.total;
		        var iPercentComplete = Math.round(e.loaded * 100 / e.total);
		        var iBytesTransfered = this.bytesToSize(params.iBytesUploaded);
		        if(params.showInfo){
			        $("#progress_percent_"+id).text(iPercentComplete.toString() + '%');
			        $("#progress_done_"+id).css("width",iPercentComplete.toString() + '%');
			        $("#uploaded_size_"+id).text(iBytesTransfered);
			        if (iPercentComplete == 100) {
			            $("#upload_file_btn_"+id).text("正在处理...");
			        }
		        }
		    } else {
			    if(params.showInfo){
				    $("#file_upload_status_"+id).text("未上传文件");
			    }
			    params.fileErr = "上传发生错误";
			    if($.isFunction(params.error)){
				    params.error(params.fileErr);
			    }
			    this.clearParam(id);
		    }
		},
		uploadFinish:function(e,id) { // upload successfully finished
			var data = e.target.responseText;
			var dataobj = eval("("+data+")");
			var params = uploadParams4Instance[id-1];
			if(dataobj.ret == 1){
				if(params.showInfo){
					$("#progress_percent_"+id).text("100%");
					$("#progress_done_"+id).css("width","100%");
					$("#uploaded_size_"+id).text(params	.sResultFileSize);
					$("#remain_time_"+id).text("00:00:00"); 
					$("#file_upload_status_"+id).text("已上传完成");
				}
				if($.isFunction(params.success)){
					params.success(dataobj);
				}
			}else{
				if(params.showInfo){
					$("#file_upload_status_"+id).text("未上传文件");
					$("#progress_info_"+id).fadeOut(1000);
				}
			    params.fileErr = dataobj.msg;
			    if($.isFunction(params.error)){
				    params.error(params.fileErr);
			    }
			}
			params.isUploading = false;
			$("#upload_file_btn_"+id).text(params.btnName).addClass("upload_file_btn").removeClass("upload_file_btn_undo");
		    clearInterval(params.oTimer);
		    this.clearParam(id);
		},
		uploadError:function(e,id) { // upload error
			var params = uploadParams4Instance[id-1];
			if(params.showInfo){
				$("#file_upload_status_"+id).text("未上传文件");
				$("#progress_info_"+id).fadeOut(1000);
			}
			params.fileErr = "上传发生错误";
			if($.isFunction(params.error)){
			    params.error(params.fileErr);
			}
		    clearInterval(params.oTimer);
		    this.clearParam(id);
		},
		uploadAbort:function(e,id) { // upload abort
			var params = uploadParams4Instance[id-1];
			if(params.showInfo){
				$("#file_upload_status_"+id).text("未上传文件");
				 $("#progress_info_"+id).fadeOut(1000);
			}
			params.fileErr = "上传已被取消";
			if($.isFunction(params.error)){
			    params.error(params.fileErr);
			}
		    clearInterval(params.oTimer);
		    this.clearParam(id);
		},
		clearParam:function(id){
			var params = uploadParams4Instance[id-1];
			params.iBytesUploaded = 0;
			params.iBytesTotal = 0;
			params.iPreviousBytesLoaded = 0;
			params.oTimer = 0;
			params.sResultFileSize = '';
			params.fileErr = '';
			params.isUploading = false;
			$("#upload_file_input_"+id).val("");
		}	
	};
	$.extend({
		cyupload:function(options){
			uploadInstanceNum++;
			var uploadInstance = uploadInstanceNum;
			var defaultOpts = {	elem:'',
							   	btnName:"请选择",		//按键名称
							   	infoElementId:"",	//上传状态信息包装元素id
							   	maxFilesize:10485760,
							   	uploadUrl:'',
							   	fileFilter:'',
							   	upfileParam:''
							 };
			var opts = $.extend(defaultOpts,options);
			opts.iBytesUploaded = 0;opts.iBytesTotal = 0;opts.iPreviousBytesLoaded = 0;
			opts.sResultFileSize = 0;opts.oTimer = 0;opts.fileErr = '';opts.isUploading = false;
			var html = "<div class='upload_file'>";
			html += "<span class='upload_file_btn' style='display:block;width:100%;height:100%;text-align:center;'";
			html += "id='upload_file_btn_"+uploadInstance+"' >"+opts.btnName+"</span>";
			html += "<form id='upload_form_"+uploadInstance+"' enctype='multipart/form-data' method='post' action=''>";
			html += "<input class='upload_file_input' id='upload_file_input_"+uploadInstance+"' type='file' ";
			html += "style='display:none' name='"+opts.upfileParam+"'/></form></div>";
			$(opts.elem).html(html);
			if(opts.infoElementId && typeof(opts.infoElementId) == "string"){
				opts.showInfo = true;
				var infoHtml = "<div class='upload_file_info' id='upload_file_info_"+uploadInstance+"' style='width: 70%;'>";
				infoHtml += "<div class='file_prop'><div class='file_prop_name'>名称：</div>";
				infoHtml += "<div class='file_prop_val' id='file_name_"+uploadInstance+"'></div></div>";
				infoHtml += "<div class='file_prop'><div class='file_prop_name'>大小：</div>";
				infoHtml += "<div class='file_prop_val' id='file_size_"+uploadInstance+"'></div></div>";
				infoHtml += "<div class='file_prop'><div class='file_prop_name'>类型：</div>";
				infoHtml += "<div class='file_prop_val' id='file_type_"+uploadInstance+"'></div></div>";
				infoHtml += "<div class='file_prop'><div class='file_prop_name'>状态：</div>";
				infoHtml += "<div class='file_prop_val' id='file_upload_status_"+uploadInstance+"'></div></div>";
				infoHtml += "<div class='progress_info' id='progress_info_"+uploadInstance+"'>";
				infoHtml += "<div class='progress'><div class='progress_done' id='progress_done_"+uploadInstance+"'></div></div>";
				infoHtml += "<div class='progress_pecent' id='progress_percent_"+uploadInstance+"'></div>";
				infoHtml += "<div class='clear'></div><div class='progress_else_info'><div class='speed' id='speed_"+uploadInstance+"'></div>";
				infoHtml += "<div class='remain_time' id='remain_time_"+uploadInstance+"' ></div></div>";
				infoHtml += "<div class='uploaded_size' id='uploaded_size_"+uploadInstance+"' ></div></div></div>";
				$("#"+opts.infoElementId).html(infoHtml);
			}else{
				opts.showInfo = false;
			}
			uploadParams4Instance[uploadInstance-1] = opts;
			$("#upload_file_input_"+uploadInstance).on("change",function(){
				myupload.fileSelected(uploadInstance);
			});
			$("#upload_file_btn_"+uploadInstance).on("click",function(){
				if(opts.isUploading){
					return;
				}
				$(document).clearQueue("myqueue");
				var FUNC = [];
				opts.clickable = undefined;
				if($.isFunction(opts.onClick)){
					FUNC.push(function(){
						opts.onClick(opts,execQueue);
					});
				}
				FUNC.push(function(){
					if(opts.clickable != undefined && !opts.clickable){
						return;
					}
					$("#upload_file_input_"+uploadInstance).click();
				});
				$(document).queue("myqueue",FUNC);
				var execQueue = function(){
					$(document).dequeue("myqueue");
				}
				execQueue();
			});
		}
	});
}));