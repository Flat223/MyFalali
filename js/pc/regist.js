define(function(require,exports,module){
	var $ = require('jquery');
	var layer = require('layer/layer.js');
	layer.config({
		path:'/js/layer/'
	});
	
	var totalTime = 60;
	var clickAble = true;
	var t;
	
	var openIndex;
	var tid = "";
	var subtid = "";
	var collegeTypes;
	var companySubTypes;
	
	exports.initReg = function(){
		handleReg.init();
	}
	exports.initIndentities = function(){
		handleIdent.init();	
	};
	exports.setCollegeTypes = function(types){
		collegeTypes = types;
	}
	exports.initIndustry = function(){
		handleIndustry.init();
	}
	exports.setIndentType = function(_tid,_subtid){
		tid = _tid;
		subtid = _subtid;
	}
	
	var handleReg = {
		init:function(){
			$(".getCode").on("click",function(){
			    if(!clickAble){
			        return;
			    } 
				var mobile = $.trim($("#mobile").val());
				if(mobile == ""){
					layer.alert('请填写手机号码',{offset:'200px'});
					return;
				}
			    var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
			    if(!reg.test(mobile)) {
				    layer.alert('请填写正确的手机号码',{offset:'200px'});
			        clickAble = true;
			        return;
			    }
			    clickAble = false;
			    handleReg.sendCode(mobile);
			});
			
			$(".regbtn").on("click",function(){
				var mobile = $.trim($("#mobile").val());
				var validcode = $.trim($("#validcode").val());
				var password = $("#psd").val();
				var confirmPsd = $("#confirm_psd").val();
				if(mobile == ""){
					layer.alert('请填写手机号码',{offset:'200px'});
					return;
				}
				if(validcode == ""){
					layer.alert('请填写验证码',{offset:'200px'});
					return;
				}
				if(password == ""){
					layer.alert('请填写密码',{offset:'200px'});
					return;
				}
				if(confirmPsd == ""){
					layer.alert('请确认密码',{offset:'200px'});
					return;
				}
				if(password != confirmPsd){
					layer.alert('两次密码输入不一致',{offset:'200px'});
					return;
				}
				if(password.length < 6){
					layer.alert('密码长度不低于6位',{offset:'200px'});
					return;
				}
				handleReg.registAccount(mobile,password,validcode);
			});
		},
		
		sendCode:function(mobile){
			var url = "http://www.ichuk.com/?api/sendsmsverifycode/e75ce5d42105d8e581327164f8e860/1";
		    $.ajax({
		        type: "GET",
		        url: url,
		        data:{"stage":"实验圈","mobile":mobile,"platform":"WEB","usage":"regist"},
		        dataType: "json",
		        success : function(data){
		            if(data.ret == 1){
		                t = setInterval(function(){
		                    totalTime--;
		                    $('.getCode').text(totalTime+"秒后重新获取");
		                    if(totalTime == 0)
		                    {
		                        $('.getCode').text("获取短信验证码");
		                        totalTime = 60;
		                        clickAble = true;
		                        clearTimeout(t);
		                    }
		                },1000);
		            } else {
			            layer.alert(data.msg,{offset:'200px'});
		                clickAble = true;
		            }
		        },
		        error:function(data){
			        layer.alert('服务器错误,请稍后再试',{offset:'200px'});
		        }
		    });
		},
		
		registAccount:function(mobile,password,validcode){
			var params = {};
			params.mobile = mobile;
			params.password = password;
			params.validcode = validcode;
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/registServ.html",
				data:params,
				success:function(data){
					if(data.ret == 0){
						layer.alert(data.msg,{offset:'200px'});
					} else {
						window.location.href = "/regist/stepIdentity.html?mid=" + data.mid;
					}
				},
				error:function(data){
					layer.alert('服务器错误,请稍后再试',{offset:'200px'});
				},
				complete:function(){
					
				}
			});
		}
	};
	
	var handleIdent = {
		init:function(){
			companySubTypes = [
				{'sub_type':'0','name':'公司注册','img':'/images/pc/company_reg.png'},
				{'sub_type':'1','name':'科研技术人员','img':'/images/pc/researcher.png'},
				{'sub_type':'2','name':'采购员','img':'/images/pc/buyer.png'}
			];
			var html = "";
			for(var i in companySubTypes){
				html += "<li ref-stid='"+companySubTypes[i].sub_type+"'>";
				html += "<dl><dt><img src='"+companySubTypes[i].img+"' /></dt>";
				html += "<dd>"+companySubTypes[i].name+"</dd>";
				html += "</dl></li>";
			}
			$(".company ul").prepend(html);
			$("#identities ul li").on("click",function(){
				var $this = $(this);
				var ref_tid = $this.attr("ref-tid");
				if(ref_tid == 1){
					handleIdent.showCollegeSub();
					return;
				}else if(ref_tid == 2){
					handleIdent.showCompanySub();
					return;
				}else if(ref_tid == tid){
					return;
				}
				tid = ref_tid;
				$this.find("dl i").addClass("selected");
				$this.siblings().find("dl i").removeClass("selected");
				$this.siblings().find("dd._sub").text("");
			});
			$(".company ul li").on("click",function(){
				var ref_stid = $(this).attr("ref-stid");
				if(ref_stid == undefined || parseInt(ref_stid) < 0){
					return;
				}
				tid = 2;
				subtid = parseInt(ref_stid);
				var name;
				for(var i in companySubTypes){
					if(companySubTypes[i].sub_type == subtid){
						name = companySubTypes[i].name;
						break;
					}
				}
				var $company = $("#identities ul li[ref-tid='2']");
				$company.find("dd._sub").text(name);
				$company.find("dl i").addClass("selected");
				$company.siblings().find("dl i").removeClass("selected");
				$company.siblings().find("dd._sub").text("");
				layer.close(openIndex);
			});
			$(".college ul li").on("click",function(){
				var ref_stid = $(this).attr("ref-stid");
				if(ref_stid == undefined || parseInt(ref_stid) < 0){
					return;
				}
				tid = 1;
				subtid = parseInt(ref_stid);
				var name;
				if(subtid == 0){
					name = "高校注册";
				}else{
					for(var i in collegeTypes){
						if(collegeTypes[i].sub_type == subtid){
							name = collegeTypes[i].name;
							break;
						}
					}
				}
				var $college = $("#identities ul li[ref-tid='1']");
				$college.find("dd._sub").text(name);
				$college.find("dl i").addClass("selected");
				$college.siblings().find("dl i").removeClass("selected");
				$college.siblings().find("dd._sub").text("");
				layer.close(openIndex);
			});
			$(".company ul li,.college ul li").on("mouseover",function(){
				$(this).addClass("cover");
			}).on("mouseout",function(){
				$(this).removeClass("cover");
			});
			$(".ident_next").on("click",function(){
				if(tid == ""){
			        layer.alert('请选择你的身份',{offset:'200px'});
			        return;
		        }
		        var mid = $('input[name=mid]').val();
				var params = {};
				params.mid = mid;
				params.tid = tid;
				params.subtid = subtid;
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/saveIdentOnRegistServ.html",
					data:params,
					success:function(data){
						if(data.ret == 0){
							layer.alert(data.msg,{offset:'200px'});
						} else {
							handleIdent.confirmIdent();
						}
					},
					error:function(data){
						layer.alert('服务器错误,请稍后再试',{offset:'200px'});
					},
					complete:function(){
						
					}
				});
				
			});
		},
		showCollegeSub:function(){
			openIndex = layer.open({
				type:1,
				content:$('.college'),
				title:'选择所属高校身份',
				area:['810px','300px'],
				offset:'200px',
				shadeClose:true
			});
		},
		showCompanySub:function(){
			openIndex = layer.open({
				type:1,
				content:$('.company'),
				title:'选择所属公司身份',
				area:['810px','300px'],
				offset:'200px',
				shadeClose:true
			});
		},
		confirmIdent:function(){
			var str;
			var mid = $('input[name=mid]').val();
			if(tid == undefined){
				return;
			}
			if(tid == 1 || tid == 2){
				if(subtid == undefined){
					return;
				}
				str = "&tid="+tid+"&subtid="+subtid;
			}else{
				str = "&tid="+tid;
			}
			window.location.href="/regist/stepIndustry.html?mid=" + mid + str;
		}	
	};
	
	var handleIndustry = {
		init:function(){
			$('.skip').on('click',function(){
				var str = "";
				var mid = $('input[name=mid]').val();
				var check = $('input[name=check]').val();
				if(check == 2 || check == 3){
					str = "&check="+check;
				}
				window.location.href="/regist/stepSuc.html?mid=" + mid +str;
			});
			$(".industry dt,.industry dd").on("click",function(){
				$(this).parent().toggleClass("sel_Industry");
			});
			
	        $('.next').on('click',function(){
		        var $industry = $(".sel_Industry");
		        var arr = new Array();
		        $industry.each(function(){
					var sid = $(this).attr('sid');
					arr.push(sid);
		        });
		        if(arr.length == 0){
			        layer.alert('请选择你的行业',{offset:'200px'});
			        return;
		        }
				handleIndustry.saveIndustry(arr.join(','));
	        });
		},
		
		saveIndustry:function(sids){
			var mid = $('input[name=mid]').val();
			$.ajax({
				type:"post",
				dataType:"json",
				url:"/service/saveIndustryOnRegistServ.html",
				data:{'sids':sids,'mid':mid},
				success:function(data){
					if(data.ret == 0){
						layer.alert(data.msg,{offset:'200px'});
					} else {
						var str = "";
						var check = $('input[name=check]').val();
						if(check == 2 || check == 3){
							str = "?mid=" + mid + "&check="+check;
						}
						window.location.href="/regist/stepSuc.html" + str;
					}
				},
				error:function(data){
					layer.alert('服务器错误,请稍后再试',{offset:'200px'});
				},
				complete:function(){
					
				}
			});
		}
	};
	
	function widreload() {
		window.location.reload();
	}
	
});