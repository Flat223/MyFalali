define(function(require){
    var $ = require('jquery');
    var layer = require('layer/layer.js');
    require('easyui/jquery.easyui.min.js');
    require('easyui/locale/easyui-lang-zh_CN.js');
    layer.config({
        path:'/js/layer/'
    });


    var totalTime = 60;
    var clickAble = true;
    var t;
    $("#getCode").click(function(){
        if(!clickAble) {
            return false;
        }
        clickAble = false;
        var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        var mobile = $("#phone").val();
        if(!reg.test(mobile)) {
            layer.alert('请输入正确的手机号码！');
            $("#phone").val("");
            clickAble = true;
            return false;
        }
        var url = "http://www.ichuk.com/?api/sendsmsverifycode/e75ce5d42105d8e581327164f8e860/1";
        $.ajax({
            type: "GET",
            url: url,
            data:{"stage":"实验圈","mobile":mobile,"platform":"WEB","usage":"repasswd"},
            dataType: "json",
            success : function(data){
                if(data.ret == 1)
                {
                    t = setInterval(function(){
                        totalTime--;
                        $('#getCode').text(totalTime+"秒后重新获取");
                        if(totalTime == 0)
                        {
                            $('#getCode').text("获取验证码");
                            totalTime = 60;
                            clickAble = true;
                            clearTimeout(t);
                        }
                    },1000);
                }
                else {
                    clickAble = true;
                }
            },
            error:function(data){
                layer.alert("error");
            }
        });
    });


    $("#subtn").click(function () {
        var type = $('input[name="titType"]:checked ').val();
        var title = $("#title").val();
        var endDate = $('#endDate').datebox('getValue');
        /*01/34/67*/
        if(endDate.indexOf("/") > 0 ) {
            var mm = endDate.substr(0,2);
            var dd = endDate.substr(3,2);
            var yy = endDate.substr(6,4);
            endDate = yy+"-"+mm+"-"+dd;
        }
        var linkermen = $("#linkermen").val();
        var phone = $("#phone").val();
        var validateCode = $("#validateCode").val();
        var mail = $("#mail").val();
        var category = $("#category").val();
        var trade = $("#trade").val();
        var desc = $("#desc").val();

        if(type == null || type == "" || title == null || title == "" || endDate == null || endDate == "" || linkermen == null || linkermen == "" || phone == null || phone == "" || validateCode == null || validateCode == ""){
            layer.alert("必填项不能为空！", {offset: '200px'});
            window.scrollTo(100,100);
            return false;
        }
        var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        if (!reg.test(phone)) {
            layer.alert("请输入正确的手机号码！");
            $("#phone").val("");
            window.scrollTo(100,100);
            return false;
        }
        var reg1 = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
        if(mail != ""){
            if (!reg1.test(mail)) {
                layer.alert("请输入正确的邮箱！");
                $("#mail").val("");
                window.scrollTo(100,100);
                return false;
            }
        }
        var date = new Date();
        var seperator1 = "-";
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var currentdate = year + seperator1 + month + seperator1 + strDate;
        currentdate = Date.parse(new Date(currentdate))/1000;
        endDate = Date.parse(new Date(endDate))/1000;
        if(endDate < currentdate){
            layer.alert("请填写正确的过期时间！", {offset: '200px'});
            window.scrollTo(100,100);
            return false;
        }

        $.ajax({
            type: "post",
            dataType: "json",
            url: "/service/SupplyReleaseServ.html",
            data: {"type":type,"trade":trade,"title":title,"endDate":endDate,"linkermen":linkermen,
                "phone":phone,"validateCode":validateCode,"mail":mail,"category":category,"desc":desc},
            success: function (data) {
                if(data.ret == 1) {
                   layer.alert(data.msg, {offset: '200px'});
                    window.setTimeout(widreload,1200);
                }else if (data.ret == -1) {
                    layer.alert(data.msg, {offset: '200px'});
                }else if (data.ret == -2) {
                    layer.alert(data.msg, {offset: '200px'});
                    window.scrollTo(100,100);
                }else if (data.ret == -3) {
                    layer.alert(data.msg, {offset: '200px'});
                }
            },
            error: function (data) {
                layer.alert('服务器错误,请稍后再试', {offset: '200px'});
            }
        });
    });

    function widreload() {
        window.location.reload();
    }
});
