$(function () {

    // $(".menu-wrapp li").on("click",function() {
    //     $(this).find("ul").slideToggle("slow");
    // });

    /*$("#member").on("change",function() {
        var text = $("#member").val();
        alert(text);
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/service/GetShopMemberServ.html",
            data: {"text":text},
            success: function (users) {
                for(var i = 0;i<users.length;i++){
                    var str = "<li class='lab_li'>";
                        str += "<div>"+users[i].name+"</div>";
                        str += "</li>";
                    $("#result").append(str);
                }
                $("#result").show();
            }
        });
    });*/

    $("#searchBtn").on("click",function () {
        var resinfo = $("#data").val();
        window.location.href="/goodsmanager/shop.html?data="+resinfo;
    });

    $("#member").combobox({
        valueField:'name',
        textField:'name',
        panelWidth:200,
        panelHeight:'auto',
        onChange:function(value){
            $("#member").combobox("reload","/service/GetShopMemberServ.html?name="+value.trim());
        }
    });


    $(".del").on("click",function() {
        var id = $(this).attr('value');
        layer.confirm('确定要删除吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/DeleteShopServ.html",
                data: {"id":id},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.location.href="/goodsmanager/shop.html";
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

    $("#select").json2select(areaJson,['','',''],"city");

    $(".spSave").on("click",function() {
        var spname = $("#spname").val();
        var address = $("#address").val();
        var phone = $("#phone").val();
        var member = $('#member').combobox('getValue');
        var city0 = document.getElementsByName("city0")[0].value;
        var city1 = null;
        var city2 = null;
        if(city0 != ""){
            city1 = document.getElementsByName("city1")[0].value;
            if(city1 != ""){
                city2 = document.getElementsByName("city2")[0].value;
            }
        }
        if(spname == "" || spname == null || phone == "" || phone == null){
            layer.msg("有未填写的信息！", {icon: 2});
            return false;
        }
        var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        if (!reg.test(phone)) {
            layer.alert("请输入正确的手机号码！",{offset: '200px'});
            $("#phone").val("");
            return false;
        }
        var addr = city0+city1+city2+address;
        /*alert(spname);
        alert(addr);
        alert(phone);
        alert(member);return;*/
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/service/EditShopServ.html",
            data: {"name":spname,"address":addr,"member":member,"phone":phone},
            success: function (data) {
                if(data.ret == 1){
                    layer.msg(data.msg, {icon: 1});
                    window.setTimeout(widreload,500);
                }else if(data.ret == -1){
                    layer.msg(data.msg, {icon: 2});
                }else if(data.ret == -2){
                    layer.msg(data.msg, {icon: 2});
                }else if(data.ret == -3){
                    layer.msg(data.msg, {icon: 2});
                }else if(data.ret == -4){
                    layer.msg(data.msg, {icon: 2});
                }
            },
            error:function (msg) {
                layer.msg('操作失败!', {icon: 2});
            }
        });
    });

    /*$(".check").on("click",function() {
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
    });*/

    function widreload() {
        window.location.reload();
    }

});
