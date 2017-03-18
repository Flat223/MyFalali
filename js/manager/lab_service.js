$(function () {

    $(".menu-wrapp li").on("click",function() {
        $(this).find("ul").slideToggle("slow");
    });

    function GetQueryString(name) {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    };
    var data = GetQueryString('data');

    $("#lab").combobox({
        valueField:'name',
        textField:'name',
        panelWidth:200,
        panelHeight:'auto',
        onChange:function(value){
            $("#lab").combobox("reload","/service/GetLabServ.html?name="+value.trim());
        }
    });

    $("#serins").on("click",function () {
        var resinfo = $("#strResult").val();
        if(resinfo == ""){
            layer.msg("请输入搜索内容", {icon: 2});
            return;
        }
        window.location.href="/labmanager/labInstrument.html?data="+resinfo;
    });

    $("#seaser").on("click",function () {
        var resinfo = $("#serResult").val();
        if(resinfo == ""){
            layer.msg("请输入搜索内容", {icon: 2});
            return;
        }
        window.location.href="/labmanager/labServiceRange.html?data="+resinfo;
    });

    $(".del").on("click",function() {
        var id = $(this).attr('value');
        layer.confirm('确定要删除吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/LabServiceIRServ.html",
                data: {"id":id},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.setTimeout(widreload,500);
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

    $(".serSave").on("click",function() {
        var id = $("#sid").val();
        var type = $("#typeVal").val();
        var name = $("#insname").val();
        var cycle = $("#inscycle").val();
        var price = $("#insprice").val();
        var lab = $("#lab").combobox('getValue');
        if(lab == null || lab == "" || name == "" || name == null || cycle == "" || cycle == null || price == "" || price == null){
            layer.msg("有未填写的信息！", {icon: 2});
            return false;
        }
        if(isNaN(price)){
            layer.msg("请输入正确的价格！", {icon: 2});
            return false;
        }
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/service/EditLabServiceServ.html",
            data: {"id":id,"name":name,"cycle":cycle,"price":price,"lab":lab,"type":type},
            success: function (data) {
                if(data.ret == 1){
                    layer.msg(data.msg, {icon: 1});
                    window.setTimeout(widreload,500);
                }else if(data.ret == -1){
                    layer.msg(data.msg, {icon: 2});
                }else if(data.ret == -2){
                    layer.msg(data.msg, {icon: 2});
                }
            },
            error:function (msg) {
                layer.msg('操作失败!', {icon: 2});
            }
        });
    });

    $(".check").on("click",function() {
        var id = $(this).attr('value');
        layer.confirm('对该条记录进行审核', {
            btn: ['通过','取消'] //按钮
        }, function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/LabListingServ.html",
                data: {"id":id,'check':1},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.setTimeout(widreload,500);
                    }else if(data.ret == -1){
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error:function (msg) {
                    layer.msg('审核失败!', {icon: 2});
                }
            });
        }, function(){
            layer.msg('', {
                time: 100
            });
        });
    });

    function widreload() {
        window.location.reload();
    }

});
