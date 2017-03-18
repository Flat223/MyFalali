define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var pro_status = $('input[name=pro_status]').val();
	
	var handle = {
		init:function(){
			$("span[name=nameinfo]").on("click",function(){
				var key = $.trim($("input[name=product]").val());
				if(key.length < 1){
					layer.alert("请输入搜索信息",{offset:'200px'});
					return;
				}
				
				var str = "";
				var sta = handle.getQueryString('sta');
				if(sta){
					str = "&sta="+sta;
				}
				window.location.href='../myshop/managerProduct.html?name='+key+str;
			})
			$("input[name=product]").keydown(function() {
				if (event.keyCode == "13") {//keyCode=13是回车键
					$('span[name=nameinfo]').click();
				}
			});
			
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				if(page == ""){
					return;
				}
				var baseurl = $('input[name=baseurl]').val();
				window.location.href = baseurl+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			
			$('select[name=proStatus]').on('change',function(){
				var status = $(this).find("option:selected").val();
				
				var str = "";
				var name = handle.getQueryString('name');
				if(name){
					str = "&name="+name;
				}
				window.location.href = "/myshop/managerProduct.html?sta=" + status + str;
			});
			
			$('.selectAll').on('click',function(){
				var checked = $(this).prop('checked');
				$('input[type=checkbox]').each(function(){
					$(this).prop('checked',checked);
				});
			});
			
			$("button[name=deleteproduct]").on("click",function(){
				var pid=$(this).attr('pid');
				var alert = layer.confirm("确定删除该产品?", {
							title:"温馨提示",
							btn: ['确认','取消'] 
						}, function(){
							layer.close(alert);
							handle.deletePro(pid);
						});
			});
			$('.delete_selPro').on('click',function(){
				var $seleSingle = $('input[name=seleSingle]');
				var arr = new Array();
				$seleSingle.each(function(){
					if($(this).is(':checked')){
						arr.push($(this).attr('pid'));
					}
				});
				var pids = arr.join(',');
				if(pids == ""){
					layer.alert('请先选择产品');
					return;
				}
				
				var alert = layer.confirm("确定删除所选产品吗?",{
							title:"温馨提示",
							btn: ['确认','取消'] 
						}, function(){
							layer.close(alert);
							handle.deletePro(pids);
						});
			});
			
			
			$("button[name=update_pro]").on("click", function(){
				var complete=$(this).attr("complete");
				if(complete == 0 && pro_status == 2){
					layer.alert("该产品属性信息尚未完善,请完善后上架");
					return;
				}
				var title = pro_status == 1 ? "下架" : "上架";
				var pid=$(this).attr("pid");
				var alert = layer.confirm("确定"+ title +"该产品吗?", {
						title:"产品" + title,
						btn: ['确认','取消'] 
					}, function(){
						layer.close(alert);
						handle.updateProStatus(pid);
					});
			});
			$('.update_selPro').on('click',function(){
				var $seleSingle = $('input[name=seleSingle]');
				var arr = new Array();
				var flag = true;
				$seleSingle.each(function(){
					if($(this).is(':checked')){
						if(pro_status == 2){
							var complete = $(this).attr('complete');
							if(complete == 0){
								layer.alert("产品 '"+$(this).attr('proname')+"' 属性信息尚未完善,请完善后上架",{area:'300px'});
								flag = false;
								return false;
							}
						}
						arr.push($(this).attr('pid'));
					}
				});
				if(!flag){
					return;
				}
				var pids = arr.join(',');
				if(pids == ""){
					layer.alert('请先选择产品');
					return;
				}
				
				var title = pro_status == 1 ? "下架" : "上架";
				var alert = layer.confirm("确定"+title+'所选产品吗?',{
						title:"产品" + title,
						btn: ['确认','取消'] 
					}, function(){
						layer.close(alert);
						handle.updateProStatus(pids);
					});
			});
			
			
			$("tr[class=product]").mousemove(function(){
				$(this).addClass("productmove").siblings().removeClass("productmove");
			});

			$("a[class=intro]").click(function(){
				var intro=$(this).attr("message");
				if(intro==""){
					intro="没有产品说明";
				}
				layer.alert(intro,{"width":400,"height":400});
			})

			$("a[class=images]").on("click", function () {
				var images=$(this).attr("images");
				layer.open({
					type: 2,
					title: '产品图片',
					shadeClose: true,
					shade: 0.8,
					area: ['720px', '80%'],
					content: '../myshop/images.html?images='+images
				});
			});

			$(".skus_detail").on("click", function () {
				var complete=$(this).attr("complete");
				if(complete == 0){
					layer.alert("该产品属性信息尚未完善,请完善后查看");
					return;
				}
				
				var pid=$(this).attr("pid");
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/getProSkuOnModServ.html",
					data:{'pid':pid},
					success:function(data){
						if(data.ret == 1){
							var proptype = data.proptype;
							var pro_vals = data.pro_vals;
							var skus = data.skus;
							
							var html;
							if(proptype == 2){
								html = "<table class=\'skuInfo\'>\
										<thead>\
											<tr class=\'single_sku\'>\
												<th>价格</th>\
												<th>库存</th>\
												<th>库存预警</th>\
											</tr>\
										</thead>\
										<tbody>\
											<tr>\
												<td>" + skus.price +"</td>\
												<td>" + skus.inventory +"</td>\
												<td>" + skus.inventory_warn +"</td>\
											</tr>\
										</tbody>\
									   </table>";
							} else {
								html = "<table class=\'skuInfo\'><thead><tr clsss=\'sku_pro\'>";
								for(var i in pro_vals){
									html += "<th>" + pro_vals[i].name + "</th>";
								}
								html += "<th>价格</th>\
										<th>库存</th>\
										<th>库存预警</th>\
										</tr></thead><tbody>";
								for(var i in skus){
									html += "<tr>";
									var valArray = skus[i].name;
									for(var j in valArray){
										html += "<td>" + valArray[j] + "</td>";
									}
									html += "<td>" + skus[i].price +"</td>\
											<td>" + skus[i].inventory +"</td>\
											<td>" + skus[i].inventory_warn +"</td>\
											</tr>";
								}
								html += "</tbody></table>";
								$('.sku_pro th').each(function(){
									console.log($(this).text());
								});
							}
							layer.open({
					            type: 1,
					            title: "查看分类详情",
					            area: ['600px','50%'],
					            shade:0.1,
					            content: html,
					        });
	                    } else {
	                        layer.alert(data.msg);
	                    }				
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试');
					}
				});
			});

			$("button[name=modify]").on("click", function (){
				var status = $(this).attr('status');
				if(status == 1){
					layer.alert("该产品已上架,请下架后修改",{offset:'200px'});
					return;
				}
				
				var pid = $(this).attr('pid');
				var name = $(this).attr('pname');
				var alert = layer.confirm(name, {
						title:"修改产品信息",
						btn: ['修改基本信息','修改属性信息'] 
					}, function(){
						layer.close(alert);
						handle.modifyBasicPro(pid);
					}, function(){
						layer.close(alert);
						handle.modifyPropertyPro(pid);
					});
			});
		},
		
		getQueryString:function(name){
			var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
			var r = window.location.search.substr(1).match(reg);
			if(r != null){
				return unescape(r[2]);
			}   
			return null;
		},
		
		deletePro:function(pids){
			$.ajax({
				type: "POST",
				url: "/service/deleteProductServ.html",
				data: {"pids":pids},
				dataType: "json",
				success: function (data) {
					layer.alert(data.msg,function(){
						if(data.ret == 1){
							location.reload(true);
						}
					});
				},
				error: function (data) {
					layer.alert("error");
				}
			});
		},
		
		updateProStatus:function(pids){
			$.ajax({
				type: "POST",
				url: '/service/updateProStatusServ.html',
				data: {"pids":pids,"status":pro_status},
				dataType: "json",
				success: function (data) {
					layer.alert(data.msg,function(){
						if(data.ret == 1){
							location.reload(true);
						}
					});
				},
				error: function (data) {
					layer.alert("服务器错误,请稍后再试");
				}
			});
		},
		
		modifyBasicPro:function(pid){
				layer.open({
					type: 2,
					title: '修改商品基本信息',
					shadeClose: true,
					shade: 0.8,
					area: ['1300px', '100%'],
					content: '../myshop/modifyproduct.html?pid=' + pid,
				});
		},
		
		modifyPropertyPro:function(pid){
				layer.open({
					type: 2,
					title: '修改商品属性信息',
					shadeClose: true,
					shade: 0.8,
					area: ['1300px', '100%'],
					content: '../myshop/modifyproperty.html?pid=' + pid,
				});
		}
	};


	$(function(){
		handle.init();
	});
});