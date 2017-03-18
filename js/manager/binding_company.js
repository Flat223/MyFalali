$(function () {

    function GetQueryString(name) {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    };

    var ch = GetQueryString('ch');

    $(".chart-wrapp ul li").each(function () {
        if(ch == null){
            ch = 1;
        }
        if ($(this).attr('value') == ch) {
            $(this).addClass("chart-this");
        }
    });
    
    /*$("#serlab").on("click",function () {
        var resinfo = $("#resdata").val();
        window.location.href="/labmanager/labListing.html?data="+resinfo;
    });*/
    

    $(".del").on("click",function() {
        var id = $(this).attr('value');
        var mid = $(this).attr('mid');
        layer.confirm('确定要删除吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/BindingCompServ.html",
                data: {"id":id,"mid":mid},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.setTimeout(widreload,1000);
                    }else if(data.ret == -1){
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error:function (msg) {
                    layer.msg('删除失败!', {icon: 2});
                }
            });
        }, function(){
            layer.msg('', {
                time: 100
            });
        });
    });

    $(".check").on("click",function() {
        var id = $(this).attr('value');
        var mid = $(this).attr('mid');
        /*alert(id+"==="+mid);*/
        layer.confirm('对该条记录进行审核', {
            btn: ['通过','拒绝'] //按钮
        }, function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/BindingCompServ.html",
                data: {"id":id,'mid':mid,"bj":0},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.setTimeout(widreload,1000);
                    }else if(data.ret == -1){
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error:function (msg) {
                    layer.msg('审核失败!', {icon: 2});
                }
            });
        }, function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/BindingCompServ.html",
                data: {"id":id,'mid':mid,"bj":1},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.setTimeout(widreload,1000);
                    }else if(data.ret == -1){
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error:function (msg) {
                    layer.msg('审核失败!', {icon: 2});
                }
            });
        });
    });

    function widreload() {
        window.location.reload();
    }

});
