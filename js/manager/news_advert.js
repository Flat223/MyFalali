$(function () {

    // $(".menu-wrapp li").on("click",function() {
    //     $(this).find("ul").slideToggle("slow");
    // });

    var type=GetQueryString('type');
    var pages=$('input[name=count]').val();
    var page=GetQueryString('page');
    var url='../newsmanager/advert.html?';
    if(type){
        url+="type="+type;
    }
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
                    window.location.href = url+'&page='+obj.curr;
                }
            }
        });
    });
    $(".edit").on("click",function() {
        var id = $(this).attr('value');
        layer.open({
            type: 2,
            title: '修改信息',
            shadeClose: true,
            shade: 0.8,
            area: ['380px', '30%'],
            content: '/newsmanager/editAdvert.html?id='+id
        });
    });

    $("#save").on("click",function() {
        var id = $("#aid").val();
        var kw = $("#keyWord").val();
        var url = $("#url").val();
        if(kw == null || kw == "" || url == null || url == ""){
            layer.msg("信息不可为空！", {icon: 2});
            return false;
        }
        var matchurl = /^((ht|f)tps?):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/;
        if(!matchurl.test(url)){
            layer.msg("链接错误！", {icon: 2});
            $("#url").val("");
            return false;
        }
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/EditAdvertServ.html",
                data: {"id":id,"kw":kw,"url":url},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.setTimeout(widreload,1000);
                    }else if(data.ret == -1){
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error:function (msg) {
                    layer.msg('操作失败!', {icon: 2});
                }
            });
    });

    $(".del").on("click",function() {
        var id = $(this).attr('value');
        layer.confirm('确定要删除吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/DeleteAdvertServ.html",
                data: {"id":id},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.setTimeout(widreload,1000);
                        window.location.reload();
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

    $("span[name=typesearch]").on("click",function(){
        var type=$("select[name=advert] option:selected").attr('type');
        window.location.href="/newsmanager/advert.html?type="+type;
    })

    function widreload() {
        /*window.location.reload();*/
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index);
    }

    function GetQueryString(name)
    {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    };

});
