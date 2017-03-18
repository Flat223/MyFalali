define(function(require,exports){
	var area = require("area");
	var layer = require("layer/layer");
	var areaData = require("area_dat");
	var validation = require("validation");
	var fmt = require("commonFmt");
	layer.config({
        path:'/js/layer/'
    });
    require("map");
    require("json2");
	
	//	发货地址id;   发货时间;   运费类型，2自定义运费(默认),1卖家承担运费;
	var addressId = 0,time = 0,type = 2;
	// 	区域限售，0不支持，1支持;   计价方式，1按件数算运费(默认),2按重量，3按体积;
	var unit,areaLimit;
	//	计数器;   运送方式状态;   累加器;   运送地区map;
	var counters = {},options = {},accumulator = {},map = {};
	//	初始化参数
	var params = ['express','ems','mail'];
	var flag = 'flag';
	var paramNames = ['快递','EMS','平邮'];
	var unitName = ['件','重','体积'];
	//	省数据状态参数
	var provincesSt = [];
	//	上下文地址
	var context;
	var queryflag = 0;
	var freights;
	
	exports.handleCnt = {
		init:function(ctx){
			context = ctx;
			this.initOrClearParams();
			area.init("province","city","county");
			this.initAreaOptions();
			$("input[type=radio][name=type]").on("click",function(){
				var val = $(this).val();
				if(type == val){
					return;
				}
				exports.handleCnt.changeType(val);
			});
			$("input[type=radio][name=val_unit]").on("click",function(){
				var val = $(this).val();
				if(unit == val){
					return;
				}
				exports.handleCnt.changeUnit(val);
			});
			$("input[type=radio][name=area_limit]").on("click",function(){
				var val = parseInt($(this).val());
				if(areaLimit == val){
					return;
				}
				areaLimit = val;
			});
			$(".delivery_type").on("click",function(){
				var param = $(this).parents(".delivery").attr("ref-param");
				if($.inArray(param,params) < 0){
					return;
				}
				if($(this).prop("checked")){
					options[param] = 1;
					$(this).parent().siblings(".delivery_type_set").css("display","block");
				}else{
					options[param] = 0;
					$(this).parent().siblings(".delivery_type_set").css("display","none");
				}
			});
			$(document).on("click",".add_freight_list table tbody .freight_del a",function(){
				var $this = $(this);
				var param = $this.parents(".delivery").attr("ref-param");
				var key = $this.parents("tr").attr("ref_id");
				if($.inArray(param,params) < 0){
					return;
				}
				if(key == undefined){
					return;
				}
				if(counters[param] <= 1){
					counters[param] = 0;
					$(".delivery[ref-param="+param+"]").find(".add_freight_list").empty();
				}else{
					counters[param]--;
					$(this).parent().parent().remove();
				}
				map[param].removeByKey(key);
			});
			$(".add_spec a").on("click",function(){
				var param = $(this).parents(".delivery").attr("ref-param");
				if($.inArray(param,params) < 0){
					return;
				}
				accumulator[param]++;
				var idstr = param + "_" + accumulator[param];
				if(counters[param] <= 0){
					counters[param] = 1;
					$(".delivery[ref-param="+param+"]").find(".add_freight_list").html(exports.handleCnt.assembleTable(idstr))
				}else{
					counters[param]++;
					$(".delivery[ref-param="+param+"]").find(".add_freight_list tbody").append(exports.handleCnt.assembleTr(idstr));
				}
			});
			$(document).on("click",".add_freight_list table tbody .delivery_area_edit a",function(){
				var param = $(this).parents(".delivery").attr("ref-param");
				if($.inArray(param,params) < 0){
					return;
				}
				var key = $(this).parents("tr").attr("ref_id");
				if(key == undefined){
					return;
				}
				exports.handleCnt.editArea(param,key);
			});
			$(".btn_save").on("click",function(){
				exports.handleCnt.save();
			});
		},
		initOrClearParams:function(){
			unit = 1;
			areaLimit = 0;
			$("input[type=radio][name=val_unit][value="+unit+"]").prop("checked",true);
			$("input[type=radio][name=area_limit][value="+areaLimit+"]").prop("checked",true);
			this.clearDelivery();
		},
		clearDelivery:function(){
			for(var i in params){
				counters[params[i]] = 0;
				options[params[i]] = 0;
				accumulator[params[i]] = 0;
				map[params[i]] = new Map();
			}
			$(".delivery .delivery_type").prop("checked",false);
			$set = $(".delivery .delivery_type_set");
			$set.css("display","none");
			$set.find(".default").html(this.assembleDefault())
			$set.find(".add_freight_list").empty();
		},
		initAreaOptions:function(){
			var html = "";
			for(var i in areaData.provinces){
				provincesSt["p_"+areaData.provinces[i].id] = {};
				provincesSt["p_"+areaData.provinces[i].id].name = areaData.provinces[i].name;
				provincesSt["p_"+areaData.provinces[i].id].cityNum = 0;
				provincesSt["p_"+areaData.provinces[i].id].selectCityNum = 0;
				html += "<div class='area_province'>";
				html += "<span class='province_tit'><label>";
				html += "<input type='checkbox' class='chk_province' ref-val='"+areaData.provinces[i].id+"' ref-name='"+areaData.provinces[i].name+"' />";
				html += areaData.provinces[i].name+"<span class='city_num'></span></label><i></i></span>";
				html += "<div class='area_cities'>";
				for(var j in areaData.cities){
					if(areaData.cities[j].parent_id == areaData.provinces[i].id){
						html += "<label><input type='checkbox' class='chk_city' ref-val='"+areaData.cities[j].id+"'";
						html += " ref-name='"+areaData.cities[j].name+"' />"+areaData.cities[j].name+"</label>";
						provincesSt["p_"+areaData.provinces[i].id].cityNum++;
					}
				}
				html += "<div><input type='button' value='确定' /></div>";
				html += "</div>";
				html += "</div>";
			}
			$(".area_option").html(html);
			$(".area_option .area_province .province_tit i").on("click",function(){
				$province = $(this).parents(".area_province");
				if($province.hasClass("drop-down")){
					$province.removeClass("drop-down");
				}else{
					$province.addClass("drop-down").siblings().removeClass("drop-down");
				}
			});
			$(".area_option .area_province .province_tit input.chk_province").on("click",function(){
				var checkable = $(this).prop("checked");
				var count = 0;
				$(this).parents(".area_province").find("input.chk_city").each(function(){
					if(!$(this).prop("disabled")){
						$(this).prop("checked",checkable);
						count++;
					}
				});
				if(checkable){
					$(this).siblings(".city_num").text("("+count+")");
				}else{
					$(this).siblings(".city_num").empty();
				}
			});
			$(".area_cities input.chk_city").on("click",function(){
				var checkable = true;
				var count = 0;
				var $cities = $(this).parents(".area_cities");
				$cities.find("input.chk_city").each(function(){
					var $this = $(this);
					if(!$this.prop("disabled")){
						if(!$this.prop("checked")){
							checkable = false;
						}else{
							count++;
						}
					}
				});
				$cities.siblings(".province_tit").find("input.chk_province").prop("checked",checkable);
				if(count > 0){
					$cities.siblings(".province_tit").find(".city_num").text("("+count+")");
				}else{
					$cities.siblings(".province_tit").find(".city_num").empty();
				}
			});
			$(".area_cities input[type=button]").on("click",function(){
				$(this).parents(".area_province").removeClass("drop-down");
			});

		},
		editArea:function(param,key){
			if($.inArray(param,params) < 0){
				return;
			}
			var keys = map[param].keys();
			var cities = new Array();
			for(var i in keys){
				if(key == keys[i]){
					continue;
				}
				var value = map[param].get(keys[i]);
				if(value != null){
					var values = value.split(',');
					cities = cities.concat(values);
				}
			}
			var selectedCities = [];
			var selectedVal = map[param].get(key);
			if(selectedVal != null){
				selectedCities = selectedVal.split(',');	
			}
			$(".area_province .area_cities input.chk_city").each(function(){
				var $this = $(this);
				var val = $this.attr("ref-val");
				if(val == undefined){
					return true;
				}
				var provinceVal = $this.parents(".area_province").find("input.chk_province").attr("ref-val");
				if(provinceVal == undefined){
					return true;
				}
				for(var i in cities){
					if(cities[i] == val){
						$this.prop("disabled",true).parent().addClass("unavailable");
						provincesSt["p_"+provinceVal].selectCityNum++;
						break;
					}
				}
			});
			$(".area_province input.chk_province").each(function(){
				var $this = $(this);
				var val = $this.attr("ref-val");
				if(val == undefined){
					return true;
				}
				if(provincesSt["p_"+val] == undefined){
					return true;
				}
				if(provincesSt["p_"+val].selectCityNum >= provincesSt["p_"+val].cityNum){
					$this.prop("disabled",true).parent().addClass("unavailable");
				}
				provincesSt["p_"+val].selectCityNum = 0;
			});
			for(var i in selectedCities){
				$(".area_province .area_cities input.chk_city[ref-val="+selectedCities[i]+"]").click();
			}
			layer.open({
				type:1,
				move:false,
				title:'选择区域',
				content:$('.area_option'),
				area:['700px','300px'],
				btn:['确定','取消'],
				yes:function(index){
					var vals = new Array();
					var areaNames = new Array();
					$(".area_province").each(function(){
						var $province = $(this).find("input.chk_province");
						var id = $province.attr("ref-val");
						var name = $province.attr("ref-name");
						if(id == undefined || name == undefined || parseInt(id) <= 0){
							return true;
						}
						if(provincesSt["p_"+id] == undefined){
							return true;
						}
						var names = new Array();
						provincesSt["p_"+id].selectCityNum = 0;
						$(this).find(".area_cities input.chk_city").each(function(){
							var $this = $(this);
							if(!$this.prop("disabled") && $this.prop("checked")){
								var val = $this.attr("ref-val");
								var cityName = $this.attr("ref-name");
								if(val == undefined || cityName == undefined || parseInt(val) < 0){
									return true;
								}
								vals.push(val);
								names.push(cityName);
								provincesSt["p_"+id].selectCityNum++;
							}
						});
						if(provincesSt["p_"+id].selectCityNum >= provincesSt["p_"+id].cityNum){
							areaNames.push(name);
						}else{
							if(names.length > 0){
								areaNames.push(name+"("+names.join(',')+")");
							}
						}
						provincesSt["p_"+id].selectCityNum = 0;
					});
					if(vals.length > 0){
						map[param].put(key,vals.join(','));
						$("tr[ref_id="+key+"] .delivery_area").text(areaNames.join(','));
					}else{
						map[param].removeByKey(key);
						$("tr[ref_id="+key+"] .delivery_area").text("未添加地区");
					}
					layer.close(index);
				},
				end:function(){
					exports.handleCnt.clearArea();
				}
			});
		},
		clearArea:function(){
			$(".area_province input[type=checkbox]").prop("checked",false).prop("disabled",false);
			$(".area_province .city_num").empty();
			$(".area_province").removeClass("drop-down");
			$(".area_province .unavailable").removeClass("unavailable");
		},
		changeType:function(val){
			val = parseInt(val);
			if(val == 1){
				layer.confirm("选择\"卖家承担运费\"后，已设置的运费内容将移除，确定要这么做吗？",
				{closeBtn:0,offset:'250px',title:"更换运费类型"},
				function(index){
					type = val;
					$("#delivery_set").css("display","none");
					exports.handleCnt.initOrClearParams();
					layer.close(index);	
				},function(){
					$("input[type=radio][name=type][value=2]").prop("checked",true);
					layer.msg("已取消切换运费类型",{offset:'350px',time:1300});
				});
			}else{
				$("#delivery_set").css("display","block");
				type = val;
			}			
		},
		changeUnit:function(val){
			val = parseInt(val);
			layer.confirm("切换计价方式后，当前设置的运送方式的信息将被重置，确定继续么？",
			{closeBtn:0,offset:'250px',title:"更换计价方式"},
			function(index){
				unit = val;
				exports.handleCnt.clearDelivery();
				layer.close(index);	
			},function(){
				$("input[type=radio][name=val_unit][value="+unit+"]").prop("checked",true);
				layer.msg("已取消切换计价方式",{offset:'350px',time:1300});
			});
		},
		assembleTable:function(id){
			var str1,str2;
			if(unit == 1){
				str1 = "首件(件)";
				str2 = "续件(件)";
			}else if(unit == 2){
				str1 = "首重(kg)";
				str2 = "续重(kg)";
			}else{
				str1 = "首体积(m³)";
				str2 = "续体积(m³)";
			}
			var html = "";
			html += "<table align='center' border='1px'>";
			html += "<thead>";
			html += "<tr>";
			html += "<th width='50%' colspan='2'>运送到</th>";
			html += "<th width='10%'>"+str1+"</th>";
			html += "<th width='10%'>首费(元)</th>";
			html += "<th width='10%'>"+str2+"</th>";
			html += "<th width='10%'>续费(元)</th>";
			html += "<th width='10%'>操作</th>";
			html += "</tr>";
			html += "</thead>";
			html += "<tbody>";
			html += "<tr ref_id='"+id+"'>";
			html += "<td class='delivery_area'>未添加地区</td>";
			html += "<td width='5%'class='delivery_area_edit'><a href='javascript:void(0);'>编辑</a></td>";
			html += "<td><input type='text' maxlength='8' class='input_1'/></td>";
			html += "<td><input type='text' maxlength='8' class='input_2'/></td>";
			html += "<td><input type='text' maxlength='8' class='input_3'/></td>";
			html += "<td><input type='text' maxlength='8' class='input_4'/></td>";
			html += "<td class='freight_del'><a href='javascript:void(0);'>删除</a></td>";
			html += "</tr>";
			html += "</tbody>";
			html += "</table>";
			return html;
		},
		assembleTr:function(id){
			var html = "";
			html += "<tr ref_id='"+id+"'>";
			html += "<td class='delivery_area'>未添加地区</td>";
			html += "<td width='5%' class='delivery_area_edit'><a href='javascript:void(0);'>编辑</a></td>";
			html += "<td><input type='text' maxlength='8' class='input_1'/></td>";
			html += "<td><input type='text' maxlength='8' class='input_2'/></td>";
			html += "<td><input type='text' maxlength='8' class='input_3'/></td>";
			html += "<td><input type='text' maxlength='8' class='input_4'/></td>";
			html += "<td class='freight_del'><a href='javascript:void(0);'>删除</a></td>";
			html += "</tr>";
			return html;
		},
		assembleDefault:function(){
			var html = "";
			var str;
			if(unit == 1){
				str = "件";
			}else if(unit == 2){
				str = "kg";
			}else{
				str = "m³";
			}
			html += "<span>默认运费：<input type='text' maxlength='8' class='input_1'/>"+str+"内，";
			html += "<input type='text' maxlength='8' class='input_2'/>元，";
			html += "每增加<input type='text' maxlength='8' class='input_3'/>"+str+"，";
			html += "增加运费<input type='text' maxlength='8' class='input_4'/>元</span>";
			return html;	
		},
		save:function(){
			if(queryflag == 1){
				return;
			}
			var dataObj = {};
			var name = $.trim($("#freight_name").val());
			if(name == ""){
				showAlert("请填写模板名称");
				return false;
			}
			dataObj.name = name;
			var countyId = $(".county").val();
			if(isNaN(countyId) || parseInt(countyId) <= 0){
				showAlert("请选择发货地址");
				return false;
			}
			dataObj.countyId = parseInt(countyId);
			var deliveryTime = $("#delivery_time").val();
			if(isNaN(deliveryTime) || parseInt(deliveryTime) <= 0){
				showAlert("请选择发货时间");
				return false;
			}
			dataObj.deliveryTime = parseInt(deliveryTime);
			if(type == 2){
				dataObj.type = 2;
				if(unit < 1 || unit > 3){
					showAlert("计价方式参数错误");
					return false;
				}
				dataObj.unit = unit;
				if(areaLimit != 0 && areaLimit != 1){
					showAlert("区域限售参数错误");
					return false;
				}
				dataObj.areaLimit = areaLimit;
				var selectNum = 0;
				for(var i in params){
					var deliveryType = {};
					if(options[params[i]] == 1){
						selectNum++;
						deliveryType.selected = 1;
						var val1 = $.trim($(".delivery[ref-param="+params[i]+"] .default input.input_1").val());
						var val2 = $.trim($(".delivery[ref-param="+params[i]+"] .default input.input_2").val());
						var val3 = $.trim($(".delivery[ref-param="+params[i]+"] .default input.input_3").val());
						var val4 = $.trim($(".delivery[ref-param="+params[i]+"] .default input.input_4").val());
						if(!this.validateFreight(val1,val2,val3,val4,true,paramNames[i])){
							return false;
						}
						deliveryType.defaultVals = {};
						deliveryType.defaultVals.val1 = unit==1?parseInt(val1):fmt.toDecimal(parseFloat(val1),2);
						deliveryType.defaultVals.val2 = fmt.toDecimal(parseFloat(val2),2);
						deliveryType.defaultVals.val3 = unit==1?parseInt(val3):fmt.toDecimal(parseFloat(val3),2);
						deliveryType.defaultVals.val4 = fmt.toDecimal(parseFloat(val4),2);
						var deliveryOptions = new Array();
						var pass = true;
						$(".delivery[ref-param="+params[i]+"] .add_freight_list table tbody tr").each(function(){
							var deliveryOption = {};
							var $this = $(this);
							var refid = $this.attr("ref_id");
							if(refid == undefined){
								return true;
							}
							var cities = map[params[i]].get(refid);
							if(cities == undefined || cities == null){
								showAlert("有设置项未添加地区");
								pass = false;
								return false;
							}
							deliveryOption.cities = cities;
							var optVal1 = $.trim($this.find("input.input_1").val());
							var optVal2 = $.trim($this.find("input.input_2").val());
							var optVal3 = $.trim($this.find("input.input_3").val());
							var optVal4 = $.trim($this.find("input.input_4").val());
							if(!exports.handleCnt.validateFreight(optVal1,optVal2,optVal3,optVal4,false,paramNames[i])){
								pass = false;
								return false;
							}
							deliveryOption.val1 = unit==1?parseInt(optVal1):fmt.toDecimal(parseFloat(optVal1),2);
							deliveryOption.val2 = fmt.toDecimal(parseFloat(optVal2),2);
							deliveryOption.val3 = unit==1?parseInt(optVal3):fmt.toDecimal(parseFloat(optVal3),2);
							deliveryOption.val4 = fmt.toDecimal(parseFloat(optVal4),2);
							deliveryOptions.push(deliveryOption);
						});
						if(!pass){
							return false;
						}
						deliveryType.options = deliveryOptions;
					}else{
						deliveryType.selected = 0;
					}
					dataObj[params[i]] = JSON.stringify(deliveryType);
				}
				if(selectNum == 0){
					showAlert("请选择至少一种运送方式");
					return false;
				}	
			}else{
				dataObj.type = 1;
			}
			queryflag = 1;
			var index = layer.msg("保存中...",{time:0,shade:0.1,offset:'350px',icon:6});
			$.ajax({
				url:context+"/service/addFreightServ.html",
				data:dataObj,
				dataType:'json',
				type:'post',
				success:function(obj){
					console.log(obj);
				},
				error:function(obj){
					showAlert("抱歉，保存错误，请稍后再试");
					console.log(obj);
				},
				complete:function(){
					queryflag = 0;
					layer.close(index);
				}
			});
		},
		validateFreight:function(val1,val2,val3,val4,isDefault,deliveryName){
			if(val1 == "" || val1 == undefined){
				if(isDefault){
					showAlert(deliveryName+"的默认运费的首"+unitName[unit-1]+"不可为空！");
				}else{
					showAlert(deliveryName+"的指定地区的首"+unitName[unit-1]+"不可为空！");
				}
				return false;
			}
			if(unit == 1){
				if(!validation.isPositiveInt(val1)){
					if(isDefault){
						showAlert(deliveryName+"的默认运费的首"+unitName[unit-1]+"必须是大于0的整数！");
					}else{
						showAlert(deliveryName+"的指定地区的首"+unitName[unit-1]+"必须是大于0的整数！");
					}
					return false;
				}
			}else{
				if(!validation.isPostiveFloat(val1)){
					if(isDefault){
						showAlert(deliveryName+"的默认运费的首"+unitName[unit-1]+"必须是大于0的数字！");
					}else{
						showAlert(deliveryName+"的指定地区的首"+unitName[unit-1]+"必须是大于0的数字！");
					}
					return false;
				}
			}
			if(val2 == "" || val2 == undefined){
				if(isDefault){
					showAlert(deliveryName+"的默认运费的首费不可为空！");
				}else{
					showAlert(deliveryName+"的指定地区的首费不可为空！");
				}
				return false;
			}
			if(!validation.isNonnegativeFloat(val2)){
				if(isDefault){
					showAlert(deliveryName+"的默认运费的首费必须是大于或等于0的数字！");
				}else{
					showAlert(deliveryName+"的指定地区的首费必须是大于或等于0的数字！");
				}
				return false;
			}
			if(val3 == "" || val3 == undefined){
				if(isDefault){
					showAlert(deliveryName+"的默认运费的续"+unitName[unit-1]+"不可为空！");
				}else{
					showAlert(deliveryName+"的指定地区的续"+unitName[unit-1]+"不可为空！");
				}
				return false;
			}
			if(unit == 1){
				if(!validation.isPositiveInt(val3)){
					if(isDefault){
						showAlert(deliveryName+"的默认运费的续"+unitName[unit-1]+"必须是大于0的整数！");
					}else{
						showAlert(deliveryName+"的指定地区的续"+unitName[unit-1]+"必须是大于0的整数！");
					}
					return false;
				}
			}else{
				if(!validation.isPostiveFloat(val3)){
					if(isDefault){
						showAlert(deliveryName+"的默认运费的续"+unitName[unit-1]+"必须是大于0的数字！");
					}else{
						showAlert(deliveryName+"的指定地区的续"+unitName[unit-1]+"必须是大于0的数字！");
					}
					return false;
				}
			}
			if(val4 == "" || val4 == undefined){
				if(isDefault){
					showAlert(deliveryName+"的默认运费的续费不可为空！");
				}else{
					showAlert(deliveryName+"的指定地区的续费不可为空！");
				}
				return false;
			}
			if(!validation.isNonnegativeFloat(val4)){
				if(isDefault){
					showAlert(deliveryName+"的默认运费的续费必须是大于或等于0的数字！");
				}else{
					showAlert(deliveryName+"的指定地区的续费必须是大于或等于0的数字！");
				}
				return false;
			}
			return true;
		}
	};
	
	exports.handleList = {
		init:function(ctx){
			context = ctx;
			for(var i in areaData.provinces){
				provincesSt["p_"+areaData.provinces[i].id] = {};
				provincesSt["p_"+areaData.provinces[i].id].name = areaData.provinces[i].name;
				provincesSt["p_"+areaData.provinces[i].id].cityNum = 0;
				provincesSt["p_"+areaData.provinces[i].id].selectCityNum = 0;
				provincesSt["p_"+areaData.provinces[i].id].selectCities = [];
				for(var j in areaData.cities){
					if(areaData.cities[j].parent_id == areaData.provinces[i].id){
						provincesSt["p_"+areaData.provinces[i].id].cityNum++;
					}
				}
			}
			$(document).on("click",".freight .fhead a.fre_mod",function(){
				var refid = $(this).parents(".freight").attr("ref-id");
				showAlert(refid);
				
				
				
			});
			$(document).on("click",".freight .fhead a.fre_del",function(){
				var $parents = $(this).parents(".freight");
				var refid = $parents.attr("ref-id");
				var refname = $parents.attr("ref-name");
				if(refid == undefined || refname == undefined){
					return;
				}
				layer.confirm("确认要删除运费模板<span style='color:red;margin:0 5px;'>"+refname+"</span>吗？",
					{icon:3,title:"删除确认",offset:"250px"},
					function(index){
						layer.close(index);
						exports.handleList.delData(refid);
					});
			});
			
			
			
			
			
			
			
			this.fixContent();
		},
		fixContent:function(){
			$(document).clearQueue();
			var FUNC = [];
			var _this = this;
			FUNC.push(function(){
				_this.getData();
			});
			FUNC.push(function(){
				_this.fixData();
			});
			$(document).queue(FUNC);
		},
		getData:function(){
			if(queryflag == 1){
				return;
			}
			var _this = this;
			queryflag = 1;
			_this.showLoading();
			$.ajax({
				url:context+"/service/freights.html",
				dataType:'json',
				type:'post',
				success:function(obj){
					if(obj.ret == 1){
						freights = obj.freights;
						$(document).dequeue();
					}else{
						_this.showErr(obj.msg);
					}
				},
				error:function(obj){
					_this.showErr("抱歉，发生错误，请稍后再试");
				},
				complete:function(){
					queryflag = 0;
				}
			});
		},
		fixData:function(){
			if(freights == undefined){
				this.showErr("抱歉，发生错误，请稍后再试");
				$(document).dequeue();
				return;
			}
			if(freights.length == 0){
				this.showNodata();
				return;
			}
			var html = "";
			$.each(freights,function(idx,data){
				var subHtml = "<div class='freight' ref-id='"+data.fre_id+"' ref-name='"+data.name+"' >";
				subHtml += "<div class='fhead'><span class='tit'>"+data.name+"</span>";
				subHtml += "<div class='inf'><span>最后编辑时间:"+fmt.formatDate(data.uptime,'yyyy-MM-dd HH:ss') +"</span>";
				subHtml += "<span><a href='javascript:void(0);' class='fre_mod' >修改</a></span>";
				subHtml += "<span>|</span><span><a href='javascript:void(0);' class='fre_del' >删除</a></span></div></div>";
				subHtml += "<div class='fbody'><ul>";
				subHtml += "<li>运送方式</li>";
				subHtml += "<li>运送到</li>";
				var unitDt = tools.transferUnit(data.valuation_unit);
				subHtml += "<li>"+unitDt.str1+"</li>";
				subHtml += "<li>运费(元)</li>";
				subHtml += "<li>"+unitDt.str2+"</li>";
				subHtml += "<li>运费(元)</li>";
				subHtml += "</ul>";
				subHtml += "<div class='fri_dat'><table><tbody>";
				if(data.type == '2'){
					$.each(params,function(i,param){
						if(data[param+'_'+flag] == "1"){
							var dat = JSON.parse(data[param]);
							subHtml += "<tr>";
							subHtml += "<td>"+paramNames[i]+"</td>";
							subHtml += "<td>全国</td>";
							subHtml += "<td>"+(data.valuation_unit=='1'?dat.defaultVals.val1:fmt.completeNum(dat.defaultVals.val1,2))+"</td>";
							subHtml += "<td>"+fmt.formatNum(dat.defaultVals.val2,2)+"</td>";
							subHtml += "<td>"+(data.valuation_unit=='1'?dat.defaultVals.val3:fmt.completeNum(dat.defaultVals.val3,2))+"</td>";
							subHtml += "<td>"+fmt.formatNum(dat.defaultVals.val4,2)+"</td>";
							subHtml += "</tr>";
							$.each(dat.options,function(j,opt){
								subHtml += "<tr>";
								subHtml += "<td>"+paramNames[i]+"</td>";
								subHtml += "<td>"+tools.transferCities(opt.cities)+"</td>";
								subHtml += "<td>"+(data.valuation_unit==1?opt.val1:fmt.completeNum(opt.val1,2))+"</td>";
								subHtml += "<td>"+fmt.formatNum(opt.val2,2)+"</td>";
								subHtml += "<td>"+(data.valuation_unit==1?opt.val3:fmt.completeNum(opt.val3,2))+"</td>";
								subHtml += "<td>"+fmt.formatNum(opt.val4,2)+"</td>";
								subHtml += "</tr>";
							});	
						}			
					});
				}else{
					subHtml += "<tr><td colspan='6'>卖家承担运费</td></tr>";
				}
				subHtml += "</tbody></table></div></div></div>";
				html += subHtml;
			});
			$("#freights").html(html);
			$(document).dequeue();
		},
		showLoading:function(){
			var html = "<div class='loading'><img src='/images/pc/loading.gif' /><span>数据加载中...</span></div>";
			$("#freights").html(html);
		},
		showErr:function(msg){
			var html = "<div class='err'><img src='/images/pc/err.gif' /><span>"+msg+"</span></div>";
			$("#freights").html(html);
		},
		showNodata:function(){
			var html = "<div class='no-data'><span>还没有添加运费模板，点击<a href='/myshop/freight.html'>添加</a></span></div>";
			$("#freights").html(html);
		},
		delData:function(id){
			if(queryflag == 1){
				return;
			}
			if(isNaN(id)){
				return;
			}
			var _this = this;
			queryflag = 1;
			var index = layer.load(2);
			$.ajax({
				url:context+"/service/delFreightServ.html",
				dataType:'json',
				data:{fid:id},
				type:'post',
				success:function(obj){
					queryflag = 0;
					if(obj.ret == 1){
						layer.msg("删除成功",{offset:'250px'});
						_this.fixContent();
					}else{
						showAlert(obj.msg);
					}
				},
				error:function(obj){
					queryflag = 0;
					showAlert("抱歉，发生错误，请稍后再试");
				},
				complete:function(){
					layer.close(index);
				}
			});
		}
		
		
		
				
	};
	
	var tools = {
		transferUnit:function(vUnit){
			var str1,str2;
			if(vUnit == '1'){
				str1 = "首件(件)";
				str2 = "续件(件)";
			}else if(vUnit == '2'){
				str1 = "首重(kg)";
				str2 = "续重(kg)";
			}else{
				str1 = "首体积(m³)";
				str2 = "续体积(m³)";
			}
			var data = {};
			data.str1 = str1;
			data.str2 = str2;
			return data;
		},
		transferCities:function(cities){
			var cityids = cities.split(',');
			$.each(cityids,function(i,cityid){
				for(var j in areaData.cities){
					if(areaData.cities[j].id == cityid){
						provincesSt["p_"+areaData.cities[j].parent_id].selectCityNum++;
						provincesSt["p_"+areaData.cities[j].parent_id].selectCities.push(areaData.cities[j].name);
						return true;
					}
				}
			});
			var strs = [];
			for(var i in provincesSt){
				var st = provincesSt[i];
				if(st.selectCityNum > 0){
					var str = "";
					if(st.selectCityNum >= st.cityNum){
						str = st.name;
					}else{
						str = st.name+"("+st.selectCities.join(',')+")";
					}
					strs.push(str);
				}
				st.selectCityNum = 0;
				st.selectCities = [];	
			}
			return strs.join(',');
		}
		
		
		
		
		
		
		
		
	};
	
	
	
	
	
	
	
	
	var showAlert = function(msg){
		layer.alert(msg,{offset:'250px',title:"提示"});
	};
	

	
	
	
	
	
	
});