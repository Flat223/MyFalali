define(function(require){
    var $ = require('jquery');
    var layer = require('layer/layer.js');
    require('easyui/jquery.easyui.min.js');
    require('easyui/locale/easyui-lang-zh_CN.js');
    require('areaJson.js');
    require('json2select.min.js');
    layer.config({
        path:'/js/layer/'
    });

    $("#select").json2select(areaJson,['','',''],"city");
    
    /*$("#addorg").click(function () {
        layer.prompt({title: '输入公司名称', formType: 2}, function(text, index){
            layer.close(index);
            $("#belong").find("option:selected").text(text);
        });
    })*/

    $("#belong").combobox({
        valueField:'name',
        textField:'name',
        panelWidth:200,
        panelHeight:'auto',
        onChange:function(value){
            $("#belong").combobox("reload","/service/GetCompanyBySearchServ.html?name="+value.trim());
        }
    });


    var totalTime = 60;
    var clickAble = true;
    var t;
    $("#vaildateCode").click(function(){
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
                        $('#vaildateCode').text(totalTime+"秒后重新获取");
                        if(totalTime == 0)
                        {
                            $('#vaildateCode').text("获取验证码");
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


    /*$("#address").blur(function(){
        var address = $("#address").val();
        // 创建地址解析器实例
        var myGeo = new BMap.Geocoder();
        // 将地址解析结果显示在地图上,并调整地图视野
        //region0+region1+region2+address
        myGeo.getPoint(region0+region1+region2+address, function(point){
            if (point) {
                map.centerAndZoom(point, 16);
                map.addOverlay(new BMap.Marker(point));
            }
        },region1);
        var localSearch = new BMap.LocalSearch(map);
        localSearch.enableAutoViewport(); //允许自动调节窗体大小
        localSearch.setSearchCompleteCallback(function (searchResult) {
            var poi = searchResult.getPoi(0);
            var lat = poi.point.lng + "," + poi.point.lat; //获取经度和纬度，将结果显示在文本框中
            $("#latlon").val(lat);
        });
        localSearch.search(region0+region1+region2+address);
    });*/

    $("#sub").click(function () {
        var city0 = document.getElementsByName("city0")[0].value;
        var city1 = null;
        var city2 = null;
        if(city0 != ""){
            city1 = document.getElementsByName("city1")[0].value;
            if(city1 != ""){
                city2 = document.getElementsByName("city2")[0].value;
            }
        }
        var labName = $("#labName").val();
        var manage = $("#manage").val();
        var phone = $("#phone").val();
        var validate = $("#validate").val();
        var serviceArea = $("#serviceArea").val();
        var belong = $('#belong').combobox('getValue');
        var registerDate = $('#registerDate').datebox('getValue');
        /*01/34/67*/
        if(registerDate.indexOf("/") > 0 ) {
            var mm = registerDate.substr(0,2);
            var dd = registerDate.substr(3,2);
            var yy = registerDate.substr(6,4);
            registerDate = yy+"-"+mm+"-"+dd;
        }
        var labDesc = $("#labDesc").val();
        var serviceRange = $("#serviceRange").val();
        var rules = $("#rules").val();
        var research = $("#research").val();
        var address = $("#address").val();
        var instrument = $("#instrument").val();
        if(labName == null || labName == "" || manage == null || manage == "" || phone == null || phone == "" || validate == null || validate == "" || serviceArea == null || serviceArea == "" || registerDate == null || registerDate == ""){
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
        if(registerDate != null || registerDate != ""){
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
            registerDate = Date.parse(new Date(registerDate))/1000;
            if(registerDate > currentdate){
                layer.alert("请填写正确的注册时间！", {offset: '200px'});
                window.scrollTo(100,100);
                return false;
            }
        }

        $.ajax({
            type: "post",
            dataType: "json",
            url: "/service/LabReleaseServ.html",
            data: {"labName":labName,"manage":manage,"phone":phone,"validate":validate,"serviceArea":serviceArea,"belong":belong,"registerDate":registerDate,
                "labDesc":labDesc,"serviceRange":serviceRange,"rules":rules,"research":research,"address":address,"instrument":instrument,"city0":city0,"city1":city1,"city2":city2},
            success: function (data) {
                if(data.ret == 1) {
                   layer.alert(data.msg, {offset: '200px'});
                    window.setTimeout(widreload,1200);
                }else if (data.ret == -1) {
                    layer.alert(data.msg, {offset: '200px'});
                }else if(data.ret == -2){
                    layer.alert(data.msg, {offset: '200px'});
                    window.scrollTo(100,100);
                }else if(data.ret == -3){
                    layer.alert(data.msg, {offset: '200px'});
                }else if(data.ret == -4){
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
