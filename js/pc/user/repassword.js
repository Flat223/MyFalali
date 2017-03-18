//获取短信验证码
var totalTime = 60;
var clickAble = true;
var t;
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
