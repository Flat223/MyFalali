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
					var html = "<div class='upload_file'><span class='upload_file_btn' id='upload_file_btn_"+uploadInstance+"' >"+opts.btnName+"</span>";
					html += "<form id='upload_form_"+uploadInstance+"' enctype='multipart/form-data' method='post' action=''>";
					html += "<input class='upload_file_input' id='upload_file_input_"+uploadInstance+"' type='file' ";
					html += "style='display:none' name='upload_file_input'/></form></div>";
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
					$("#upload_file_input_"+uploadInstance).on("change",function(){
						fileSelected(uploadInstance);
					});
					$("#upload_file_btn_"+uploadInstance).on("click",function(){
						if(opts.isUploading){
							return;
						}
						$("#upload_file_input_"+uploadInstance).click();
					});
				}
			});	
		})(jquery);
	};
	
	function secondsToTime(secs) { // we will use this function to convert seconds in normal time format
	    var hr = Math.floor(secs / 3600);
	    var min = Math.floor((secs - (hr * 3600))/60);
	    var sec = Math.floor(secs - (hr * 3600) -  (min * 60));
	    if (hr < 10) {hr = "0" + hr; }
	    if (min < 10) {min = "0" + min;}
	    if (sec < 10) {sec = "0" + sec;}
	    if (hr) {hr = "00";}
	    return hr + ':' + min + ':' + sec;
	};
	
	function bytesToSize(bytes) {
	    var sizes = ['Bytes', 'KB', 'MB'];
	    if (bytes == 0) return 'n/a';
	    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
	};

	function fileSelected(id){
		var oFile = document.getElementById("upload_file_input_"+id).files[0];
		if(oFile == undefined || oFile == null){
			return;
		}
		var params = uploadParams4Instance[id-1];
		if($.isFunction(params.begin)){
			var ret = params.begin(id);
			if(ret != undefined && !ret){
				return;
			}
		}
		params.fileErr = "";
		$("#file_name_"+id).text(oFile.name);
		$("#file_size_"+id).text(bytesToSize(oFile.size));
		var rFilter = /^(doc|docx|xls|xlsx|pdf)$/i;
		var rFilter2 = /^(bmp|gif|jpeg|png|tiff|jpg)$/i;
		var strs = oFile.name.split(".");
		var suffix = strs[strs.length-1];
		if(rFilter.test(suffix)){
	        $("#file_type_"+id).text("文档");
	    }else if(rFilter2.test(suffix)) {
	        $("#file_type_"+id).text("图片");
	    }else{
		     $("#file_type_"+id).text(suffix+"文件");
	    }
	    $("#upload_file_info_"+id).css("display","block");
		$("#"+params.infoElementId).css("display","block");
		if(params.fileFilter != "" && params.fileFilter != undefined){
			if(!params.fileFilter.test(suffix)){
				$("#file_upload_status_"+id).text("未上传文件");
			    params.fileErr = "上传文件的类型不正确";
			    if($.isFunction(params.error)){
				    params.error(params.fileErr);
			    }
			    return;
			}
		}
	    if(oFile.size > params.maxFilesize){
		    $("#file_upload_status_"+id).text("未上传文件");
		    params.fileErr = "文件不要超过10M大小";
		    if($.isFunction(params.error)){
			    params.error(params.fileErr);
		    }
		    return;
	    }
		params.sResultFileSize = bytesToSize(oFile.size);
		var oReader = new FileReader();
		oReader.readAsDataURL(oFile);
		startUploading(id);
	}

	function startUploading(id){
		var params = uploadParams4Instance[id-1];
		if(params.fileErr != ''){
			return;
		}
		params.isUploading = true;
		$("#progress_info_"+id).fadeIn(100);
		$("#upload_file_btn_"+id).text("上传中...").removeClass("upload_file_btn").addClass("upload_file_btn_undo");
		var vFD = new FormData(document.getElementById('upload_form_'+id));
	    // create XMLHttpRequest object, adding few event listeners, and POSTing our data
	    var oXHR = new XMLHttpRequest();       
	    oXHR.upload.addEventListener('progress', function(event){uploadProgress(event,id)}, false);
	    oXHR.addEventListener('load', function(event){uploadFinish(event,id)}, false);
	    oXHR.addEventListener('error', function(event){uploadError(event,id)}, false);
	    oXHR.addEventListener('abort', function(evnet){uploadAbort(event,id)}, false);
	    oXHR.open('POST', params.uploadUrl);
	    oXHR.send(vFD);
	    // set inner timer
	    params.oTimer = setInterval(function(){doInnerUpdates(id)}, 300);
	}
	
	function doInnerUpdates(id){
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
	    $("#speed_"+id).text(iSpeed);
	    $("#remain_time_"+id).text(secondsToTime(secondsRemaining)); 
	}
	
	function uploadProgress(e,id) { // upload process in progress
	    if (e.lengthComputable) {
	    	var params = uploadParams4Instance[id-1];
	        params.iBytesUploaded = e.loaded;
	        params.iBytesTotal = e.total;
	        var iPercentComplete = Math.round(e.loaded * 100 / e.total);
	        var iBytesTransfered = bytesToSize(params.iBytesUploaded);
	        $("#progress_percent_"+id).text(iPercentComplete.toString() + '%');
	        $("#progress_done_"+id).css("width",iPercentComplete.toString() + '%');
	        $("#uploaded_size_"+id).text(iBytesTransfered);
	        if (iPercentComplete == 100) {
	            $("#upload_file_btn_"+id).text("正在处理...");
	        }
	    } else {
	    	$("#file_upload_status_"+id).text("未上传文件");
		    params.fileErr = "上传发生错误";
		    if($.isFunction(params.error)){
			    params.error(params.fileErr);
		    }
		    clearParam(id);
	        //document.getElementById('progress').innerHTML = 'unable to compute';
	    }
	}
	
	function uploadFinish(e,id) { // upload successfully finished
		var data = e.target.responseText;
		var dataobj = eval("("+data+")");
		var params = uploadParams4Instance[id-1];
		if(dataobj.ret == 1){
			$("#progress_percent_"+id).text("100%");
			$("#progress_done_"+id).css("width","100%");
			$("#uploaded_size_"+id).text(params	.sResultFileSize);
			$("#remain_time_"+id).text("00:00:00"); 
			if($.isFunction(params.success)){
				params.success(dataobj.file_url);
			}
			$("#file_upload_status_"+id).text("已上传完成");
		}else{
			$("#file_upload_status_"+id).text("未上传文件");
		    params.fileErr = dataobj.msg;
		    if($.isFunction(params.error)){
			    params.error(params.fileErr);
		    }
		}
		params.isUploading = false;
		$("#upload_file_btn_"+id).text(params.btnName).addClass("upload_file_btn").removeClass("upload_file_btn_undo");
	    clearInterval(params.oTimer);
	    $("#progress_info_"+id).fadeOut(1000);
	    clearParam(id);
	}
	
	function uploadError(e,id) { // upload error
		var params = uploadParams4Instance[id-1];
		$("#file_upload_status_"+id).text("未上传文件");
		params.fileErr = "上传发生错误";
		if($.isFunction(params.error)){
		    params.error(params.fileErr);
		}
	    clearInterval(params.oTimer);
	    $("#progress_info_"+id).fadeOut(1000);
	    clearParam(id);
	}
	
	function uploadAbort(e,id) { // upload abort
		var params = uploadParams4Instance[id-1];
		$("#file_upload_status_"+id).text("未上传文件");
		params.fileErr = "上传已被取消";
		if($.isFunction(params.error)){
		    params.error(params.fileErr);
		}
	    clearInterval(params.oTimer);
	    $("#progress_info_"+id).fadeOut(1000);
	    clearParam(id);
	}
	
	function clearParam(id){
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

	
});
	