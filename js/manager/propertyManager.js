$(document).ready(function(){		
/*
    var pages=$('input[name=count]').val();
    var page=GetQueryString('page');
    
    layui.use(['laypage', 'layer'], function(){
        var laypage = layui.laypage
                ,layer = layui.layer;

        laypage({
            cont: 'page',
            pages: parseInt(pages),
            curr:parseInt(page),
            skip: true,
            jump:function (obj,first){
                if(!first) {
                    window.location.href = '../goodsmanager/property.html?page='+obj.curr;
                }
            }
        });
    });
*/

	$(".menu-wrapp li").on("click",function() {
        $(this).find("ul").slideToggle("slow");
    });
    
	var forthLevelArray = Array();
	var fifthLevelArray = Array();
	var allPropertyArray = Array();
	getPropertyByLevel();
	getAllProperty();
	
	var TimeFn = null;
	
	$(".levelone").on("click", function () {
        layer.open({
            type: 2,
            title: '一级类列表',
            shadeClose: true,
            shade: 0.5,
            area: ['370px', '60%'],
            content: 'http://d31.ichuk.com/goodsmanager/levelone.html'
        });
    });
    
    $(document).on("click",".addlevel",function(){
        var level = $(this).attr('level');
		var parentid = "";
		if(level > 2){
			parentid = $(this).attr('parentid');
		}
        addlevel(parentid,level);
    });
    
    function addlevel(parentid,level){
        var title;
		if(level == 1){
			title = "添加一级分类";
		} else if(level == 2){
			title = "添加二级分类";
		} else if(level == 3){
			title = "添加三级分类";
		} else if(level == 4){
			title = "添加四级分类";
		} else {
			title = "添加五级分类";
		}
		
		layer.open({
            type: 2,
            title: title,
            shadeClose: true,
            shade: 0.5,
            area: '380px',
            content: 'http://d31.ichuk.com/goodsmanager/addlabel.html?level='+level+'&parentid='+parentid,
        });
    }
    
    function addProperty(id,name){
	    layer.open({
            type: 2,
            title: name,
            shadeClose: true,
            shade: 0.5,
            area: ['400px', '50%'],
            content: 'http://d31.ichuk.com/goodsmanager/addproperty.html?id='+id
        });
    }
    
    $(document).on("dblclick",".prolevel",function(){
	    var ptid = $(this).attr('ptid');
	    var proname = $.trim($(this).text());
	    clearTimeout(TimeFn);
	    showUpdateTypePopup(ptid,proname);
    });
    
    function showUpdateTypePopup(ptid,proname){
	    layer.open({
            type: 1
            ,title: "修改分类名称"
            ,area: '330px;'
            ,shade: 0.5
            ,btn: ["确定", "取消"]
            ,btnAlign: 'c'
            ,content: '<div style="padding: 20px; line-height: 22px; font-weight: 300;">请输入分类名称<input class="type_name" autofocus="focus" type="text" style="line-height: 30px;border:1px solid #ddd;margin-left:15px;padding-left:2px; width:160px;" value=" ' + proname + ' "></div>'
            ,success: function(layero){
                var btn = layero.find('.layui-layer-btn');
                btn.find('.layui-layer-btn0').click(function(){
	                var name = $.trim($('.type_name').val());
	                if(name == ""){
		            	layer.alert('请输入类名');
			            return ;    
	                } else {
		                updateTypeName(ptid,name);
	                }
                });
            }
        });
    }
    
    function updateTypeName(ptid,name){
	    var params = {};
	    params.ptid = ptid;
		params.name = name;
	    $.ajax({
            type: "POST",
            url: "/service/updatePropertyTypeServ.html",
            data: params,
            dataType: "json",
            success: function (data) {
                if(data.ret==1){
	                layer.alert(data.msg,function(){
		            	location.reload(true);    
	                });
                }else{
                    layer.alert(data.msg);
                }
            },
            error: function (data) {
                layer.alert("服务器错误1");
            }
        });
    }
    
    $("button[name=levelthree]").on("click", function () {
	    $(this).addClass('select').siblings().removeClass("select");
	    var ptid = $(this).attr('ptid');
	    var parentid = $(this).attr('parentid');
	    var name = $(this).text();
	    clearTimeout(TimeFn);
	    
	    TimeFn = setTimeout(function(){
		    var exitSub = false;
		    for(var i in forthLevelArray){
			    var forthLevel = forthLevelArray[i];
			    if(forthLevel['parentid'] == ptid){
				    exitSub = true;
				    break;
			    }
		    }
		    
		    var exitProp = false;
		    if(!exitSub){
			    for(var j in allPropertyArray){
				    var property = allPropertyArray[j];
				    if(property['ptid'] == ptid){
					    exitProp = true;
					    break;
				    }
			    }
		    }
		    
		     $(".forthBody[ptid=" + parentid + "]").empty();
		     $(".fifthBody[ptid=" + parentid + "]").empty();
		    if(exitSub){
			    var str = "";
			    for(var i in forthLevelArray){
				    var forthLevel = forthLevelArray[i];
				    if(forthLevel['parentid'] == ptid){
					    str = str + "<button class=\'forthLevel prolevel\' ptid=\'" + forthLevel['ptid'] + "\' parentid=\'" + parentid + "\'>" + forthLevel['name'] + "</button>";
				    }
			    }
			    
			    str = str + "<button class=\'addlevel layui-btn\'\
	                        	ptid=\'" + ptid + "\'\
	                        	level=\'4\'>\
	                            <i class=\'layui-icon\'>+</i>\
	                        </button> ";
			    
			    $(".forthBody[ptid=" + parentid + "]").html(str);
		    } else if(!exitProp){
			    var alert = layer.confirm(name,{
						title:"三级分类",
						btn: ['编辑三级分类','添加四级分类'] 
					}, function(){
						layer.close(alert);
						addProperty(ptid,name);
					}, function(){
						addlevel(ptid,4);
					});
		    } else {
			    addProperty(ptid,name);
		    }
	    },250);
    });
    
    $(document).on("click",".forthLevel",function(){
	    $(this).addClass('select').siblings().removeClass("select");
	    var ptid = $(this).attr('ptid');
	    var parentid = $(this).attr('parentid');
	    var name = $(this).text();
	    
	    clearTimeout(TimeFn); 
	    TimeFn = setTimeout(function(){
			var exitSub = false;
		    for(var i in fifthLevelArray){
			    var fifthLevel = fifthLevelArray[i];
			    if(fifthLevel['parentid'] == ptid){
				    exitSub = true;
				    break;
			    }
		    }
		    
		    var exitProp = false;
		    if(!exitSub){
			    for(var j in allPropertyArray){
				    var property = allPropertyArray[j];
				    if(property['ptid'] == ptid){
					    exitProp = true;
					    break;
				    }
			    }
		    }
		    
		    $(".fifthBody[ptid=" + parentid + "]").empty();
		    if(exitSub){
			    var str = "";
			    for(var i in fifthLevelArray){
				    var fifthLevel = fifthLevelArray[i];
				    if(fifthLevel['parentid'] == ptid){
					    str = str + "<button class=\'fifthLevel prolevel\' ptid=\'" + fifthLevel['ptid'] + "\' parentid=\'" + parentid + "\'>" + fifthLevel['name'] + "</button>";
				    }
			    }
			    
			    str = str + "<button class=\'addlevel layui-btn\'\
	                        	ptid=\'" + ptid + "\'\
	                        	level=\'5\'>\
	                            <i class=\'layui-icon\'>+</i>\
	                        </button> ";
			    
			    $(".fifthBody[ptid=" + parentid + "]").html(str);
		    } else if(!exitProp){
			    var alert = layer.confirm(name,{
						title:"四级分类",
						btn: ['编辑四级分类','添加五级分类'] 
					}, function(){
						layer.close(alert);
						addProperty(ptid,name);
					}, function(){
						addlevel(ptid,5);
					});
		    } else {
			    addProperty(ptid,name);
		    }
	    },250);
    });
    
    $(document).on("click",".fifthLevel",function(){
	    var ptid = $(this).attr('ptid');
	    var name = $(this).text(); 
	    
	    clearTimeout(TimeFn); 
	    TimeFn = setTimeout(function(){
			addProperty(ptid,name);  
	    },250)
    });
    
    $("span[class=del]").on("click",function(){
        var id=$(this).attr('proid');
        var alert = layer.confirm("是否删除？", {
            title:"<b>删除后该类和其子类将消失!!</b>",
            btn: ['确认','取消'] //按钮
        }, function(){
            layer.close(alert);
            Deletetype(id);
        }, function(){

        });
    })
    
    $("span[class=update]").on("click",function(){
        var ptid = $(this).attr('proid');
        var proname = $(this).attr('proname');
        showUpdateTypePopup(ptid,proname);
    });
    
    function Deletetype(id){
        $.ajax({
            type: "POST",
            url: '/service/deleteprotypeServ.html',
            data: {"ptid":id},
            dataType: "json",
            success: function (data) {
	            var alert = layer.alert(data.msg,function(){
		            if(data.ret == 1){
			            location.reload(true);
		            }
	            });
            },
            error: function (data) {
                layer.alert("error");
            }
        });
    }

    function GetQueryString(name){
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    };
    
    function getPropertyByLevel(){
	    $.ajax({
            type: "POST",
            url: "/service/getPropertyByLevServ.html",
            data: "",
            dataType: "json",
            success: function (data) {
                if(data.ret==1){
	                forthLevelArray = data.forth;
	                fifthLevelArray = data.fifth;
                }else{
                    layer.alert(data.msg);
                }
            },
            error: function (data) {
                layer.alert("error");
            }
        });
    }
    
    function getAllProperty(){
	    $.ajax({
            type: "POST",
            url: "/service/getAllPropertyServ.html",
            data: "",
            dataType: "json",
            success: function (data) {
                if(data.ret==1){
	                allPropertyArray = data.property;
                }else{
                    layer.alert(data.msg);
                }
            },
            error: function (data) {
                layer.alert("error");
            }
        });
    }
});