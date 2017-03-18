define(function(require) {
    var $ = require('jquery');
    require('swiper.min.js');
    var layer = require('layer/layer.js');
    layer.config({
        path:'/js/layer/'
    });

    var labtid = GetQueryString('labtId');
    var labpid = GetQueryString('parentId');
    var obtype = GetQueryString('obtype');

    $(".heart").click(function () {
        var id = $(this).attr('value');
        $.ajax({
            type:"get",
            dataType:"json",
            url:"/service/CollectionLabServ.html",
            data:{"id":id},
            success:function(data) {
                if(data.ret == 1){
                    layer.alert(data.msg, {icon: 1});
                    $(".ht"+id).attr('src','/images/temp_pc/list-heart.png');
                }else if(data.ret == -1){
                    layer.alert(data.msg, {icon: 2});
                }else if(data.ret == -2){
                    layer.alert(data.msg, {icon: 2});
                }else if(data.ret == -3){
                    layer.alert(data.msg, {icon: 6});
                }
            },
            error:function () {
                layer.alert("服务器繁忙", {icon: 2});
            }
        });
    });

    var handle = {
        init:function() {
            $("div .colorValue").each(function () {
                if (labpid == null) {
                    if ($(this).attr('value') == labtid) {
                        $(this).addClass("act-a");
                    }
                } else {
                    if ($(this).attr('value') == labpid || $(this).attr('value') == labtid) {
                        $(this).addClass("act-a");
                    }
                }
            });

            $('.sort ul li').each(function () {
                if (obtype != 0 && obtype != null) {
                    if ($(this).attr('value') == obtype) {
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
        }
    };

    function GetQueryString(name) {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    }

    $(function(){
        handle.init();
    });
});