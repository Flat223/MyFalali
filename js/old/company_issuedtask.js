define(function(require){
	var $ = require('jquery');
	require('ichuk');
	require('common');
	var layer = require('layer');
	layer.config({
		path:'/js/layer/'
	});
	
	var chosenMid = 0;
	var queryflag = 0;
	
	var handle = {
		init:function(){
		     var iChuk = iChukCore.Inital();
			 $addmission = $("#addmission"); 
			 $addmission.click(function(){
			    var adddialog;
				var time = Math.floor((Math.random()*100000000)+1);
				LoadTemplets = "../../do/loadplughtml.html?filename=user.issuedtask.addmission.html&rand="+time;
				htmlobj=$.ajax({url:LoadTemplets,async:false});
				var html = "<div id=\'_"+time+"\'>"+htmlobj.responseText+"</div>";
				layer.open({
					title:"发活",
					type: 1,
					scrollbar: false,
					area: ['650px', 'auto'],
					skin: 'layui-layer-demo', //样式类名
					closeBtn: 1, //不显示关闭按钮
					shift: 2,
					shadeClose: false, //开启遮罩关闭
					content: html
				});
			 });
			$("#goto").on("click",function(){
				var page = $.trim($("#page_num").val());
				if(page == ""){
					return;
				}
				var sid = $("#sid").val();
				var search = $("#search_key").val();
				window.location.href = getHost()+"/user/issuedTask.html?sid="+sid+"&search="+search+"&page="+page;
			});
			$("#page_num").keypress(function(e){
				if(e.keyCode == 13){
					$("#goto").click();
				}
			});
			$("#task_search_btn").on("click",function(){
				var searchVal = $.trim($("#task_search").val());
				if(searchVal == ""){
					return;
				}
				var sid = $("#sid").val();
				window.location.href = getHost()+"/user/issuedTask.html?sid="+sid+"&search="+searchVal;
			});
			$("#task_search").keypress(function(e){
				if(e.keyCode == 13){
					$("#task_search_btn").click();
				}
			});
			$(".td_a a").on("click",function(){
				var id = $(this).parent().attr("data");
				if(id == undefined || isNaN(id) || id <= 0){
					return;
				}
				var opt = $(this).attr("opt");
				if(opt == 'complete'){
					var index = layer.confirm('确认完成？', {
						title:"温馨提示",
						btn: ['确认','取消'], //按钮
						offset:['220px','40%']
					}, function(){
						layer.close(index);
						handle.changeMissionSt(id,4);
					});	
				}else if(opt == 'unpass'){
					layer.confirm('确认不通过？', {
						title:"温馨提示",
						btn: ['确认','取消'], //按钮
						offset:['220px','40%']
					}, function(index){
						layer.close(index);
						handle.changeMissionSt(id,2);
					});	
				}else if(opt == 'distribute'){
					handle.showApplicants(id);
				}else if(opt == 'payFunds'){
					handle.confirmPayFunds(id);
				}
			});
			$("#tab2 ul li").on("click",function(){
				if($(this).hasClass("act")){
					return;
				}
				$(this).addClass("act").siblings().removeClass("act");
				var ref = $(this).attr("ref");
				$("."+ref).show().siblings(".tab").hide().find("tr.active").removeClass("active");
				chosenMid = 0;
			});
			$(document).on("click","#tab2 .tab table tbody tr",function(){
				if($(this).hasClass("active")){
					$(this).removeClass("active");
					chosenMid = 0;
				}else{
					$(this).addClass("active").siblings().removeClass("active");
					chosenMid = $(this).attr("data");
				}
			});
		},
		showApplicants:function(id){
			if(queryflag == 1){
				return;
			}
			queryflag = 1;
			var index = layer.load(2);
			$.ajax({
				type:'post',
				dataType:'json',
				data:{id:id},
				url:getHost()+"/service/getApplicants.html",
				success:function(data){
					if(data.ret == 1){
						var applicants = data.applicants;
						var emplyees = data.employees;
						var html1 = "";
						var html2 = "";
						if(applicants.length == 0){
							html1 += "<div class='no-data'>没有申请者</div>";
						}else{
							html1 += "<table><thead><tr><th>姓名</th><th>昵称</th><th>手机号</th></tr></thead><tbody>";
							for(var i in applicants){
								html1 += "<tr data='"+applicants[i].mid+"'><td>"+applicants[i].name+"</td>";
								html1 += "<td>"+applicants[i].nickname+"</td>";
								html1 += "<td>"+applicants[i].mobile+"</td></tr>";
							}
							html1 += "</tbody></table>";
						}
						$(".applicant").html(html1);
						if(emplyees.length == 0){
							html2 += "<div class='no-data'>没有企业员工</div>";
						}else{
							html2 += "<table><thead><tr><th>姓名</th><th>手机号</th></tr></thead><tbody>";
							for(var i in emplyees){
								html2 += "<tr data='"+emplyees[i].mid+"'><td>"+emplyees[i].name+"</td>";
								html2 += "<td>"+emplyees[i].mobile+"</td></tr>";
							}
							html2 += "</tbody></table>";
						}
						$(".employee").html(html2);
						layer.open({
						   type: 1,
						   offset:['40px;','40%'],
						   title:"申请者/企业员工",
						   skin: 'layui-layer-rim', //加上边框
						   area: ['340px', '470px'], //宽高
						   btn:['确定','取消'],
						   content: $("#tab2"),
						   cancel:function(){
							  handle.clearApplicant();
						   },
						   yes:function(index){
							   var mid = chosenMid;
							   if(mid == undefined || isNaN(mid) || mid <= 0){
								   layer.alert("请选择一个分配者");
								   return;
							   }
							   handle.distribute(mid,id);
							   handle.clearApplicant();
							   layer.close(index);
						   },btn2:function(){
							   handle.clearApplicant();
						   }
						});	
					}else if(data.ret == -1){
						window.location.href = getHost()+"/handle/login.html?redirect="+encodeURIComponent(window.location.href);
					}else{
						layer.alert(data.msg);
					}
				},
				error:function(){
					layer.alert('抱歉，服务器错误');
				},
				complete:function(){
					queryflag = 0;
					layer.close(index);
				}
			});
		},
		clearApplicant:function(){
			chosenMid = 0;
			$(".applicant,.employee").html("");
			$("#tab2 ul li:eq(0)").click();
		},
		distribute:function(mid,pid){
			if(queryflag == 1){
				return;
			}
			queryflag = 1;
			var index = layer.load(2);
			$.ajax({
				type:"post",
				dataType:"json",
				data:{id:pid,mid:mid},
				url:getHost()+"/service/distributePj.html",
				success:function(data){
					if(data.ret == 1){
						window.location.reload();
					}else if(data.ret == -1){
						window.location.href = getHost()+"/handle/login.html?redirect="+encodeURIComponent(window.location.href);
					}else{
						layer.alert(data.msg);
					}
				},error:function(){
					layer.alert('抱歉，服务器错误');
				},
				complete:function(){
					queryflag = 0;
					layer.close(index);
				}
			});
		},
		changeMissionSt:function(id,st){
			if(queryflag == 1){
				return;
			}
			queryflag = 1;
			var index = layer.load(2);
			$.ajax({
				type:"post",
				dataType:"json",
				data:{pid:id,result_status:st},
				url:getHost()+"/service/changeCompanyTask.html",
				success:function(data){
					if(data.ret == 1){
						window.location.reload();
					}else if(data.ret == -1){
						window.location.href = getHost()+"/handle/login.html?redirect="+encodeURIComponent(window.location.href);
					}else{
						layer.alert(data.msg);
					}
				},error:function(){
					layer.alert('抱歉，服务器错误');
				},
				complete:function(){
					queryflag = 0;
					layer.close(index);
				}
			});
		},
		confirmPayFunds:function(id){
			layer.confirm("确定要托管资金吗？",{icon:3,title:'提示',btn:['托管','取消']},function(index){
				window.location.href = getHost()+"/user/mkorder.html?id="+id;
				layer.close(index);
			});	
		}
	};
	(function(){
		handle.init();
	})();
});