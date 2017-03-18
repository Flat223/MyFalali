$(function () {

    $(".menu-wrapp li").on("click",function() {
        $(this).find("ul").slideToggle("slow");
    });

    /*var ptype = $("#ptype").val();
    $("#fir option").each(function () {
        if($(this).attr('value') == cptype){

        }
    })*/

    $("#org").combobox({
        valueField:'name',
        textField:'name',
        panelWidth:200,
        panelHeight:'auto',
        onChange:function(value){
            $("#org").combobox("reload","/service/GetOrgServ.html?name="+value.trim());
        }
    });

    $("#fir").on("change",function () {
       var tid = $(this).val();
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/service/GetTypeServ.html",
            data: {"tid":tid},
            success: function (data) {
                if(data != null){
                    $("#bef").hide();
                    $("#sec").show();
                    $("#sec").empty();
                    for (var i = 0; i<data.length;i++){
                        var str = "<option value='"+data[i].lab_tid+"'>"+data[i].name+"</option>";
                        $("#sec").append(str);
                    }
                }
            },
            error:function (msg) {
                alert(msg);
            }
        });
    });

    function hasDot(num) {
        if (!isNaN(num)) {
            return ((num + '').indexOf('.') != -1) ? true : false;
        }
    }

    $(".score").on("blur",function () {
        var zyx = $("#zyx").val();
        var kyhj = $("#kyhj").val();
        var jlx = $("#jlx").val();
        var kyry = $("#kyry").val();
        if(zyx > 10 || zyx < 0 || hasDot(zyx)){
            layer.alert("请输入小于等于10的正整数！",{offset: '200px'});
            $("#zyx").val(0);
            return false;
        }
        if(kyhj > 10 || zyx < 0 || hasDot(kyhj)){
            layer.alert("请输入小于等于10的正整数！",{offset: '200px'});
            $("#kyhj").val(0);
            return false;
        }
        if(jlx > 10 || zyx < 0 || hasDot(jlx)){
            layer.alert("请输入小于等于10的正整数！",{offset: '200px'});
            $("#jlx").val(0);
            return false;
        }
        if(kyry > 10 || zyx < 0 || hasDot(kyry)){
            layer.alert("请输入小于等于10的正整数！",{offset: '200px'});
            $("#kyry").val(0);
            return false;
        }
        var total = (parseInt(zyx)+parseInt(kyhj)+parseInt(jlx)+parseInt(kyry))/4;
        if(hasDot(total)){
            $("#total").val(Math.ceil(total));
        }else{
            $("#total").val(total);
        }
    });

    $("#sbtn").on("click",function () {
        var labid = $("#labid").val();
        var name = $("#name").val();
        var manager = $("#manager").val();
        var phone = $("#phone").val();
        var zyx = $("#zyx").val();
        var kyhj = $("#kyhj").val();
        var jlx = $("#jlx").val();
        var kyry = $("#kyry").val();
        var lat = $("#lat").val();
        var lon = $("#lon").val();
        var view_num = $("#view_num").val();
        var service_area = $("#service_area").val();
        var tot = $("#total").val();
        var type = null;
        if($("#sec").val() == null){
            type = $("#fir").val();
        }else{
            type = $("#sec").val();
        }
        var org = $('#org').combobox('getValue');
        var address = $("#address").val();
        var rules = $("#rules").val();
        var intro = $("#intro").val();
        var logo = "";
        var logo1 =$("#logo1")[0].src;
        logo = logo1;
        var logo2 = null;
        var logo3 = null;
        if(logo1 != null){
            logo2 = $("#logo2")[0].src;
            logo = logo1+","+logo2;
            if(logo2 != null){
                logo3 = $("#logo3")[0].src;
                logo = logo1+","+logo2+","+logo3;
            }
        }

        if(name == null || name == ""){
            layer.alert('名称不可为空',{offset: '200px'});
            return false;
        }
        var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        if (!reg.test(phone)) {
            layer.alert("请输入正确的手机号码！",{offset: '200px'});
            $("#phone").val("");
            return false;
        }

        $.ajax({
            type: "post",
            dataType: "json",
            url: "/service/LabInfoServ.html",
            data: {"name":name,"manager":manager,"phone":phone,"zyx":zyx,"kyhj":kyhj,"jlx":jlx,"kyry":kyry,"view_num":view_num,"labid":labid,"logo":logo,
                "service_area":service_area,"type":type,"org":org,"address":address,"rules":rules,"intro":intro,"lat":lat,"lon":lon,"total":tot},
            success: function (data) {
                if(data.ret == 1) {
                    layer.alert(data.msg, {offset: '200px'});
                    window.setTimeout(widreload,1000);
                }else if(data.ret == -1){
                    layer.alert(data.msg, {offset: '200px'});
                }else if(data.ret == -2){
                    layer.alert(data.msg, {offset: '200px'});
                }
            }
        });
    });

    function widreload() {
        window.location.reload();
    }

    $.cyupload({
        elem:'#upimg',
        btnName:"选择图片",		//按键名称
        infoElementId:"",	//上传状态信息包装元素id
        maxFilesize:10485760,
        uploadUrl:'/service/uploadimgServ.html',
        fileFilter:'',
        upfileParam:'upload_file_input',
        success:function(url){
            for(var i=1;i<4;i++){
                if($("#logo"+i)[0].src=="http://d31.ichuk.com/images/pc/upload.jpg"){
                    $("#logo"+i)[0].src=url['file_url'];
                    return;
                }
            }
        }
    });

});
