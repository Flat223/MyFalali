define(function(require,exports,module){
    var $ = require('jquery');
    var layer = require('layer/layer.js');
    require('easyui/jquery.easyui.min.js');
    require('easyui/locale/easyui-lang-zh_CN.js');
    layer.config({
        path:'/js/layer/'
    });
    require('cyupload.js');

// 	var mid = $('input[name=mid]').val();
	
	var proType_change = false;
	var pid = $('input[name=pid]').val();
	var address_type = $('input[name=address_type]').val();
	var productType = $('input[name=productType]').val();
	
	var ptid;
	var first_tid = $("select[name=first_type] option:selected").val();
	var second_tid = $("select[name=second_type] option:selected").val();
	var third_tid = $("select[name=third_type] option:selected").val();
	var forth_tid = $("select[name=forth_type] option:selected").val();
	var fifth_tid = $("select[name=fifth_type] option:selected").val();
	
	var product;
	var propertyArray;
	var second_type;
	var third_type;
	var forth_type;
	var fifth_type;
	
	exports.setProduct = function(pro){
		product = pro;
	}
	exports.setProPerty = function(properties){
		propertyArray = properties;
	}
	exports.setProType = function(secondTypes,thirdTypes,forthTypes,fifthTypes){
		second_type = secondTypes;
		third_type = thirdTypes;
		forth_type = forthTypes;
		fifth_type = fifthTypes;
	}
	
    var handle = {
        init:function(){
	        
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
	        
	        var producer = product.producer;
	        var packing = product.packing;

	        $(":radio[name='producer'][value='" + producer + "']").prop("checked", "checked"); 
	        $(":radio[name='packing'][value='" + packing + "']").prop("checked", "checked");
	        
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
                success:function(url){
                    var img=$("img[name=uping]");
                    for(var i=0;i<img.length;i++){
                        if(img[i].src=="http://d27.ichuk.com/images/pc/upload.jpg"){
                            img[i].src=url['file_url'];
                            return;
                        }
                    }
                }
            });
	        
	        $.cyupload({
                elem:'#brand_upimg',
                btnName:"请选择",		//按键名称
                infoElementId:"",	//上传状态信息包装元素id
                maxFilesize:10485760,
                uploadUrl:'/service/uploadimgServ.html',
                fileFilter:'',
                upfileParam:'upload_file_input',
                success:function(url){
                    $('#images').attr('src',url['file_url']);
                }
            });

            $('span[class=deleteimg]').on('click',function () {
                $(this).parent().find("img[name=uping]")[0].src="http://d27.ichuk.com/images/pc/upload.jpg";
            });
            
            $('select[name=first_type]').on('change',function(){
	            if(!proType_change){
					var alert = layer.confirm('分类修改后将要重新填写产品信息(包括属性)',{
			        				title:'确定修改分类?',
									btn:['确定','取消']
		        				},function(){
			        				proType_change = true;
				        			layer.close(alert);
				        			$('select[name=first_type]').change();
			        			},function(){
									$("select[name=first_type]").find("option[value="+first_tid+"]").prop('selected','selected');
									layer.close(alert);
			        			});
	        	} 
	        	
	        	if(!proType_change){
		        	return;
	        	}
	        	
	        	first_tid = $('select[name=first_type] option:selected').val();
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
				if(!proType_change){
					var alert = layer.confirm('分类修改后将要重新填写产品信息(包括属性)',{
		        				title:'确定修改分类?',
								btn:['确定','取消']
	        				},function(){
		        				proType_change = true;
			        			layer.close(alert);
			        			$('select[name=second_type]').change();
		        			},function(){
			        			$("select[name=second_type]").find("option[value="+second_tid+"]").prop('selected','selected');
								layer.close(alert);
		        			});
	        	}
	        	
				if(!proType_change){
					return;
				}
				
				second_tid = $(this).find('option:selected').val();
				third_tid = handle.handleSelectType(second_tid,third_type,'third_type');
				
				if(third_tid != 0){
					forth_tid = handle.handleSelectType(third_tid,forth_type,'forth_type');
					if(forth_tid != 0){
						fifth_tid = handle.handleSelectType(forth_tid,fifth_type,'fifth_type');	
					} 
				} 
			});
			
			$('select[name=third_type]').on('change',function(){
				if(!proType_change){
					var alert = layer.confirm('分类修改后将要重新填写产品信息(包括属性)',{
		        				title:'确定修改分类?',
								btn:['确定','取消']
	        				},function(){
		        				proType_change = true;
			        			layer.close(alert);
			        			$('select[name=third_type]').change();
		        			},function(){
			        			$("select[name=third_type]").find("option[value="+third_tid+"]").prop('selected','selected');
								layer.close(alert);
		        			});
	        	}
	        	
				if(!proType_change){
					return;
				}
				
				third_tid = $(this).find('option:selected').val();
				forth_tid = handle.handleSelectType(third_tid,forth_type,'forth_type');
				
				if(forth_tid != 0){
					fifth_tid = handle.handleSelectType(forth_tid,fifth_type,'fifth_type');	
				} 
			});
			
			$('select[name=forth_type]').on('change',function(){
				if(!proType_change){
					var alert = layer.confirm('分类修改后将要重新填写产品信息(包括属性)',{
		        				title:'确定修改分类?',
								btn:['确定','取消']
	        				},function(){
		        				proType_change = true;
			        			layer.close(alert);
			        			$('select[name=forth_type]').change();
		        			},function(){
			        			$("select[name=forth_type]").find("option[value="+forth_tid+"]").prop('selected','selected');
								layer.close(alert);
								return;
		        			});
	        	}
	        	
				if(!proType_change){
					return;
				}
				
				forth_tid = $(this).find('option:selected').val();
				fifth_tid = handle.handleSelectType(forth_tid,fifth_type,'fifth_type');
			});
			
			$('select[name=fifth_type]').on('change',function(){
				if(!proType_change){
					var alert = layer.confirm('分类修改后将要重新填写产品信息(包括属性)',{
		        				title:'确定修改分类?',
								btn:['确定','取消']
	        				},function(){
		        				proType_change = true;
			        			layer.close(alert);
			        			$('select[name=fifth_type]').change();
		        			},function(){
			        			$("select[name=fifth_type]").find("option[value="+fifth_tid+"]").prop('selected','selected');
								layer.close(alert);
								return;
		        			});
	        	}
	        	
				if(!proType_change){
					return;
				}
				
				fifth_tid = $(this).find('option:selected').val();
			});
            
            $(":radio").on('click',function(){
				if($(this).attr('name') == "can_testing"){
					if($(this).val() == 1){
						$('.quality_price').show();
						$('input[name=quality_testing]').show();
					} else {
						$('.quality_price').hide();
						$('input[name=quality_testing]').hide();
					}
				}
				if($(this).attr('name') == "can_guarantee"){
					if($(this).val() == 1){
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
			
            $('#savethis').on('click',function(){
                var name=$('input[name=name]').val();
                var proAlias = $.trim($('input[name=proAlias]').val());
				var EnglishName = $.trim($('input[name=EnglishName]').val());				
				var CASnumber = $.trim($('input[name=CASnumber]').val());
				var goods_code = $.trim($('input[name=goods_code]').val());
				var size = $.trim($('input[name=size]').val());
				var purity = $.trim($('input[name=purity]').val());
				
				var unit = $.trim($("select[name=unit] option:selected").text());
				var packing = $.trim($('input[name=packing]:checked').val());
				var is_harmful = $('input[name=is_harmful]:checked').val();
				var shelf_life = $.trim($("select[name=shelf_life] option:selected").text());
				var producer = $.trim($('input[name=producer]:checked').val());
				var freight_way = $("select[name=freight_way] option:selected").val();
				var fre_id = $("select[name=freight] option:selected").val();
				var valuation_unit = $("select[name=freight] option:selected").attr('valuation_unit');
				
				var store = $.trim($("select[name=store] option:selected").text());
				var traffic = $.trim($("select[name=traffic] option:selected").text());
                
                var intro=ue.getContent();
                var intro_mobile=uc.getContent();
                var brand_id=$('select[name=sel_brand]').val();
                var imageUrl = "";
                var video_url=$('input[name=video_url]').val();
                var video_img=$("#images").attr('src');
                var isUploadImg = false;
                var imgArray = $("img[name=uping]");
                
                var vipone=$("input[name=vipone]").val();
                var viptwo=$("input[name=viptwo]").val();
                var vipthree=$("input[name=vipthree]").val();
                var vipfour=$("input[name=vipfour]").val();
                
                var can_testing = $('input[name=can_testing]:checked').val();
				var quality_testing = $('input[name=quality_testing]').val();
				var can_guarantee = $('input[name=can_guarantee]:checked').val();
				var guarantee_1 = $('input[name=guarantee_1]').val();
				var guarantee_2 = $('input[name=guarantee_2]').val();
				var guarantee_5 = $('input[name=guarantee_5]').val();
				
                for(var i=0;i<imgArray.length;i++){
                    var image = imgArray[i];
                    if(image.src != "http://d27.ichuk.com/images/pc/upload.jpg" && image.src != ""){
                        isUploadImg = true;
                        if(imageUrl == ""){
                            imageUrl = image.src;
                        } else {
                            imageUrl = imageUrl + "," + image.src;
                        }
                    }
                }
                
                if(name.length<1){
                    layer.alert('请填写产品名称');
                    return;
                }
                if(!isUploadImg){
                    layer.alert("请添加产品图片",{offset:'200px'});
                    return;
                }
                
                var params={};
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
				
				if(ptid != product.ptid){
					params.ptid = ptid;
					params.first_tid = first_tid;
					params.second_tid = second_tid;
					params.third_tid = third_tid;
					params.forth_tid = forth_tid;
					params.fifth_tid = fifth_tid;
				}
				
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
					if(CASnumber != product.CASnumber){
						params.CASnumber = CASnumber;	
					}
					if(purity == ""){
						layer.alert("请填写产品含量",{offset:'200px'});
						return;
					}
					if(purity != product.purity){
						params.purity = purity;
					}
					
					if(proAlias != product.proAlias){
						params.proAlias = proAlias;	
					}
					if(is_harmful != product.is_harmful){
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
				
				if(productType == 1 || productType == 4){
					if(EnglishName != product.EnglishName){
						params.EnglishName = EnglishName;	
					}
				}
				if(productType != 2){
					if(shelf_life != product.shelf_life){
						params.shelf_life = shelf_life;	
					}
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

                var brand = $('#brand').combobox('getValue');
                if(brand == null || brand == ""){
                    layer.alert("品牌不能为空！",{offset:'200px'});
                    return;
                }
                
                params.pid=pid;
				params.brand = brand;    
                if(name != product.name){
	            	params.name = name;    
                }
                if(goods_code != product.goods_code){
	                params.goods_code = goods_code;
                }
                if(size != product.size){
	                params.size = size;
                }
                if(packing != product.packing){
	                params.packing = packing;
                }
                if(unit != product.unit){
	                params.unit = unit;	
                }
                if(imageUrl != product.images){
	            	params.images=imageUrl;    
                }
				if(producer != product.producer){
					params.producer = producer;
				}
				if(store != product.store){
					params.store = store;
				}
				if(traffic != product.traffic){
					params.traffic = traffic;
				}
                if(video_url != product.video_url){
	            	params.video_url = video_url;
                }
                if(video_img != product.video_img){
	            	params.video_img = video_img;
                }
                if(vipone != product.v1_discount){
	            	params.vipone = vipone;	
                }
                if(viptwo != product.v2_discount){
	            	params.viptwo = viptwo;
                }
                if(vipthree != product.v3_discount){
	            	params.vipthree=vipthree;	
                }
                if(vipfour != product.v4_discount){
	            	params.vipfour=vipfour;	
                }
                if(vipfour != product.vipfour){
	            	params.vipfour=vipfour;	
                }
                params.can_testing = can_testing;
            	params.quality_testing = quality_testing;
            	params.can_guarantee = can_guarantee;
				params.guarantee_1 = guarantee_1;
				params.guarantee_2 = guarantee_2;
				params.guarantee_5 = guarantee_5;
                
				if(intro != product.intro){
					params.intro = intro;
				}	
				if(intro_mobile != product.intro_mobile){
					params.intro_mobile = intro_mobile;
				}
				console.log(params);
                $.ajax({
                    type: "POST",
                    url: "/service/modifyproServ.html",
                    data: params,
                    dataType: "json",
                    success: function (data) {
                        if(data.ret==1){
	                        layer.alert(data.msg,{offset:'200px'},function(){
		                        if(ptid != product.ptid){
			                    	window.location.href = "/myshop/modifyproperty.html?pid=" + product['pid'];
		                        } else {
			                    	window.location.reload(true);       
		                        }
	                        });
                        }else{
	                        console.log(data);
                            layer.alert(data.msg,{offset:'200px'});
                        }
                    },
                    error: function (data) {
                        layer.alert('服务器错误,请稍后再试',{offset:'200px'});
                    }
                });
            })
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
    };

    $(function(){
	    handle.init();
    });
});