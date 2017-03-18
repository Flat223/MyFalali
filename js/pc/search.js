define(function(require){
    var $ = require('jquery');
    var layer = require('layer/layer.js');
    require('swiper.min.js');
    require('cityData.js');
    require('cityPicker.js');
    require('pc/user/logout.js');
    layer.config({
        path:'/js/layer/'
    });
	var userInfo={};
	getUserInfo();
    var cityPicker = new IIInsomniaCityPicker({
        data: cityData,
        target: '#cityChoice',
        valType: 'k-v',
        hideCityInput: '#city',
        hideProvinceInput: '#province',
        callback: function(city_id){
            
        }
    });

    cityPicker.init();

    $("#usercity").hover(function(){
        $(this).addClass('hover');
    }, function(){
        $(this).removeClass('hover');
    });

    function GetQueryString(name) {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    }

    var ob = GetQueryString('ob');
    $('.goods ul li').each(function () {
        if (ob != 0 && ob != null) {
            if ($(this).attr('value') == ob) {
                $(this).addClass("sort-this");
                $(this).find('a').css('color', '#fff');
            }
        } else {
            var sort = 1;
            if ($(this).attr('value') == sort) {
                $(this).addClass("sort-this");
                $(this).find('a').css('color', '#fff');
            }
        }
    });

    $(".Laybut").on("click",function () {
        // layer.open({
        //     type: 1,
        //     title: 通知,
        //     area: '300px',
        //     skin: 'layui-layer-nobg', //没有背景色
        //     shadeClose: flase,
        //     content: $('.LayMsg')
        // })
        console.log(11);
        layer.tips('下', '.Laybut', {
            tips: 3
        });
    });
    function userlogout()
    {
        var url = "../?do/logout";
        $.ajax({
            type: "POST",
            url: url,
            data:{},
            dataType: "json",
            success : function(data){
                if(data.ret == 1)
                {
                    window.location.reload();
                }
                else
                {
                    alert(data.msg);
                }
            },
            error:function(data){
                var status = data.status ;
                var readyState = data.readyState;
                alert("error");
            }
        });
    }
    
    $("#weixin").click(function () {
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            area: '300px',
            skin: 'layui-layer-nobg', //没有背景色
            shadeClose: true,
            content: $('#wximg')
        });
    })
    $("input[name=search]").focus(function () {
	  if($(this).val()!=""){
      	$(".automatically").show();
      }
    });

    var handle = {
        init:function(){

			$("#buy_cart").click(function () {
	        	if(userInfo.type==3){
					layer.alert("您的身份无购物车功能");
					return;
				}else if(userInfo.type==1&&userInfo.sub_type==0){
					layer.alert("您的身份无购物车功能");
					return;
				}else if(userInfo.type==2&&userInfo.sub_type==0){
					layer.alert("您的身份无购物车功能");
					return;
				}
				window.location.href="/user/shopCart.html";
	        });
	        $("input[name=search]").blur(function(){
		        setTimeout(function () {
					$(".automatically").hide();
				}, 300);
				
			});
	        $("select.search").change(function(){
		       	var key=$('input[name=search]').val();
				if(key==""){
					$("li").remove('.ad');
					$(".automatically").hide();
					return;
				}
				 var type=$("select[name=search]").val();
				 var ntype=0;
				 if(type=='goods'){
					 ntype=1;
				 }else if(type=='lab'){
					 ntype=2;
				 }
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/GetSearchHintServ.html",
					data:{'key':key,'type':ntype},
					success:function(data){
						var data1=data.data;
						if(data1.length>0){
							$(".automatically").show();	
						}
						var str="";
						if(ntype==1){
							for(var i=0;i<data1.length;i++){
								str+="<li class=\'ad\'>\
										<a href=\'/goods/detail.html?pid="+data1[i]['pid']+"\' target=\'_blank\'>"+data1[i]['name']+"</a>\
									</li>\
									";
							}
						}else if(ntype==2){
							for(var i=0;i<data1.length;i++){
								str+="<li class=\'ad\'>\
										<a href=\'/lab/detail.html?labId="+data1[i]['lab_id']+"\' target=\'_blank\'>"+data1[i]['name']+"</a>\
									</li>\
									";
							}
						}
						$("li").remove('.ad');
						$(".automatically").append(str);
					},
					error:function(data){
						layer.alert("服务器错误,请稍后再试");
					}
				});

	        });
			$('input[name=search]').bind('input oninput',function(){
				var key=$(this).val();
				if(key==""){
					$("li").remove('.ad');
					$(".automatically").hide();
					return;
				}
				 var type=$("select[name=search]").val();
				 var ntype=0;
				 if(type=='goods'){
					 ntype=1;
				 }else if(type=='lab'){
					 ntype=2;
				 }
				$.ajax({
					type:"post",
					dataType:"json",
					url:"/service/GetSearchHintServ.html",
					data:{'key':key,'type':ntype},
					success:function(data){
						var data1=data.data;
						if(data1.length>0){
							$(".automatically").show();	
						}
						var str="";
						if(ntype==1){
							for(var i=0;i<data1.length;i++){
								str+="<li class=\'ad\'>\
										<a href=\'/goods/search.html?type=goods&info="+data1[i]['name']+"\'>"+data1[i]['name']+"</a>\
									</li>\
									";
							}
						}else if(ntype==2){
							for(var i=0;i<data1.length;i++){
								str+="<li class=\'ad\'>\
										<a href=\'/lab/detail.html?labId="+data1[i]['lab_id']+"\'>"+data1[i]['name']+"</a>\
									</li>\
									";
							}
						}
						$("li").remove('.ad');
						$(".automatically").append(str);
					},
					error:function(data){
						layer.alert("服务器错误,请稍后再试");
					}
				});
				
	        });
            $(document).on('click','span[name=search]',function(){
                console.log(1);
                var info=$("input[name=search]").val();
                var type=$("select[name=search]").val();
                console.log(type);
                if(info.length<=0){
                    layer.alert('请输入搜索信息');
                    return false;
                }
                var url1='../lab/search.html?type=lab';
                var url2='../goods/search.html?type=goods';
                if(type=='lab'){
                    var urla=url1+"&info="+info;
                    window.location.href=urla;
                }
                if(type=='goods'){
                    var urlb=url2+"&info="+info;
                    window.location.href=urlb;
                }
            });

            $("input[name=search]").keydown(function() {
                if (event.keyCode == "13") {//keyCode=13是回车键
                    $('span[name=search]').click();
                }
            });      
        }
    };
    
    function getUserInfo(){
		$.ajax({
			type:"post",
			dataType:"json",
			url:"/service/GetUserInfoServ.html",
			data:{},
			success:function(data){
				userInfo=data.data;
			},
			error:function(data){
				alert("服务器错误,请稍后再试");
			}

		});
	}
    
    

    $(function(){
        handle.init();
    });
});