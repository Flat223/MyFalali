define(function(require) {
    var $ = require('jquery');
    require('jquery.SuperSlide.2.1.1')($);
    require('pc/public.js');
    require('pc/index.js');
    var layer = require('layer/layer.js');
    layer.config({
        path:'/js/layer/'
    });

    //banner
    $(".slideBox").slide({mainCell:".bd ul",effect:"left",autoPlay:true});

    function GetQueryString(name) {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    }
    var cate = GetQueryString('cate');

    $(".heart").click(function () {
        var id = $(this).attr('value');
        $.ajax({
            type:"get",
            dataType:"json",
            url:"/service/CollectionLabServ.html",
            data:{"id":id},
            success:function(data) {
                if(data.ret == 1){
                    layer.alert(data.msg, {icon: 1});
                    $(".ht"+id).attr('src','/images/temp_pc/list-heart.png');
                }else if(data.ret == -1){
                    layer.alert(data.msg, {icon: 2});
                }else if(data.ret == -2){
                    layer.alert(data.msg, {icon: 2});
                }else if(data.ret == -3){
                    layer.alert(data.msg, {icon: 6});
                }
            },
            error:function () {
                layer.alert("服务器繁忙", {icon: 2});
            }
        });
    });

    //创建和初始化地图函数：
    function initMap(){
        createMap();//创建地图
        setMapEvent();//设置地图事件
        addMapControl();//向地图添加控件
    }
    function idValue(id) {
        return document.getElementById(id);
    }
    //创建地图函数：
    function createMap(){
        var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
        /*var geolocation = new BMap.Geolocation();//根据浏览器定位
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                var mk = new BMap.Marker(r.point);
                var point = new BMap.Point(r.point.lng,r.point.lat);//自动定位*!/
                map.centerAndZoom(point,17);//设定地图的中心点和坐标并将地图显示在地图容器中
                map.addOverlay(mk);
                map.panTo(r.point);
            }
            else {
                alert('failed'+this.getStatus());
            }
        },{enableHighAccuracy: true})*/
        $.ajax({
            type:"get",
            dataType:"json",
            url:"/service/LabShareServ.html",
            success:function(data) {
                var markerArr = data;
                var point = new Array(); //存放标注点经纬信息的数组
                var marker = new Array(); //存放标注点对象的数组
                var info = new Array(); //存放提示信息窗口对象的数组
                for (var i = 0; i < markerArr.length; i++) {
                    var p0 = markerArr[i].lon; //
                    var p1 = markerArr[i].lat; //按照原数组的point格式将地图点坐标的经纬度分别提出来
                    point[i] = new window.BMap.Point(p0, p1); //循环生成新的地图点
                    marker[i] = new window.BMap.Marker(point[i]); //按照地图点坐标生成标记
                    map.addOverlay(marker[i]);
                    marker[i].setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
                    var label = new window.BMap.Label(markerArr[i].name, { offset: new window.BMap.Size(20, -10) });
                    marker[i].setLabel(label);
                    info[i] = new window.BMap.InfoWindow("<p style=’font-size:12px;lineheight:1.8em;’>" + markerArr[i].name + "</br>地址：" + markerArr[i].address + "</br> 电话：" + markerArr[i].manager_phone + "</br></p>"); // 创建信息窗口对象
                }
                /*for(var j = 0;j< markerArr.length;j++){
                    marker[j].addEventListener("mouseover", function () {
                        this.openInfoWindow(info[j]);
                    });
                }*/
                marker[0].addEventListener("mouseover", function () {
                    this.openInfoWindow(info[0]);
                });
                marker[1].addEventListener("mouseover", function () {
                    this.openInfoWindow(info[1]);
                });
                marker[2].addEventListener("mouseover", function () {
                    this.openInfoWindow(info[2]);
                });
                marker[3].addEventListener("mouseover", function () {
                    this.openInfoWindow(info[3]);
                });
                marker[4].addEventListener("mouseover", function () {
                    this.openInfoWindow(info[4]);
                });
            }
        });

    function myFun(result){
            var cityName = result.name;
            if(cityName != ""){
                map.centerAndZoom(cityName,13);// 用城市名设置地图中心点
            }
        }
        var myCity = new BMap.LocalCity();
        myCity.get(myFun);

        var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
            {"input" : "pointVal"
                ,"location" : map
            });
        ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
            var str = "";
            var _value = e.fromitem.value;
            var value = "";
            if (e.fromitem.index > -1) {
                value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            }
            str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;

            value = "";
            if (e.toitem.index > -1) {
                _value = e.toitem.value;
                value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            }
            str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
            idValue("searchResultPanel").innerHTML = str;
        });

        var myValue;
        ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
            var _value = e.item.value;
            myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            idValue("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;

            setPlace();
        });

        function setPlace(){
            map.clearOverlays();    //清除地图上所有覆盖物
            function myFun(){
                var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
                map.centerAndZoom(pp, 16);
                map.addOverlay(new BMap.Marker(pp));    //添加标注
            }
            var local = new BMap.LocalSearch(map, { //智能搜索
                onSearchComplete: myFun
            });
            local.search(myValue);
        }
       /* var point = new BMap.Point(120.739622,31.271497);//定义一个中心点坐标*!/
        map.centerAndZoom(point,17);//设定地图的中心点和坐标并将地图显示在地图容器中*/
        window.map = map;//将map变量存储在全局
    }

    //地图事件设置函数：
    function setMapEvent(){
        map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
        map.enableScrollWheelZoom();//启用地图滚轮放大缩小
        map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
        map.enableKeyboard();//启用键盘上下左右键移动地图
    }

    //地图控件添加函数：
    function addMapControl(){
        //向地图中添加缩放控件
        var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
        map.addControl(ctrl_nav);
        //向地图中添加缩略图控件
        var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT,isOpen:1});
        map.addControl(ctrl_ove);
        //向地图中添加比例尺控件
        var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT});
        map.addControl(ctrl_sca);
    }
    initMap();//创建和初始化地图
});