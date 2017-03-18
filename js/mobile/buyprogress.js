define(function(require){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	require('json2');
	layer.config({
		path:'/js/layer/'
	});
	window.onload = function(){  
        var url = window.location.href;  
        var ps = url.split("#");  
        try{  
            if(ps[1] != 1){  
                url += "#1";  
            }else{  
                window.location = ps[0];  
            }  
        }catch(ex){  
            url += "#1";  
        }  
          
        window.location.replace(url);  
          
    };  
	var yn=false;
	var code=0;
	var layerindex;
    var couponprice=0,crashprice=0,totmoney = 0;
	var handle = {
		init:function(){
			$(".manage_address .radio input[type=radio]").click(function  () {
				var id=$(this).attr('value');
			    var oUrl = window.location.href.toString();
			    var re=eval('/('+ 'aid'+'=)([^&]*)/gi');
			    var nUrl = oUrl.replace(re,'aid'+'='+id);
			    window.location.href = nUrl;		
			});
			var gid=0;
			var cartids="";
			var type=GetQueryString('type');
			if(type==3){
				gid=GetQueryString('id');
			}else if(type==1){
				cartids=GetQueryString('id');
			}	
			var id=GetQueryString('aid');
			$(".address").each(function(){
				if($(this).attr('value')==id){
					$(this).attr("checked",true);
					$(this).parent().attr("style","border: 1px solid rgb(0, 204, 204);");
					$(this).parent().children('.right1').attr("style","cursor: pointer");
				}				
			});
			$(".del").click(function(){
				var id=$(this).parent().attr('value');
				deletet(id);
				$(this).parent().parent().empty();				
			});
			$('.tabs').click(function(){
				yn=true;
				layer.close();
			});
			$(".freight p input[type=radio]").click(function  () {
				$(".payinfo .found").show();
				$(".freight p").hide();
				$(".payinfo").css("margin-top","20px");
			});
			var skus=Array();
			
			$(".commodity_inof").each(function(){
				skus.push($(this).attr('value'));
			});
			$("span.right1").click(function(){
				window.location.href="/user/editAddress.html?aid="+$(this).attr('value')+"&gb=1";
			});
			$("span.right").click(function(){
				window.location.href="/user/address.html?gb=1";
			});
			$(".tabs").click(function(){
				var intype=$('.tab-this').attr('value');
				var inputva="";
				var typeva="";
				if(intype==1){
					inputva=$('.input-this').children().val();
					typeva = $(".item-r li.li-this").html();
					if(inputva==""){
						layer.alert('请选择发票抬头');
						return;
					}
				}else if(intype==2){
					inputva=$('.input-this1').children().val();
					typeva = $(".item-r li.li-this1").html();
					if(inputva==""){
						layer.alert('请选择发票抬头');
						return;
					}
					var mobile=$("#emobile").val();
					if(mobile==""){
						layer.alert('请填写手机号');
						return;
					}
					var email=$("#eemail").val();
					checkEmail(email);
				}else if(intype==3){
					var params={};
					params.cname=$('#czanme').val();
					if(params.cname==""){
						layer.alert("请填写单位名称");
						return;
					}
					params.code=$('#zcode').val();
					if(params.code==""){
						layer.alert("请填写识别码");
						return;
					}
					params.relocation=$('#zrelocation').val();
					if(params.relocation==""){
						layer.alert("请填写注册地址");
						return;
					}
					params.remobile=$('#zremobile').val();
					if(params.remobile==""){
						layer.alert("请填写注册电话");
						return;
					}							
					params.bankname=$('#zbankname').val();
					if(params.bankname==""){
						layer.alert("请填写开户银行");
						return;
					}	
					params.bankaccount=$('#zbankaccount').val();
					if(params.bankaccount==""){
						layer.alert("请填写银行账户");
						return;
					}
					params.name1=$('#zname').val();
					if(params.name1==""){
						layer.alert("请填写收票人姓名");
						return;
					}
					params.mobile=$('#zmobile').val();
					if(params.mobile==""){
						layer.alert("请填写收票人电话");
						return;
					}
					inputva="个人";
					typeva="增值税";
					
				}
                var liTitle = $(".ul_invoice ul li#intitle").html(inputva);
                var liType =  $(".ul_invoice ul li#intype").html(typeva);

                
                layer.close(layerindex);
			});
			
			
			$('#submit').click(function(){
				var remarks=new Array();
				i=0;
				
				$(".extUser").each(function(){
					var value=$(this).find('.remarks').val();
					var sid=$(this).find('.remarks').attr('value');
					var etype=$(this).find
					var s={};
					s.remarks=value;
					s.sid=sid;
					var etype1=$(this).find('.etype').find("option:selected").attr('value');
					s.etype=etype1;
					remarks.push(s);
				});
				var ot=$("input[name=order]:checked").attr("value");
				if(ot>0){
					var aid=$("input[name=radio]:checked").attr("value");
					if(aid>0){
						if($("#invoice").prop("checked")){
							var intype=$('.tab-this').attr('value');
							if(intype==1){
								var params={};
								params.title1=$('.input-this').children().val();
								if(params.title1==""){
									layer.alert('请选择发票抬头');
								}
								params.type=$('.li-this').attr('value');
								params.ttype=intype;
								saveinvoice(params,skus,aid,remarks,ot,gid,cartids);
							}else if(intype==2){
								var params={};
								params.title1=$('.input-this1').children().val();
								params.type=$('.li-this1').attr('value');
								params.mobile=$('#emobile').val();
								params.email=$('#eemail').val();
								params.ttype=intype;
								saveinvoice(params,skus,aid,remarks,ot,gid,cartids);
							}else if(intype==3){
								var params={};
								params.cname=$('#czanme').val();
								if(params.cname==""){
									layer.alert("请填写单位名称");
									return;
								}
								params.code=$('#zcode').val();
								if(params.code==""){
									layer.alert("请填写识别码");
									return;
								}
								params.relocation=$('#zrelocation').val();
								if(params.relocation==""){
									layer.alert("请填写注册地址");
									return;
								}
								params.remobile=$('#zremobile').val();
								if(params.remobile==""){
									layer.alert("请填写注册电话");
									return;
								}							
								params.bankname=$('#zbankname').val();
								if(params.bankname==""){
									layer.alert("请填写开户银行");
									return;
								}	
								params.bankaccount=$('#zbankaccount').val();
								if(params.bankaccount==""){
									layer.alert("请填写银行账户");
									return;
								}
								params.name1=$('#zname').val();
								if(params.name1==""){
									layer.alert("请填写收票人姓名");
									return;
								}
								params.mobile=$('#zmobile').val();
								if(params.mobile==""){
									layer.alert("请填写收票人电话");
									return;
								}
								params.ttype=intype;
								saveinvoice(params,skus,aid,remarks,ot,gid,cartids);
							}
							
						}else{
							$('input:radio[name=radio1]:checked').each(function(){
								type=$(this).attr('value');
								if(type==1){
									var discount=$(this).parent().children('#discount1').find('option:selected').val();
									if(discount==""){
										discount=0;
									}
									submitOrder(type,discount,0,skus.toString(),aid,JSON.stringify(remarks),code,ot,gid,cartids);
								}else if(type==2){
									var cash=$(this).parent().children('#cash').find('option:selected').val();
									if(cash==""){
										cash=0;
									}
									submitOrder(type,cash,0,skus.toString(),aid,JSON.stringify(remarks),code,ot,gid,cartids);
								}else if(type==3){
									submitOrder(0,0,1,skus.toString(),aid,JSON.stringify(remarks),code,ot,gid,cartids);
								}else{
									submitOrder(0,0,0,skus.toString(),aid,JSON.stringify(remarks),code,ot,gid,cartids);
								}
							});
		
						}
					}else{
						layer.alert("请选择收货地址");
					}
				}else{
					layer.alert("请选择订单类型");

				}
			});
			totmoney=$(".pay_total").attr("value");

			// alert(totmoney);
			$("#discount1").change(function(){
                crashprice =couponprice = 0;
                $("#tmoney").text("￥"+(handlePrice()).toFixed(2));
				var val=$('input:radio[id="discount"]:checked').val();
                // alert(val);
				if(val==null){
				}else{
					var dis=$('#discount1 option:selected') .text();
                    couponprice  = Number(dis);
                    crashprice = 0;
					var money=totmoney-couponprice;
                    // alert(money);
					if(money<=0){
						money=0.01;
						layer.alert("使用券最低支付0.01元");
					}
					$("#tmoney").text("￥"+(money).toFixed(2));
				}
			});

			
			$(".tabq").click(function(){
				layer.close(layerindex);
			});
			
			$("#cash").change(function(){
                crashprice =couponprice = 0;
                $("#tmoney").text("￥"+(handlePrice()).toFixed(2));
				var val=$('input:radio[id="cashinput"]:checked').val();
				if(val==null){
				}else{
					var dis=$('#cash option:selected') .text();
                    crashprice  = Number(dis);
                    couponprice = 0;
					var money=handlePrice();
					if(money<=0){
						money=0.01;
						layer.alert("使用券最低支付0.01元");
					}
					$("#tmoney").text("￥"+(money).toFixed(2));
				}
			});
		}	
	};

	function handlePrice()
	{
        var money = totmoney - couponprice - crashprice;
        return money;
	}
    $(".type_item >.found>input.foundRaNO").on("click",function () {
    	// alert(1);
        crashprice =couponprice = 0;
        $("#tmoney").text("￥"+(handlePrice()).toFixed(2));
    })

	
	function saveinvoice(params,skus,aid,remarks,ot,gid,cartids){
		$.ajax({
				type: "post",//数据提交的类型（post或者get）
		        url:"/service/SaveInvoiceServ.html",	           
		        dataType: "json",//返回的数据类型
				data:params,
				success:function(data){
					if(data.ret == 1){
						code=data.code;
						var type=0;
						$('input:radio[name=radio1]:checked').each(function(){
							type=$(this).attr('value');
							if(type==1){
								var discount=$(this).parent().children('#discount1').find('option:selected').val();
								submitOrder(type,discount,0,skus.toString(),aid,JSON.stringify(remarks),code,ot,gid,cartids);
							}else if(type==2){
								var cash=$(this).parent().children('#cash').find('option:selected').val();
								submitOrder(type,cash,0,skus.toString(),aid,JSON.stringify(remarks),code,ot,gid,cartids);
							}else if(type==3){
								submitOrder(0,0,1,skus.toString(),aid,JSON.stringify(remarks),code,ot,gid,cartids);
							}else{
								submitOrder(0,0,0,skus.toString(),aid,JSON.stringify(remarks),code,ot,gid,cartids);
							}
						});
					}else{
						layer.alert(data.msg,{offset:'200px'});
					}
				},
				error:function(data){
					layer.alert('服务器错误,请稍后再试',{offset:'200px'});
				}
		});
		
	};
	
	
	function saveTitle(title){
		$.ajax({
			type: "post",//数据提交的类型（post或者get）
		    url:"/service/SaveTitleServ.html",	           
		    dataType: "json",//返回的数据类型
			data:{'title':title},
			success:function(data){
				if(data.ret==1){

				}
			},
			error:function(data){
				layer.alert('服务器错误,请稍后再试',{offset:'200px'});
			}

		});
		
	};
	
	function deletet(id){
		$.ajax({
			type: "post",//数据提交的类型（post或者get）
	        url:"/service/DeleteInvoiceServ.html",	           
	        dataType: "json",//返回的数据类型
			data:{'rid':id},
			success:function(data){
				if(data.ret == 1){
					layer.alert('删除成功');
				}else{
					layer.alert(data.msg,{offset:'200px'});
				}
			},
			error:function(data){
				layer.alert('服务器错误,请稍后再试',{offset:'200px'});
			}
		});
	};
	
	function submitOrder(ctype,cid,fund,skus,addressid,remarks,code,ot,gid,cartids){
		$.ajax({
			type: "post",//数据提交的类型（post或者get）
	        url:"/service/SubmitOrderServ.html",	           
	        dataType: "json",//返回的数据类型
			data:{'ctype':ctype,'cid':cid,'fund':fund,'skus':skus,'addressid':addressid,'remarks':remarks,'code':code,'ordertype':ot,'gid':gid,'cartids':cartids},
			success:function(data){
				if(data.ret == 1){
					if(data.type!=1){
						var codes=data.ordercodes.toString();
						var id=data.addressid;
						window.location.href="/pay/confirmation.html?codes="+codes+"&id="+id+"&otype=1";
					}else{
						var codes=data.ordercodes.toString();
						window.location.href="/pay/confirmationtt.html?codes="+codes;
					}
				}else if(data.ret==2){
					window.location.href="/pay/success.html?ordercode="+data.code+"&type=1";
				}else{
					layer.alert(data.msg,{offset:'200px'});
				}
			},
			error:function(data){
				console.log(data);
				layer.alert('服务器错误,请稍后再试',{offset:'200px'});
			}
		});
		
	};
	function checkEmail(str){
		var re = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/
		if(re.test(str)){
    	}else{
        	layer.alert("邮箱格式不正确");
        	return ;
    	}
	}
	
	function GetQueryString(name){
	    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return  unescape(r[2]); return null;
	};
	$(function(){

		$(".invoice_this").on("click",function () {

			layerindex = layer.open({
				type: 1
				,title: '发票信息'
				,area: ['628px', '680px']
				,shade: 0.1
				,content: $(".invoice-wrapp")
			});
		});
		$(".tab-ul li").on("click",function () {
			$(this).addClass("tab-this").siblings().removeClass("tab-this");
			var $dangqian = $(".tab-con .tab-item").eq($(".tab-ul li").index(this));
			$dangqian.addClass("tab-show").siblings().removeClass("tab-show");
		})
		$(".item1 li").on("click",function () {
			$(this).addClass("li-this").siblings().removeClass("li-this");
		})
		$(".item2 li").on("click",function () {
			$(this).addClass("li-this1").siblings().removeClass("li-this1");
		})
		//新增发票

		//增值税发票
		//step1
		$(".item .tab-submit .tab-normal").on("click",function () {
			$(".item").hide();
			$(".form-con").hide();
			$(".form-item").show();
			$(".invoice-status li:nth-of-type(3)").addClass("curr");
		})
		//step2
		$(".form-item .tab-submit .tab-normal").on("click",function () {
			$(".form-con").hide();
			$(".resv").show();
			$(".invoice-status li:nth-of-type(5)").addClass("curr");
		})
		//step2 返回
		$(".form-item .tab-submit .tab-default").on("click",function () {
			$(".form-con").show();
			$(".form-item").hide();
			$(".item").show();
			$(".invoice-status li:nth-of-type(3)").removeClass("curr");
		})
		//step3
		$(".form-item .tab-submit .tab-normal").on("click",function () {
			$(".form-con").hide();
			$(".form-item").hide();
		})
		//step3 返回
		$(".resv .tab-submit .tab-default").on("click",function () {
			$(".form-con").hide();
			$(".resv").hide();
			$(".form-item").show();
			$(".invoice-status li:nth-of-type(5)").removeClass("curr");
		})
		//普通发票
		$(".add-invoice").on("click",function () {
			$(".normal-ul li").removeClass("input-this");
			$('.normal-ul ').append("<li><input id=\'addtitle\'/><i></i><div class=\'save\' id=\'save\'><span>保存</span></div></li>");
		})
		$(".normal-ul").on("click",'li',function () {
			// alert(1);
			$(this).addClass("input-this").siblings().removeClass("input-this");
		})
		$(".normal-ul").on("click","#save",function () {
			// alert(1);return;
			if (document.getElementById('addtitle').value == ""){
				layer.alert('请填写发票抬头');
				return;
			}
			saveTitle(document.getElementById('addtitle').value);
			$("#save span").hide();
			$("#save").addClass("edt-del");
			$("#save").append("<span class=\"del\">删除</span>");
		})
		//电子发票
		$(".add-input input").on("click",function () {
			$(".invoice-info").removeClass("input-this1");
			$(".add-input").addClass("input-this1")
		})
		$(".invoice-info input").on("click",function () {
			$(".add-input").removeClass("input-this1");
			$(".invoice-info").addClass("input-this1");
		})

	});
	$(".type_item>.found>.foundRa").on("click",function () {
		// alert(1)
		$(".chice-item input[value=3]").attr("checked","checked");
        $(".chice-item >input[value=4]").attr("disabled","disabled");
        $(".chice-item >input[value=4]").removeAttr("checked","checked");
    })
    $(".type_item >input").on("click",function () {
        $(".chice-item >input[value=4]").removeAttr("disabled","disabled");
    })
    $(".type_item >.found").on("click",".foundRaNO",function () {
        $(".chice-item >input[value=4]").removeAttr("disabled","disabled");
    })


	$(function(){
		handle.init();
	});
});
