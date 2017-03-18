define(function(require){
    var $ = require('jquery');
    var layer = require('layer/layer.js');
    layer.config({
        path:'/js/layer/'
    });
    require('cyupload.js');
    var area = require('area.js');

    var realProvince = $(".s_province").attr('real');
    var realCity = $(".s_city").attr('real');
    var realCountry = $(".s_country").attr('real');

    var reg = /^\d{17}([0-9]|X)$/;
    var handle = {
        init:function(){
            area.init("s_province","s_city","s_country",realProvince,realCity,realCountry);

            $("#save").on("click",function () {
                data={};
                var sid=$("input[name=sid]").val();
                var logo=$('#images')[0].src;
                var shop_name=$("input[name=shop_name]").val();
                var mobile=$("input[name=tel]").val();
                var address=$("input[name=address]").val();
                var zip=$("input[name=zip]").val();
                var province_id = $(".s_province").val();
                var city_id = $(".s_city").val();
                var district = $(".s_country").val();
                data.sid=sid;
                data.logo=logo;
                data.shop_name=shop_name;
                data.mobile=mobile;
                data.address=address;
                data.zip=zip;
                data.province_id=province_id;
                data.city_id=city_id;
                data.district=district;
                console.log(data);
                $.ajax({
                    type:"post",
                    dataType:"json",
                    url:"/service/UpdateshopinfoServ.html",
                    data:data,
                    success:function(data){
                        if(data.ret == 1){
                            layer.alert(data.msg,{offset:'200px'},function(){
                                window.location.reload(true);
                            });
                        } else {
                            layer.alert(data.msg);
                        }
                    },
                    error:function(data){
                        layer.alert('服务器错误,请稍后再试',{offset:'200px'});
                    },
                    complete:function(){

                    }
                });
            })

            $.cyupload({
                elem: '#images',
                btnName: "请选择",		//按键名称
                infoElementId: "",	//上传状态信息包装元素id
                maxFilesize: 10485760,
                uploadUrl: '/service/uploadimgServ.html',
                fileFilter: '',
                upfileParam: 'upload_file_input',
                success: function (url) {
                    $('#images').attr('src', url['file_url']);
                }
            });
        },

    };

    $(function(){
        handle.init();
    });
});