define(function(require){
	var $ = require("jquery");
	require("common");
	require("map");
	require("json2");
	var dialog = require("dialog");
	var pid = $("#pid").val();
	var ptid = $("#ptid").val();
	var proptype = $("#proptype").val();
	var propCount = $("#propCount").val();
	
	var maxPropVals = 20;
// 	var maxPropSize = 3;
	var counter = 0;
	var props = new Map();
	var skus;
	var storeProps;
	var flagop = 0;
	
	var handlePro = {
		addPropVal:function(){
			if($(".propval").length >= maxPropVals){
				dialog.info({msg:"属性内容最多只能有"+maxPropVals+"条"});
				return;
			}
			var html = "<tr class='propval'>";
			html += "<td><input type='input' class='form-control prop_val' placeholder='填写属性内容' maxlength='100'></td>";
			html += "<td><button type='button' class='btn btn-primary propval_del' style='padding:5px 25px;'>删除</button></td>";
			html += "</tr>";
			$(".propvals_body").append(html);
			$(".propval:last").find("input").focus();
		},
		addProp:function(pro_id,name){
/*
			if(props.size() >= maxPropSize){
				dialog.info({msg:"最多只能添加"+maxPropSize+"种产品属性"});
				return;
			}
*/
			
			var isExit = false;
			var propId;
			var prop;
			var addedPropsKeys = props.keys();
			var addedProps = props.values();
			for(var i in addedProps){
				var addProp = addedProps[i];
				if(addProp != false && addProp != null && addProp.propertyid == pro_id){
					isExit = true;
					propId = addedPropsKeys[i];
					prop = addProp;
					break;
				}
			}
			if(isExit){
				$("#prop_stat").val("mod");
				$("#propid").val(propId);
				var arr = prop.propvals.split(",");
				var html = "";
				for(var i in arr){
					html += "<tr class='propval'>";
					html += "<td><input type='input' class='form-control prop_val' placeholder='填写属性内容' maxlength='100' value='"+arr[i]+"' ></td>";
					html += "<td><button type='button' class='btn btn-primary propval_del' style='padding:5px 25px;'>删除</button></td>";
					html += "</tr>";
				}
				$(".propvals_body").append(html);
			} else {
				$("#prop_stat").val("add");	
				var html = "<tr class='propval'>";
				html += "<td><input type='input' id='inputfirst' class='form-control prop_val' placeholder='填写属性内容' maxlength='100'></td>";
				html += "<td><button type='button' class='btn btn-primary propval_del' style='padding:5px 25px;'>删除</button></td>";
				html += "</tr>";
				$(".propvals_body").append(html);
// 				document.getElementById('inputfirst').focus();
			}
			
			$("#cover").show();
			$(".prop_op").show();
			$('input[name=pro_name]').val(name);
			$('input[name=pro_name]').attr('pro_id',pro_id);
		},
		modProp:function(id){
			var prop = props.get(id);
			if(prop == false || prop == null){
				dialog.info({msg:"不存在此产品属性"});
				return;
			}
			
			$("#cover").show();
			$(".prop_op").show();
			$("#prop_stat").val("mod");
			$("#propid").val(id);
			$("input[name=pro_name]").val(prop.name);
			$('input[name=pro_name]').attr('pro_id',prop.propertyid);
			if(prop.propvals != ""){
				var arr = prop.propvals.split(",");
				var html = "";
				for(var i in arr){
					html += "<tr class='propval'>";
					html += "<td><input type='input' class='form-control prop_val' placeholder='填写属性内容' maxlength='100' value='"+arr[i]+"' ></td>";
					html += "<td><button type='button' class='btn btn-primary propval_del' style='padding:5px 25px;'>删除</button></td>";
					html += "</tr>";
				}
				$(".propvals_body").append(html);
			}
		},
		confirmProp:function(){
		  	var	propname = $('input[name=pro_name]').val();
		  	var	propertyid = $('input[name=pro_name]').attr('pro_id');
			var st = $("#prop_stat").val();
			if(st != "add" && st != "mod"){
				dialog.info({msg:"参数有错误"});
				return;
			}
			var $propval = $(".propval");
			var len = $propval.length;
			if(len == 0){
				dialog.info({msg:"请添加属性内容"});
				return;
			}
			if(len >= maxPropVals){
				dialog.info({msg:"属性内容最多只能有"+maxPropVals+"条"});
				return;
			}
			if(st == "add"){
				var addedProps = props.values();
				for(var i in addedProps){
					if(addedProps[i].name == propname){
						dialog.info({msg:"属性名称已经存在了"});
						return;
					}
				}
			}else{
				var id = $("#propid").val();
				if(id == ""){
					dialog.info({msg:"参数有错误"});
					return;
				}
				var addedPropsKeys = props.keys();
				for(var i in addedPropsKeys){
					var prop = props.get(addedPropsKeys[i]);
					if(prop.name == propname && addedPropsKeys[i] != id){
						dialog.info({msg:"属性名称已经存在了"});
						return;
					}
				}
				var mProp = props.get(id);
				if(mProp == false || mProp == null){
					dialog.info({msg:"产品数据有错误，请刷新后重新填写"});
					return;
				}
			}
			var arr = new Array();
			var flag = true;
			$propval.each(function(){
				var propval = $.trim($(this).find("input.prop_val").val());
				if(propval == ""){
					dialog.info({msg:"属性内容名称存在空值"});
					flag = false;
					return false;
				}
				if($.inArray(propval,arr) >= 0){
					dialog.info({msg:"属性内容名称存在重复"});
					flag = false;
					return false;
				}
				arr.push(propval);
			});
			if(!flag){
				return;
			}
			var prop = {};
			prop.propertyid = propertyid;
			prop.name = propname;
			prop.propvals = arr.join(",");
			
			if(st == "add"){
				counter++;
				props.put("prop"+counter,prop);
				var html = "<tr class='prop' data='prop"+counter+"'><td>"+prop.name+"</td><td>"+prop.propvals+"</td>";
				html += "<td><button type='button' class='btn btn-primary prop_mod' style='padding:5px 25px;margin:0 5px;'>修改</button>";
				html += "<button type='button' class='btn btn-primary prop_del' style='padding:5px 25px;margin:0 5px;'>删除</button></td></tr>";
				$(".prop_body").append(html);
			}else{
				var id = $("#propid").val();
				props.put(id,prop);
				var tr = $(".prop[data='"+id+"']");
				tr.find("td:eq(0)").text(prop.name);
				tr.find("td:eq(1)").text(prop.propvals);
			}
			
			this.cancelProp();
		},
		cancelProp:function(){
			$("#cover").hide();
			$(".prop_op").hide();
			$("#propid").val("");
			$("#prop_stat").val("");
			$("input[name=pro_name]").val("");
			$(".propvals_body").html("");
		},
		delProp:function(id){
			props.removeByKey(id);
		},
		saveProps:function(){
			if(flagop == 1){
				return;
			}
			if(props.size() == 0){
				dialog.info({msg:"请添加产品属性"});
				return;
			}
			if(props.size() < propCount){
				dialog.info({msg:"属性内容添加不完整,请将所有属性都添加内容"});
				return;
			}
/*
			if(props.size() > maxPropSize){
				dialog.info({msg:"最多只能添加"+maxPropSize+"种产品属性"});
				return;
			}
*/
			var addedProps = props.values();
			var propsjson = JSON.stringify(addedProps);
			
			flagop = 1;
			
			var params = {};
			params.pid = pid;
			params.props = propsjson;
			console.log(params);			
			$.ajax({
				url:"/service/savePropsServ.html",
				type:"post",
				dataType:"json",
				data:params,
				success:function(data){
					flagop = 0;
					var dataobj = eval(data);
					if(dataobj.ret == 1){
						storeProps = dataobj.props;
						handlePro.fixProps(storeProps);
					}else{
						dialog.info({msg:data.msg});
					}
				},
				error:function(){
					flagop = 0;
					dialog.info({msg:"服务器错误,请稍后再试"});
				}
			});	
		},
		fixProps:function(queryProps){
			skus = this.combinationArr(queryProps);
			
			if(queryProps.length > 3){
				var width = parseFloat($('.sku_tit').parent().css('width'));
				width = width + parseFloat((queryProps.length - 3)*60.0)+'px';
				$('.sku_tit').parent().css('width',width);
			}
			
			var headhtml = "<tr role='row'>";
			for(var i in queryProps){
				headhtml += "<th class='col-sm-1'>"+queryProps[i].name+"</th>";
			}
			headhtml += "<th class='col-sm-2'>价格</th>";
			headhtml += "<th class='col-sm-2'>库存</th>";
			headhtml += "<th class='col-sm-2'>库存预警</th>";
			headhtml += "<th class='col-sm-3'>操作</th>";
			headhtml += "</tr>";
			$(".sku_tit").html(headhtml);
			
			var newSkuids = [];
			var restSkuids = [];
			for(var i in skus){
				skus[i].vals = this.filterArrAsc(skus[i].vals);
				skus[i].skuid = skus[i].vals.join(",");
				skus[i].status = 1;
				newSkuids.push(skus[i].skuid);
			}
			$(".sku_body tr").each(function(){
				var data = $(this).attr("data");
				if($.inArray(data,newSkuids) > -1){
					restSkuids.push(data);
				}else{
					$(this).remove();
				}
			});
			var bodyhtml = "";
			for(var i in skus){
				if($.inArray(skus[i].skuid,restSkuids) == -1){
					bodyhtml += "<tr role='row' data='"+skus[i].skuid+"' >";
					for(var j in skus[i].name){
						bodyhtml += "<td>"+skus[i].name[j]+"</td>";
					}
					bodyhtml += "<td><input type='text' class='form-control prop-price' maxlength='8' placeholder='价格' /></td>";
					bodyhtml += "<td><input type='text' class='form-control prop-inventory' maxlength='10' placeholder='库存' /></td>";
					bodyhtml += "<td><input type='text' class='form-control prop-inventory-warn' maxlength='10' placeholder='库存预警' /></td>";
					bodyhtml += "<td><button type='button' class='btn btn-primary sku_del' style='padding:5px 25px;'>删除</button></td>";
					bodyhtml += "</tr>";
				}
			}
			$(".sku_body").append(bodyhtml);
			$(".sku_add_ui").show();
		},
		filterArrAsc:function(arr){
			arr.sort(function(a,b){
				return a-b;
			});
			return arr;
		},
		combinationArr:function(arr){
			var newArr = [];
			var tempObj = {};
			tempObj.name = [];
			tempObj.vals = [];
			this.combinationArrWithParams(arr,newArr,0,tempObj);
			return newArr;
		},
		combinationArrWithParams:function(arr,newArr,index,tempObj){
			var size = arr.length;
			if(size > index){
				var newIndex = index + 1;
				for(var i=0;i<arr[index].vals.length;i++){
					var vals = arr[index].vals;
					tempObj.name[index] = vals[i].name;
					tempObj.vals[index] = vals[i].id;
					this.combinationArrWithParams(arr,newArr,newIndex,tempObj);
				}
			}else{
				var tempArr2 = {};
				tempArr2.name = tempObj.name.slice(0);
				tempArr2.vals = tempObj.vals.slice(0);
				newArr.push(tempArr2);
			}
		},
		showAddSkuUI:function(){
			if(storeProps == undefined){
				return;
			}
			$(".cover").show();
			$(".sku_op").show();
			var html = "";
			for(var i in storeProps){
				html += "<div class='form-group'>";
				html += "<label class='col-sm-3 control-label'>"+storeProps[i].name+"</label>";
				html += "<div class='col-sm-7'>";
				html += "<select class='form-control' id='prop"+i+"'>";
				for(var j in storeProps[i].vals){
					var vals = storeProps[i].vals;
					html += "<option value='"+vals[j].id+"'>"+vals[j].name+"</option>";
				}
				html += "</select>";
				html += "</div></div>";
			}
			$("#sku_op_body").html(html);
		},
		hideAddSkuUI:function(){
			$(".cover").hide();
			$(".sku_op").hide();
			$("#sku_op_body").html("");
		},
		addSku:function(){
			var len = storeProps.length;
			if(len <= 0){
				dialog.info({msg:"数据有错误"});
				return;
			}
			var arr_id = [];
			var arr_name = [];
			for(var i=0;i<len;i++){
				var id = $("#prop"+i).val();
				var name = $("#prop"+i+" option:selected").text();
				if(id == undefined || id <= 0 || name == undefined || name == ""){
					dialog.info({msg:"数据有错误"});
					return;
				}
				arr_id.push(id);
				arr_name.push(name);
			}
			arr_id = this.filterArrAsc(arr_id);
			var skuid = arr_id.join(",");
			var exits = 0;
			for(var i in skus){
				if(skus[i].skuid == skuid){
					exits = 1;
					if(skus[i].status == 1){
						dialog.info({msg:"此关联属性已经存在了"});
						return;
					}
					skus[i].status = 1;
				}
			}
			if(exits == 0){
				dialog.info({msg:"数据有错误"});
				return;
			}
			var html = "<tr role='row' data='"+skuid+"'>";
			for(var i in arr_name){
				html += "<td>"+arr_name[i]+"</td>";
			}
			html += "<td><input type='text' class='form-control prop-price' maxlength='8' placeholder='价格' /></td>";
			html += "<td><input type='text' class='form-control prop-inventory' maxlength='10' placeholder='库存' /></td>";
			html += "<td><input type='text' class='form-control prop-inventory-warn' maxlength='10' placeholder='库存预警' /></td>";
			html += "<td><button type='button' class='btn btn-primary sku_del' style='padding:5px 25px;'>删除</button></td>";
			html += "</tr>";
			$(".sku_body").append(html);
			this.hideAddSkuUI();
		},
		toggleProProps:function(val){
			if(val == 1){
				$(".pro_prop1").show();
				$(".pro_prop2").hide();
				if(storeProps != undefined && storeProps.length > 0){
					$(".sku_add_ui").show();
				}
				proptype = 1;
			}else if(val == 2){
				$(".pro_prop1").hide();
				$(".pro_prop2").show();
				$(".sku_add_ui").hide();
				proptype = 2;
			}
		},
		saveProPropInfo:function(){			
			if(flagop == 1){
				return;
			}
			if(proptype != 1 && proptype != 2){
				dialog.info({msg:"参数有错误，请刷新重试"});
				return;
			}
			var data = {};
			data.proptype = proptype;
			data.ptid = ptid;
			if(proptype == 1){
				var upSkus = [];
				if(skus == undefined || skus.length == 0){
					dialog.info({msg:"请添加属性关联"});
					return;
				}
				for(var i in skus){
					if(skus[i].status == 1){
						var tr = $(".sku_body tr[data='"+skus[i].skuid+"']");
						if(tr.length == 0){
							dialog.info({msg:"参数有错误，请刷新重试"});
							return;
						}
						var price = $.trim(tr.find(".prop-price").val());
						var inventory = $.trim(tr.find(".prop-inventory").val());
						var inventoryWarn = $.trim(tr.find(".prop-inventory-warn").val());
						if(price == ""){
							dialog.info({msg:"属性关联价格不能为空"});
							return;
						}
						if(isNaN(price) || price <= 0){
							dialog.info({msg:"属性关联价格必须为大于0的数字"});
							return;
						}
						if(inventory == ""){
							dialog.info({msg:"属性关联库存不能为空"});
							return;
						}
						if(!/^[1-9][0-9]*$/.test(inventory)){
							dialog.info({msg:"属性关联库存必须为大于0的整数"});
							return;
						}
						if(inventoryWarn == ""){
							dialog.info({msg:"属性关联库存预警不能为空"});
							return;
						}
						if(!/^[1-9][0-9]*$/.test(inventoryWarn)){
							dialog.info({msg:"属性关联库存预警必须为大于0的整数"});
							return;
						}
						var sku = {};
						sku.skuid = skus[i].skuid;
						sku.price = price;
						sku.inventory = inventory;
						sku.inventoryWarn = inventoryWarn;
						upSkus.push(sku);
					}
				}
				if(upSkus.length == 0){
					dialog.info({msg:"请添加属性关联"});
					return;
				}
				data.skus = JSON.stringify(upSkus);
			}else{
				var price = $.trim($("#pro_price").val());
				var inventory = $.trim($("#pro_inventory").val());
				var inventoryWarn = $.trim($("#pro_inventory_warn").val());
			
				if(price == ""){
					dialog.info({msg:"产品价格不能为空"});
					return;
				}
				if(isNaN(price) || price <= 0){
					dialog.info({msg:"产品价格必须为大于0的数字"});
					return;
				}
				if(inventory == ""){
					dialog.info({msg:"产品库存不能为空"});
					return;
				}
				if(!/^[1-9][0-9]*$/.test(inventory)){
					dialog.info({msg:"产品库存必须为大于0的整数"});
					return;
				}
				if(inventoryWarn == ""){
					dialog.info({msg:"产品库存预警不能为空"});
					return;
				}
				if(!/^[1-9][0-9]*$/.test(inventoryWarn)){
					dialog.info({msg:"产品库存预警必须为大于0的整数"});
					return;
				}
				data.price = price;
				data.inventory = inventory;
				data.inventoryWarn = inventoryWarn;
			}
			data.pid = pid;
			flagop = 1;
			console.log(data);
			$.ajax({
				type:"post",
				dataType:"json",
				data:data,
				url:getHost()+"/service/saveProductPropInfoServ.html",
				success:function(data){
					flagop = 0;
					var dataobj = eval(data);
					if(dataobj.ret == 1){
						dialog.confirm({
							title:'提示',
							msg:'添加成功',
							needCancelBtn:true,
							confirmLabel:'继续添加',
							cancellabel:'查看产品',
							confirmCallback:function(){
								window.location.href = "/myshop/addProduct.html";
							},
							cancelCallback:function(){
								window.location.href = "/myshop/managerProduct.html?sta=2";
							}
						});
					}else{
						dialog.info({msg:dataobj.msg});
					}
				},
				error:function(data){
					flagop = 0;
					dialog.info({msg:"服务器错误,请稍后再试"});
				}
			});
		}
	};
	
	
	(function(){
		$("#addPropVal").on("click",function(){
			handlePro.addPropVal();
		});
		$(document).on("click",".propval_del",function(){
			$(this).parent().parent().remove();
		});
		$("#prop_cancel").on("click",function(){
			handlePro.cancelProp();
		});
		$(".prop_add").on("click",function(){
			var name = $(this).attr('pro_name');
			var pro_id = $(this).attr('pro_id');
			handlePro.addProp(pro_id,name);
		});
		$("#prop_ok").on("click",function(){
			handlePro.confirmProp();
		});
		$(document).on("click",".prop_mod",function(){
			var id = $(this).parent().parent().attr("data");
			handlePro.modProp(id);
		});
		$(document).on("click",".prop_del",function(){
			var tr = $(this).parent().parent();
			var id = tr.attr("data");
			handlePro.delProp(id);
			tr.remove();
		});
		$("#props_save").on("click",function(){
			handlePro.saveProps();
		});
		$(document).on("click",".sku_del",function(){
			var $tr = $(this).parent().parent();
			var data = $tr.attr("data");
			if(data != undefined && data != ""){
				for(var i in skus){
					if(skus[i].skuid == data){
						skus[i].status = 0;
					}
				}
			}
			$tr.remove();
		});
		$("#sku_add").on("click",function(){
			handlePro.showAddSkuUI();
		});
		$("#sku_cancel").on("click",function(){
			handlePro.hideAddSkuUI();
		});
		$("#sku_ok").on("click",function(){
			handlePro.addSku();
		});
		$(".pro_prop").on("change",function(){
			var val = $(this).val();			
			handlePro.toggleProProps(val);
		});
		$("#save").on("click",function(){
			handlePro.saveProPropInfo();
		});
	})();

});