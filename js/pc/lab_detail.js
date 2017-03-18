define(function(require) {
    var $ = require('jquery');
    require('swiper.min.js');
    require('pc/rating.js');
    require('pc/dimmer.js');
    var layer = require('layer/layer.js');
    layer.config({
        path:'/js/layer/'
    });

    var swiper = new Swiper('.banner .swiper-container', {
        pagination: '.swiper-pagination',
        slidesPerView: 1,
        paginationClickable: true,
        spaceBetween: 30,
        loop: true,
        autoplay : 8000
    });
    $('.special.card .image').dimmer({
        on: 'hover'
    });
    $('.star.rating').rating();
    $('.card .dimmer').dimmer({
        on: 'hover'
    });

    $("#yuyue").on("click",function () {
        $.ajax({
            type:"post",
            dataType:"json",
            url:"/service/BespeakServ.html",
            success:function(data){
                if(data.ret == 1){
                    layer.alert(data.msg);
                }else if(data.ret == -1){
                    layer.msg(data.msg,{icon:2});
                }
            }
        });
    });

    $(".expertInfo").on("click",function() {
        var id = $(this).attr('value');
        layer.open({
            type: 2,
            title: '专家信息',
            shadeClose: true,
            shade: 0.8,
            area: ['500px', '60%'],
            content: '/lab/expertInfo.html?id='+id
        });
    });

    var handle = {
        init:function() {
            $(".labintro .bartitle #more").on("click", function () {
                if ($(".labintro .bartitle + section").hasClass("fadeauto")) {
                    $(".labintro .bartitle + section").removeClass("fadeauto");
                    var test = document.getElementById("more");
                    test.innerHTML = "&gt;&gt;更多";
                } else {
                    $(".labintro .bartitle + section").addClass("fadeauto");
                    var test = document.getElementById("more");
                    test.innerHTML = "&gt;&gt;收起";
                }
            })
            $(".service-range p #more1").on("click", function () {
                if ($(".service-range p + section").hasClass("fadeauto")) {
                    $(".service-range p + section").removeClass("fadeauto");
                    var test = document.getElementById("more1");
                    test.innerHTML = "&gt;&gt;更多";
                } else {
                    $(".service-range p + section").addClass("fadeauto");
                    var test = document.getElementById("more1");
                    test.innerHTML = "&gt;&gt;收起";
                }
            })

            $(".apparatus p #more2").on("click", function () {
                if ($(".apparatus p + section").hasClass("fadeauto")) {
                    $(".apparatus p + section").removeClass("fadeauto");
                    var test = document.getElementById("more2");

                    test.innerHTML = "&gt;&gt;更多";
                } else {
                    $(".apparatus p + section").addClass("fadeauto");
                    var test = document.getElementById("more2");
                    test.innerHTML = "&gt;&gt;收起";
                }
            })
            $(".professor .bartitle #more3").on("click", function () {
                if ($(".professor .bartitle + section").hasClass("fadeauto")) {
                    $(".professor .bartitle + section").removeClass("fadeauto");
                    var test = document.getElementById("more3");
                    test.innerHTML = "&gt;&gt;更多";
                } else {
                    $(".professor .bartitle + section").addClass("fadeauto");
                    var test = document.getElementById("more3");
                    test.innerHTML = "&gt;&gt;收起";
                }
            })
            $(".contact-s i").on("click", function () {
                $(".contact-s").fadeOut("slow");
            });

            $("span[name='consultation']").on("click", function () {
                var name = $(this).attr('value');
                var cycle = $(this).attr('value1');
                var price = $(this).attr('value2');
                var serviceWindow = document.getElementById("serviceWindow");
                if (serviceWindow.style.display == "none") {
                    serviceWindow.style.display = "block";
                }
                $("#service_name").val(name);
                $("#service_cycle").val(cycle);
                $("#service_price").val(price);
            });
            $("span[name='consultation_instr']").on("click", function () {
                var name = $(this).attr('value');
                var cycle = $(this).attr('value1');
                var price = $(this).attr('value2');
                var serviceWindow = document.getElementById("serviceWindow");
                if (serviceWindow.style.display == "none") {
                    serviceWindow.style.display = "block";
                }
                $("#service_name").val(name);
                $("#service_cycle").val(cycle);
                $("#service_price").val(price);
            });
        }
    };
    //scrollTop
//创建和初始化地图函数：
    function initMap(){
        createMap();//创建地图
        setMapEvent();//设置地图事件
        addMapControl();//向地图添加控件
        addMarker();//向地图中添加marker
    };

    //创建地图函数：
    function createMap(){
        var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
        var point = new BMap.Point($("#lon").val(),$("#lat").val());//定义一个中心点坐标
        map.centerAndZoom(point,14);//设定地图的中心点和坐标并将地图显示在地图容器中
        window.map = map;//将map变量存储在全局
    };

    //地图事件设置函数：
    function setMapEvent(){
        map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
        map.enableScrollWheelZoom();//启用地图滚轮放大缩小
        map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
        map.enableKeyboard();//启用键盘上下左右键移动地图
    };

    //地图控件添加函数：
    function addMapControl(){
        //向地图中添加缩放控件
        var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
        map.addControl(ctrl_nav);
        //向地图中添加缩略图控件
        var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:1});
        map.addControl(ctrl_ove);
        //向地图中添加比例尺控件
        var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
        map.addControl(ctrl_sca);
    };

    //标注点数组
    var markerArr = {name:$("#labName").val(),address:$("#address").val(),lon:$("#lon").val(),lat:$("#lat").val(),phone:$("#phone").val()};
    var marker = new Array();
    //创建marker
    function addMarker() {
        var p0 = markerArr.lon; //
        var p1 = markerArr.lat; //按照原数组的point格式将地图点坐标的经纬度分别提出来
        var point = new window.BMap.Point(p0, p1); //循环生成新的地图点
        var marker = new window.BMap.Marker(point); //按照地图点坐标生成标记
        map.addOverlay(marker);
        marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
        var label = new window.BMap.Label(markerArr.name, { offset: new window.BMap.Size(20, -10) });
        marker.setLabel(label);
        var info = new window.BMap.InfoWindow("<p style=’font-size:12px;lineheight:1.8em;’>" + markerArr.name + "</br>地址：" + markerArr.address + "</br> 电话：" + markerArr.phone + "</br></p>"); // 创建信息窗口对象

        marker.addEventListener("mouseover", function () {
            this.openInfoWindow(info);
        });
    }

    initMap();//创建和初始化地图//

    $(function(){
        handle.init();
    });
 });


