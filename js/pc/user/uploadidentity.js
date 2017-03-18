var totalTime = 60;
var clickAble = true;
var t;
$("#identity").click(function () {
    if (!clickAble) {
        return;
    }
    clickAble = false;

    var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
    var mob = $('input[name=identitymobile]').val();
    if (!reg.test(mob)) {
        alert("请输入正确的手机号码");
        clickAble = true;
        return;
    }
    var url = "http://www.ichuk.com/?api/sendsmsverifycode/e75ce5d42105d8e581327164f8e860/1";

    $.ajax({
        type: "GET",
        url: url,
        data: {"stage": "实验圈", "mobile": mob, "platform": "WEB", "usage": "identity"},
        dataType: "json",
        success: function (data) {
            if (data.ret == 1) {
                t = setInterval(function () {
                    totalTime--;
                    $('#identity').text(totalTime + "秒后重新获取");
                    if (totalTime == 0) {
                        $('#identity').text("获取短信验证码");
                        totalTime = 60;
                        clickAble = true;
                        clearTimeout(t);
                    }
                }, 1000);
            }
            else {
                clickAble = true;
            }
        },
        error: function (data) {
            alert("error");
        }
    });
})

$('input[name=identitysubmit]').on("click", function () {
    var post = {};
    var userid = $('input[name=identityuser]').val();
    var name = $('input[name=realname]').val();
    var cardstyle = $('select[name=cardstyle]').val();
    var cardnum = $('input[name=cardnum]').val();
    var mobile = $('input[name=identitymobile]').val();
    var code = $('input[name=identitycode]').val();
    var cardimg=$("#images")[0].src;
    var isIDCard1=/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/;
    var isIDCard2= /^[a-zA-Z0-9]{5,17}$/;
    var mob = $('input[name=identitymobile]').val();
    if (cardstyle=='1' && !isIDCard1.test(cardnum)) {
        alert("请输入正确的身份证号");
        clickAble = true;
        return;
    }
    if (cardstyle=='2' && !isIDCard2.test(cardnum)) {
        alert("请输入正确的护照号");
        clickAble = true;
        return;
    }
    if(cardimg.length<10){
        alert("请上传证件图片");
        return;
    }
    post.userid = userid;
    post.name = name;
    post.cardstyle = cardstyle;
    post.cardnum = cardnum;
    post.mobile = mobile;
    post.code = code;
    post.cardimg=cardimg;
    var url = "/service/UploadidentityServ.html";
    $.ajax({
        type: "POST",
        url: url,
        data: post,
        dataType: "json",
        success: function (data) {
            if (data.ret == '1') {
                alert(data.msg);
                window.location.reload(-1);
            } else {
                alert(data.msg);
            }
        },
        error: function (data) {
            alert("error");
        }
    });
})

$("#repasswdcode").click(function(){
    if(!clickAble)
    {
        return;
    }
    clickAble = false;

    var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;

    var mobile = $('input[name=mobilecode]').val();
    if(!reg.test(mobile)) {
        alert('请输入正确的手机号');
        clickAble = true;
        return;
    }
    var url = "http://www.ichuk.com/?api/sendsmsverifycode/e75ce5d42105d8e581327164f8e860/1";

    $.ajax({
        type: "GET",
        url: url,
        data:{"stage":"实验圈","mobile":mobile,"platform":"WEB","usage":"repasswd"},
        dataType: "json",
        success : function(data){
            if(data.ret == 1){
                t = setInterval(function(){
                    totalTime--;
                    $('#repasswdcode').text(totalTime+"秒后重新获取");
                    if(totalTime == 0)
                    {
                        $('#repasswdcode').text("获取验证码");
                        totalTime = 60;
                        clickAble = true;
                        clearTimeout(t);
                    }
                },1000);
            }else{
                layer.alert(data.msg,{offset:'200px'});
                clickAble = true;
            }
        },
        error:function(data){
            layer.alert('服务器错误,请稍后再试',{offset:'200px'});
        }
    });
})

//修改密码
$('input[name=repasswd]').click(function(){
    var post={};
    var mobile = $('input[name=mobilecode]').val();
    var messagecode=$('input[name=messagecode]').val();
    var oldpassword=$('input[name=oldpassword]').val();
    var newpassword=$('input[name=newpassword]').val();
    var renewpassword=$('input[name=renewpassword]').val();
    if(newpassword.length<=0){
        alert('请输入新密码');
        return false;
    }
    if(newpassword!==renewpassword){
        alert('两次输入的密码不一致');
        return false;
    }
    post.mobile=mobile;
    post.messagecode=messagecode;
    post.newpassword=newpassword;
    var url='/service/RepasswordBycodeServ.html';
    var url2='/service/RepasswordBypasswordServ.html';
    var list= $('input:radio[name="logintype"]:checked').val();
    if(list=='1') {
        $.ajax({
            type: "POST",
            url: url,
            data: post,
            dataType: "json",
            success: function (data) {
                if (data.ret == '1') {
                    alert(data.msg);
                    window.location.reload(-1);
                } else {
                    alert(data.msg);
                }
            },
            error: function (data) {
                alert("error");
            }
        });
    }else{
        $.ajax({
            type: "POST",
            url: url2,
            data: {"mobile":mobile,"oldpassword":oldpassword,"newpassword":newpassword},
            dataType: "json",
            success: function (data) {
                if (data.ret == '1') {
                    alert(data.msg);
                    window.location.reload(-1);
                } else {
                    alert(data.msg);
                }
            },
            error: function (data) {
                alert("error");
            }
        });
    }
})

$(".na-ul li").on("click",function(){
    $(this).addClass("na-li-this").siblings().removeClass("na-li-this");
    var type = $(this).attr('invoice_type');
    var params = {};
    params.type = type;
    $.ajax({
        type:"post",
        dataType:"json",
        url:"/service/GetInvoiceListServ.html",
        data:params,
        success:function(data){
            if(data.ret == 0){
//                 alert(data.msg);
                $(".invoice_container").html("");
            } else {
                var invoiceArray = data.invoice;
                reloadHtml(invoiceArray);
            }
        },
        error:function(data){
            alert("服务器错误,请稍后再试");
        },
        complete:function(){

        }
    });
});

$('.delete').on('click',function(){
    var params = {};
    var rid = $(this).attr('rid');
    params.rid = rid;
    $.ajax({
        type:"post",
        dataType:"json",
        url:"/service/deleteInvoiceServ.html",
        data:params,
        success:function(data){
            if(data.ret == 0){
                alert(data.msg);
            } else {
                window.location.href = "/user/account.html?type=3";
            }
        },
        error:function(data){
            alert("服务器错误,请稍后再试");
        },
        complete:function(){

        }
    });
});


function reloadHtml(invoiceArray){
    var str = "";
    for(var i=0;i<invoiceArray.length;i++){
        var invoice = invoiceArray[i];
        str = str + "<tr>\
            <td>" + invoice.invoice_code + "</td>\
            <td>" + invoice.title + "</td>\
            <td></td>\
            <td></td>\
            <td></td>\
            <td class=\'delete\'>删除</td>\
        </tr>";
    }
    $(".invoice_container").html(str);
}




