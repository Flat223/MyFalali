define(function(require,exports,module){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	require('easyui/jquery.easyui.min.js');
	require('easyui/locale/easyui-lang-zh_CN.js');
	layer.config({
		path:'/js/layer/'
	});
	require('common');
	require('cyupload.js');
	
	var productType = 1;//1:设备仪器 2:耗材  3:生物试剂 4:化学试剂
	var questflag = 0;
// 	var mid = $('input[name=mid]').val();
	var address_type = $('input[name=address_type]').val();
	var first_tid = $("select[name=first_type] option:selected").val();
	var second_tid = $("select[name=second_type] option:selected").val();
	var third_tid = $("select[name=third_type] option:selected").val();
	var forth_tid = $("select[name=forth_type] option:selected").val();
	var fifth_tid = $("select[name=fifth_type] option:selected").val();
	var ptid;
	
	var propertyArray;
	var second_type;
	var third_type;
	var forth_type;
	var fifth_type;
	
	exports.setProType = function(secondTypes,thirdTypes,forthTypes,fifthTypes){
		second_type = secondTypes;
		third_type = thirdTypes;
		forth_type = forthTypes;
		fifth_type = fifthTypes;
	}
	
	exports.setProPerty = function(properties){
		propertyArray = properties;
	}
	
	var handle = {
		initType:function(){
			if(fifth_tid != undefined){
				ptid = fifth_tid;
			} else if(forth_tid != undefined){
				fifth_tid = 0;
				ptid = forth_tid;
			} else if(third_tid != undefined){
				forth_tid = 0;
				ptid = third_tid;
			} else if(second_tid != undefined){
				third_tid = 0;
				ptid = second_tid;
			} else {
				second_tid = 0;
				ptid = first_tid;
			}
		},
		
		init:function(){	
/*
			$.cyupload({
				elem:'.import',
				uploadUrl:'/service/uploadFileServ.html',
				btnName:"Excel批量导入",
				upfileParam:"upload_file_input",
				maxFilesize:31457280,
				fileFilter:/^(xlsx|xls)$/i,
				error:function(data){
					layer.alert(data,{offset:'200px'});
				},
				success:function(res){
					console.log(res);
				}
			});
*/
			
			$("#brand").combobox({
				valueField:'name',
				textField:'name',
				panelWidth:200,
				panelHeight:'auto',
				onChange:function(value){
					$("#brand").combobox("reload","/service/GetProductBrandServ.html?name="+value.trim());
				}
			});
			
			$.cyupload({
				elem:'#uping',
				btnName:"请选择",		//按键名称
				infoElementId:"",	//上传状态信息包装元素id
				maxFilesize:10485760,
				uploadUrl:'/service/uploadimgServ.html',
				fileFilter:'',
				upfileParam:'upload_file_input',
				success:function(res){
					var img=$("img[name=uping]");
					for(var i=0;i<img.length;i++){
						if(img[i].src=="http://d27.ichuk.com/images/pc/upload.jpg"){
							img[i].src=res.file_url;
							return;
						}
					}
				},
				error:function(data){
					layer.alert(data,{offset:'200px'});
				}
			});
			
			$.cyupload({
				elem: '#uploadimg',
				btnName: "请选择",		//按键名称
				infoElementId: "",	//上传状态信息包装元素id
				maxFilesize: 10485760,
				uploadUrl: '/service/uploadimgServ.html',
				fileFilter: '',
				upfileParam: 'upload_file_input',
				success: function (url) {
					$('.proShowImage').attr('src', url['file_url']);
				}
			}); 
			
			$(".proShowImage").click(function(){
				$("#uploadimg").find(".upload_file_btn").trigger("click");
			});
			
			$('span[class=deleteimg]').on('click',function () {
				$(this).parent().find("img[name=uping]")[0].src="http://d27.ichuk.com/images/pc/upload.jpg";
			});

			$('select[name=first_type]').on('change',function(){
				var curr_tid = $("select[name=first_type] option:selected").val();
				if(first_tid == curr_tid){
					return;
				}
				first_tid = curr_tid;
				second_tid = handle.handleSelectType(first_tid,second_type,'second_type');
				
				if(second_tid != 0){
					third_tid = handle.handleSelectType(second_tid,third_type,'third_type');
					if(third_tid != 0){
					forth_tid = handle.handleSelectType(third_tid,forth_type,'forth_type');
						if(forth_tid != 0){
							fifth_tid = handle.handleSelectType(forth_tid,fifth_type,'fifth_type');	
						} 
					}	
				}
				handle.changeProductType(first_tid);
				handle.showProductInfo();
			});
			$('select[name=second_type]').on('change',function(){
				var curr_tid = $("select[name=second_type] option:selected").val();
				if(second_tid == curr_tid){
					return;
				}
				second_tid = curr_tid;	
				third_tid = handle.handleSelectType(second_tid,third_type,'third_type');
				
				if(third_tid != 0){
					forth_tid = handle.handleSelectType(third_tid,forth_type,'forth_type');
					if(forth_tid != 0){
						fifth_tid = handle.handleSelectType(forth_tid,fifth_type,'fifth_type');	
					} 
				} 
			});
			
			$('select[name=third_type]').on('change',function(){
				var curr_tid = $("select[name=third_type] option:selected").val();
				if(third_tid == curr_tid){
					return;
				}
				third_tid = curr_tid;
				forth_tid = handle.handleSelectType(third_tid,forth_type,'forth_type');
				
				if(forth_tid != 0){
					fifth_tid = handle.handleSelectType(forth_tid,fifth_type,'fifth_type');	
				} 
			});
			
			$('select[name=forth_type]').on('change',function(){
				var curr_tid = $("select[name=forth_type] option:selected").val();
				if(forth_tid == curr_tid){
					return;
				}
				forth_tid = curr_tid;
				fifth_tid = handle.handleSelectType(forth_tid,fifth_type,'fifth_type');
			});
			
			$('select[name=fifth_type]').on('change',function(){
				var curr_tid = $("select[name=fifth_type] option:selected").val();
				if(fifth_tid == curr_tid){
					return;
				}
				fifth_tid = curr_tid;
			});

			$(":radio").on('click',function(){
				if($(this).attr('name') == "can_testing"){
					if($(this).val() == '1'){
						$('.quality_price').show();
						$('input[name=quality_testing]').show();
					} else {
						$('.quality_price').hide();
						$('input[name=quality_testing]').hide();
					}
				}
				if($(this).attr('name') == "can_guarantee"){
					if($(this).val() == '1'){
						$('.guarantee').show();
					} else {
						$('.guarantee').hide();
					}
				}
			});
			
			$("select[name=freight_way]").on("change",function(){
				var freight_way = $("select[name=freight_way] option:selected").val();
				if(freight_way == 1){
					$('.freight_intro').css('display','inline');
					$('div.address').hide();
				} else {
					$('.freight_intro').hide();
					$('div.address').show();
				}
				
				var valuation_unit = $("select[name=freight] option:selected").attr('valuation_unit');
				if(valuation_unit == 2){
					$('.weight_info').show();
					$('.volume_info').hide();
				} else if(valuation_unit == 3){
					$('.weight_info').hide();
					$('.volume_info').show();
				} else {
					$('.weight_info').hide();
					$('.volume_info').hide();
				}
			});
			$("select[name=freight]").on("change",function(){
				$('input[name=weight]').val('');
				$('input[name=volume]').val('');
				var valuation_unit = $(this).find("option:selected").attr('valuation_unit');
				if(valuation_unit == 2){
					$('.weight_info').show();
					$('.volume_info').hide();
				} else if(valuation_unit == 3){
					$('.weight_info').hide();
					$('.volume_info').show();
				} else {
					$('.weight_info').hide();
					$('.volume_info').hide();
				}
			});
			
			$('.nextStep').on('click',function(){
				if(questflag == 1){
					return;
				}
				var name = $.trim($('input[name=proName]').val());
				var proAlias = $.trim($('input[name=proAlias]').val());
				var EnglishName = $.trim($('input[name=EnglishName]').val());				
				var CASnumber = $.trim($('input[name=CASnumber]').val());
				var goods_code = $.trim($('input[name=goods_code]').val());
				var size = $.trim($('input[name=size]').val());
				var purity = $.trim($('input[name=purity]').val());
				
				var packing = $('input[name=packing]:checked').val();
				var is_harmful = $('input[name=is_harmful]:checked').val();
				
				var unit = $("select[name=unit] option:selected").text();
				var shelf_life = $("select[name=shelf_life] option:selected").text();
				var producer = $('input[name=producer]:checked').val();
				var freight_way = $("select[name=freight_way] option:selected").val();
				var fre_id = $("select[name=freight] option:selected").val();
				var valuation_unit = $("select[name=freight] option:selected").attr('valuation_unit');
				
				var store = $("select[name=store] option:selected").text();
				var traffic = $("select[name=traffic] option:selected").text();
				
				var video_url = $('input[name=proVideo]').val();
				var video_img = $.trim($('.proShowImage').attr('src'));
				var intro=ue.getContent();
                var intro_mobile=uc.getContent();
				var vipone=$.trim($("input[name=vipone]").val());
				var viptwo=$.trim($("input[name=viptwo]").val());
				var vipthree=$.trim($("input[name=vipthree]").val());
				var vipfour=$.trim($("input[name=vipfour]").val());
				
				var can_guarantee = $('input[name=is_harmful]:checked').val();
				var can_testing = $('input[name=can_testing]:checked').val();
				var quality_testing = $('input[name=quality_testing]').val();
				var can_guarantee = $('input[name=can_guarantee]:checked').val();
				var guarantee_1 = $('input[name=guarantee_1]').val();
				var guarantee_2 = $('input[name=guarantee_2]').val();
				var guarantee_5 = $('input[name=guarantee_5]').val();
				
				var params = {};
				
				//必填
				if(name == ""){
					layer.alert("请填写产品名称",{offset:'200px'});
					return;
				}
				if(goods_code == ""){
					layer.alert("请填写产品货号",{offset:'200px'});
					return;
				}
				if(size == ""){
					layer.alert("请填写产品规格",{offset:'200px'});
					return;
				}
				if(productType == 3 || productType == 4){
					if(CASnumber == ""){
						layer.alert("请填写产品CAS号",{offset:'200px'});
						return;
					}
					params.CASnumber = CASnumber;
					if(purity == ""){
						layer.alert("请填写产品含量",{offset:'200px'});
						return;
					}
					params.purity = purity;
					if(proAlias != ""){
						params.proAlias = proAlias;	
					}
					if(is_harmful != 0){
						params.is_harmful = is_harmful;
					}
				}
				
				var imageUrl = "";
				var isUploadImg = false;
				var imgArray = $("img[name=uping]");
				for(var i=0;i<imgArray.length;i++){
					var image = imgArray[i];
					if(image.src != "http://d27.ichuk.com/images/pc/upload.jpg"){
						isUploadImg = true;
						if(imageUrl == ""){
							imageUrl = image.src;
						} else {
							imageUrl = imageUrl + "," + image.src;
						}
					}
				}
				
				if(!isUploadImg){
					layer.alert("请上传产品图片",{offset:'200px'});
					return;
				}
				var brand = $('#brand').combobox('getValue');
				if(brand == null || brand == ""){
					layer.alert("请选择品牌",{offset:'200px'});
					return;
				}
	
				if(freight_way == 1){
					if(fre_id == "" || fre_id == undefined){
						layer.alert("请添加运费模板",{offset:'200px'});
						return;
					}
					if(valuation_unit == 2){
						var weight = $.trim($('input[name=weight]').val());	
						if(weight == "" || weight == undefined){
							layer.alert("运费模板的计价方式为按重量计算,请填写产品重量",{offset:'200px'});
							return;
						}
						params.weight = weight;	
					} else if(valuation_unit == 3){
						var volume = $.trim($('input[name=volume]').val());
						if(volume == "" || volume == undefined){
							layer.alert("运费模板的计价方式为按体积计算,请填写产品体积",{offset:'200px'});
							return;
						}	
						params.volume = volume;	
					}	
					params.fre_id = fre_id;	
				} else {
					if(address_type == 0){
						layer.alert('请先添加发货地址');
						return;
					}
					var address_id = $('select[name=address] option:selected').val();
					params.address_id = address_id;
					params.fre_id = 0;	
				}
				
				//选填
				if(productType == 1 || productType == 4){
					if(EnglishName != ""){
						params.EnglishName = EnglishName;	
					}
				}
				if(productType != 2){
					if(shelf_life != ''){
						params.shelf_life = shelf_life;	
					}
				}
						
				if(parseInt(vipone)>1 ||  parseInt(vipone)<0){
					layer.alert("折扣值必须在0和1之间",{offset:'200px'});
					return;
				}
				if(parseInt(viptwo)>1 || parseInt(viptwo)<0){
					layer.alert("折扣值必须在0和1之间",{offset:'200px'});
					return;
				}
				if(parseInt(vipthree)>1 || parseInt(vipthree)<0){
					layer.alert("折扣值必须在0和1之间",{offset:'200px'});
					return;
				}
				if(parseInt(vipfour)>1 || parseInt(vipfour)<0){
					layer.alert("折扣值必须在0和1之间",{offset:'200px'});
					return;
				}
				if(can_testing == 1 && quality_testing == ""){
					layer.alert("请设定质检价格",{offset:'200px'});
					return;
				}
				if(can_guarantee == 1 && (guarantee_1 == "" || guarantee_2 == "" || guarantee_5 == "")){
					layer.alert("请将保修价格设定完整",{offset:'200px'});
					return;
				}
				
				if(fifth_tid != 0 && fifth_tid != undefined){
					ptid = fifth_tid;
				} else if(forth_tid != 0 && forth_tid != undefined){
					ptid = forth_tid;
				} else if(third_tid != 0 && third_tid != undefined){
					ptid = third_tid;
				} else if(second_tid != 0 && second_tid != undefined){
					ptid = second_tid;
				} else {
					ptid = first_tid;
				}			
				
				params.ptid = ptid;
				params.first_tid = first_tid;
				params.second_tid = second_tid;
				params.third_tid = third_tid;
				params.forth_tid = forth_tid;
				params.fifth_tid = fifth_tid;
				
				params.name = name;
				params.goods_code = goods_code;
				params.size = size;
				params.packing = packing;
				params.unit = unit;	
				params.brand = brand;
				params.imageUrl = imageUrl;
				
				if(producer != ""){
					params.producer = producer;
				}
				if(store != ""){
					params.store = store;
				}
				if(traffic != ""){
					params.traffic = traffic;
				}
				if(video_url != ""){
					params.video_url = video_url;
				}	
				if(video_img != "http://d27.ichuk.com/images/pc/upload.jpg"){
					params.video_img = video_img;
				}
				if(vipone != ""){
					params.vipone=vipone;	
				}
				if(viptwo != ""){
					params.viptwo=viptwo;	
				}
				if(vipthree != ""){
					params.vipthree=vipthree;	
				}
				if(vipfour != ""){
					params.vipfour=vipfour;	
				}
				if(can_testing == 1){
					params.can_testing = 1;
					params.quality_testing = quality_testing;
				}
				if(can_testing == 1){
					params.can_guarantee = 1;
					params.guarantee_1 = guarantee_1;
					params.guarantee_2 = guarantee_2;
					params.guarantee_5 = guarantee_5;
				}
				if(intro != ""){
					params.intro = intro;
				}	
				if(intro_mobile != ""){
					params.intro_mobile = intro_mobile;
				}
				console.log(params);
				questflag = 1;
				$.ajax({
					type:"post",
					dataType:"json",
					url:'/service/addMyProductServ.html',
					data:params,
					success:function(data){
						if(data.ret == 1){
							location.href = "/myshop/addProNext.html?pid=" + data.pronumber;
						}else{
							layer.alert(data.msg,{offset:'200px'});
						}
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					},
					complete:function(){
						questflag = 0;
					}
				});
				
			});
		},
		
		changeProductType:function(first_tid){
			if(first_tid == 1){
				productType = 1;
			} else if(first_tid == 93){
				productType = 4;
			} else if(first_tid == 217 || first_tid == 218 || first_tid == 219){
				productType = 3;
			} else {
				productType = 2;
			}
		},
		
		showProductInfo:function(){
			if(productType == 1){
				$('.EnglishName').show();
				$('.shelf_life').show();
				
				$('.proAlias').hide();
				$('.CASnumber').hide();
				$('.purity').hide();
				$('.is_harmful').hide();
			} else if(productType == 3){
				$('.proAlias').show();
				$('.is_harmful').show();
				$('.shelf_life').show();
				$('.CASnumber').show();
				$('.purity').show();
				
				$('.EnglishName').hide();
			} else if(productType == 4){
				$('.proAlias').show();
				$('.EnglishName').show();
				$('.CASnumber').show();
				$('.purity').show();
				$('.is_harmful').show();
				$('.shelf_life').show();
			} else {
				$('.proAlias').hide();
				$('.EnglishName').hide();
				$('.CASnumber').hide();
				$('.purity').hide();
				$('.is_harmful').hide();
				$('.shelf_life').hide();
			}
		},
		
		handleSelectType:function(ptid,typeArray,typeName){
			var exitSub = false;
			var str;
			var subFirPtid = 0;
			for(var i in typeArray){
				var type = typeArray[i];
				if(type['parentid'] != ptid){
					continue;
				}
				exitSub = true;
				str = str + "<option value="+type['ptid']+">"+type['name']+"</option>";
			}
			if(exitSub){
				$("select[name=" + typeName + "]").html(str);
				$("select[name=" + typeName + "]").show();
				subFirPtid = $("select[name=" + typeName + "] option:selected").val();
			} else {
				$("select[name=" + typeName + "]").hide();
				if(typeName == "second_type"){
					third_tid = 0;
					forth_tid = 0;
					fifth_tid = 0;
					$("select[name=third_type").hide();
					$("select[name=forth_type").hide();
					$("select[name=fifth_type").hide();
				}
				if(typeName == "third_type"){
					forth_tid = 0;
					fifth_tid = 0;
					$("select[name=forth_type").hide();
					$("select[name=fifth_type").hide();
				}
				if(typeName == "forth_type"){
					fifth_tid = 0;
					$("select[name=fifth_type").hide();
				}
			}
			return subFirPtid;
		},
		
/*
		getProductType:function(level){
			$.ajax({
				type:"post",
				dataType:"json",
				url:'/service/getProductTypeServ.html',
				data:"",
				success:function(data){
					if(data.ret == 1){
						second_type = data.second;
						third_type = data.third;
						forth_type = data.forth;
						fifth_type = data.fifth;
					}else{
						layer.alert(data.msg,{offset:'200px'});
					}
				},
				error:function(data){
					layer.alert('服务器错误,请稍后再试',{offset:'200px'});
				},
				complete:function(){

				}
			});
		},	
*/
		
/*
		getAllProperty:function(level){
			$.ajax({
				type:"post",
				dataType:"json",
				url:'/service/getAllPropertyServ.html',
				data:"",
				success:function(data){
					if(data.ret == 1){
						propertyArray = data.property;
					}else{
						layer.alert(data.msg,{offset:'200px'});
					}
				},
				error:function(data){
					layer.alert('服务器错误,请稍后再试',{offset:'200px'});
				},
				complete:function(){

				}
			});
		},
*/
	};
	
	$(function(){
		handle.init();
	});
});