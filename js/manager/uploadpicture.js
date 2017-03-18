define(function(require){
    var $ = require("jquery");
    $.browser = {};
    $.browser.mozilla = /firefox/.test(navigator.userAgent.toLowerCase());
    $.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
    $.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
    $.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());
    require("common");
    require("map");
    require("jquery.Jcrop.min")($);
    var dialog = require("dialog");
    if($.browser.msie){
        require('ajaxFileUpload.js')($);
        require('upload4ie.js')($);
    }else{
        require('upload.js')($);
    }
    var product;
    var types;
    var brandTypes;
    var typeid;
    var imageMap;
    var maxImageSize = 5;
    var jcrop_api;
    var maxElseInfo = 6;

    var handlePro = {
        init:function(){
            imageMap = new Map();
            this.initTypes();
            this.initBrands();
            this.initUploadImg();
            this.initJcrop();
            this.getTypes();
            this.getBrands();
        },
        initTypes:function(){
            $("#type1").on("change",function(){
                if(types == undefined || types == null){
                    return;
                }
                var type1 = $(this).val();
                var types2 = types.types2;
                var html = "";
                for(var i in types2){
                    if(types2[i].parentid == type1){
                        html += "<option value='"+types2[i].tid+"'>"+types2[i].name+"</option>";
                    }
                }
                $("#type2").html(html);
                $("#type2").change();
            });
            $("#type2").on("change",function(){
                if(types == undefined || types == null){
                    return;
                }
                var type2 = $(this).val();
                var types3 = types.types3;
                var html = "";
                for(var i in types3){
                    if(types3[i].parentid == type2){
                        html += "<option value='"+types3[i].tid+"'>"+types3[i].name+"</option>";
                    }
                }
                $("#type3").html(html);
            });
        },
        initBrands:function(){
            $("#brand_types").on("change",function(){
                if(brandTypes == undefined || brandTypes == null){
                    return;
                }
                var id = $(this).val();
                for(var i in brandTypes){
                    if(brandTypes[i].id == id){
                        var brands = brandTypes[i].brands;
                        var html = "";
                        for(var j in brands){
                            html += "<option value='"+brands[j].id+"'>"+brands[j].name+"</option>";
                        }
                        $("#brands").html(html);
                        break;
                    }
                }
            });
        },
        initUploadImg:function(){
            $("#uploadimg").myUpload({
                uploadUrl:getHost()+"/do/uploadProImg.html",
                btnName:"添加图片",
                fileFilter:/^(bmp|gif|jpeg|png|tiff|jpg)$/i,
                begin:function(){
                    if(imageMap.size() >= maxImageSize){
                        dialog.info({msg:"已有"+maxImageSize+"张图片，不可再添加了"});
                        return false;
                    }
                },
                error:function(data){
                    dialog.info({msg:data});
                },
                success:function(url){
                    var arr = url.split("/");
                    var imageName = arr[arr.length-1];
                    $("#cut_img_name").val(imageName);
                    handlePro.showImgCut(url);
                }
            });
        },
        initJcrop:function(){
            $("#jcrop").Jcrop({
                addClass:'content-center',
                aspectRatio:1,
                minSelect:[50,50],
                maxSize:[800,800],
                boxWidth:600,
                boxHeight:400,
                onSelect:handlePro.showCoords,
                onChange:handlePro.showCoords,
                onRelease:handlePro.clearCoords
            },function(){
                jcrop_api = this;
            });
        },
        getTypes:function(){
            $.ajax({
                type:"post",
                dataType:"json",
                url:getHost()+"/do/getProductTypes.html",
                success:function(data){
                    var dataobj = eval(data);
                    if(dataobj.ret == 1){
                        types = dataobj.types;
                        var types1 = types.types1;
                        var html = "";
                        for(var i in types1){
                            html += "<option value='"+types1[i].tid+"'>"+types1[i].name+"</option>";
                        }
                        $("#type1").html(html);
                        $("#type1").change();
                    }else if(dataobj.ret == -2){
                        window.location.href = getHost()+"/shop/login.html?uri="+window.location.href;
                    }else if(dataobj.ret == -1){
                        window.location.href = getHost()+"/member/login.html?uri="+window.location.href;
                    }
                }
            });
        },
        getBrands:function(){
            $.ajax({
                type:"post",
                dataType:"json",
                url:getHost()+"/do/getAllBrands.html",
                success:function(data){
                    var dataobj = eval(data);
                    if(dataobj.ret == 1){
                        brandTypes = dataobj.brandTypes;
                        var html = "";
                        for(var i in brandTypes){
                            html += "<option value='"+brandTypes[i].id+"'>"+brandTypes[i].type_name+"</option>";
                        }
                        $("#brand_types").html(html).change();
                    }else if(dataobj.ret == -2){
                        window.location.href = getHost()+"/shop/login.html?uri="+window.location.href;
                    }else if(dataobj.ret == -1){
                        window.location.href = getHost()+"/member/login.html?uri="+window.location.href;
                    }
                }
            });
        },
        showImgCut:function(url){
            jcrop_api.setImage(url);
            $("#cover").show();
            $("#main_jcrop").show();
        },
        showCoords:function(c){
            $("#img-width").val(c.w);
            $("#img-height").val(c.h);
        },
        clearCoords:function(){
            $("#img-width").val("");
            $("#img-height").val("");
        },
        cancelCrop:function(){
            this.clearCoords();
            jcrop_api.release();
            jcrop_api.setSelect([0,0,0,0]);
            $("#cover").hide();
            $("#main_jcrop").hide();
            $("#cut_img_name").val("");
            jcrop_api.setImage("");
        },
        uploadCutImg:function(){
            var croping = $("#img_jcrop_confirm").attr("data");
            if(croping == 1){
                return;
            }
            var imageName = $("#cut_img_name").val();
            if(imageName == "" || imageName == undefined){
                dialog.info({msg:"参数错误"});
                return;
            }
            var obj = jcrop_api.tellSelect();
            if(obj.h < 50 || obj.w < 50){
                dialog.info({msg:"请剪裁图片"});
                return;
            }
            $("#img_jcrop_confirm").attr("data",1);
            var data = {imageName:imageName,width:obj.w,height:obj.h,x:obj.x,y:obj.y};
            $.ajax({
                type:"post",
                url:getHost()+"/do/cutProductImg.html",
                dataType:"json",
                data:data,
                success:function(data){
                    var dataobj = eval(data);
                    if(dataobj.ret == 1){
                        var size = imageMap.size();
                        if(size >= maxImageSize){
                            dialog.info({msg:"已有"+maxImageSize+"张图片，不可再添加了"});
                        }else{
                            var img = $("#product_images").children(".added_img:eq("+size+")").children("img");
                            img.attr("src",dataobj.url+"?id="+Math.random()).attr("data",dataobj.url);
                            imageMap.put(dataobj.url,1);
                            handlePro.cancelCrop();
                        }
                    }else if(dataobj.ret == 2){
                        dialog.info({msg:dataobj.msg});
                    }else{
                        dialog.info({msg:dataobj.msg});
                    }
                    $("#img_jcrop_confirm").attr("data",0);
                },
                error:function(data){
                    dialog.info({msg:"抱歉，服务器出错"});
                    $("#img_jcrop_confirm").attr("data",0);
                }
            });
        },
        addElseInfo:function(){
            if($(".otherinfo").length >= maxElseInfo){
                dialog.info({msg:"额外信息最多只能有"+maxElseInfo+"条"});
                return;
            }
            var html = "<tr class='otherinfo'>";
            html += "<td><input type='input' class='form-control info_name' placeholder='填写名称' maxlength='30' ></td>";
            html += "<td><input type='input' class='form-control info_val' placeholder='填写名称值' maxlength='30' ></td>";
            html += "<td><button type='button' class='btn btn-primary info_del' style='padding:5px 25px;'>删除</button></td>";
            html += "</tr>";
            $(".otherinfo_body").append(html);
            $(".otherinfo:last").find(".info_name").focus();
        },
        saveProduct:function(){
            var proname = $.trim($("#pro_name").val());
            if(proname == ""){
                dialog.info({msg:"请填写产品名称"});
                return;
            }
            var typeid = $("#type3").val();
            if(isNaN(typeid) || parseInt(typeid) <= 0){
                dialog.info({msg:"请选择类型"});
                return;
            }
            var brandid = $("#brands").val();
            if(isNaN(brandid) || parseInt(brandid) <= 0){
                dialog.info({msg:"请选择品牌"});
                return;
            }
            var num = imageMap.size();
            if(num == 0){
                dialog.info({msg:"请添加产品图片"});
                return;
            }
            if(num > maxImageSize){
                dialog.info({msg:"最多只能上传"+maxImageSize+"张图片"});
            }
            var imgs = "";
            var images = imageMap.keys();
            for(var i in images){
                imgs += images[i]+",";
            }
            if(imgs != ""){
                imgs = imgs.substring(0,imgs.length-1);
            }
            var otherinfos = [];
            var infoNames = [];
            var flag = true;
            $(".otherinfo").each(function(){
                var infoName = $.trim($(this).find(".info_name").val());
                var infoVal = $.trim($(this).find(".info_val").val());
                if(infoName == ""){
                    dialog.info({msg:"额外信息名称不能为空"});
                    flag = false;
                    return false;
                }
                if(infoVal == ""){
                    dialog.info({msg:"额外信息名称的值不能为空"});
                    flag = false;
                    return false;
                }
                if(otherinfos.length >= maxElseInfo){
                    dialog.info({msg:"额外信息最多有"+maxElseInfo+"条"});
                    flag = false;
                    return false;
                }
                if($.inArray(infoName,infoNames) > -1){
                    dialog.info({msg:"额外信息名称有重复"});
                    flag = false;
                    return false;
                }
                infoNames.push(infoName);
                var info = {};
                info.infoName = infoName;
                info.infoVal = infoVal;
                otherinfos.push(info);
            });
            if(!flag){
                return;
            }
            var intro = ue.getContent();
            var data = {};
            data.info = JSON.stringify(otherinfos);
            data.pname = proname;
            data.ptype = typeid;
            data.pimages = imgs;
            data.pintro = intro;
            data.brandid = brandid;
            $.ajax({
                type:"post",
                dataType:"json",
                data:data,
                url:getHost()+"/do/saveProBaseinfo.html",
                success:function(data){
                    var dataobj = eval(data);
                    if(dataobj.ret == 1){
                        var form = "<form type='post' action='"+getHost()+"/myshop/uploadproductscd.html' id='subform' >";
                        form += "<input type='hidden' name='pid' value='"+dataobj.pid+"' />";
                        form += "</form>";
                        $("body").append(form);
                        $("#subform").submit();
                    }else if(dataobj.ret == -2){
                        window.location.href = getHost()+"/shop/login.html?uri="+window.location.href;
                    }else if(dataobj.ret == -1){
                        window.location.href = getHost()+"/member/login.html?uri="+window.location.href;
                    }else{
                        dialog.info({msg:dataobj.msg});
                    }
                },
                error:function(){
                    dialog.info({msg:"发生错误"});
                }
            });
        }
    };

    (function(){
        handlePro.init();
        $("#img_jcrop_confirm").on("click",function(){
            handlePro.uploadCutImg();
        });
        $("#img_jcrop_cancel").on("click",function(){
            var croping = $("#img_jcrop_confirm").attr("data");
            if(croping == 1){
                return;
            }
            handlePro.cancelCrop();
        });
        $(document).on("click",".del_imgs",function(){
            var url = $(this).next("img").attr("data");
            if(url == undefined || url == ""){
                dialog.info({msg:"参数错误"});
                return;
            }
            imageMap.removeByKey(url);
            $(this).parent("div").remove();
            var html = "<div class='added_img'><span class='del_imgs'></span><img src='/images/img-add-default.jpg' data='' /></div>";
            $("#product_images").append(html);
        });
        $("#product_images").on("mouseleave",".added_img",function(){
            $(this).children(".del_imgs").hide();
        }).on("mouseenter",".added_img",function(){
            var url = $(this).children("img").attr("data");
            if(url != undefined && url != ""){
                $(this).children(".del_imgs").show();
            }
        });
        $("#otherinfo_add").on("click",function(){
            handlePro.addElseInfo();
        });
        $(document).on("click",".info_del",function(){
            $(this).parent().parent().remove();
        });
        $("#save").on("click",function(){
            handlePro.saveProduct();
        });
    })();

});