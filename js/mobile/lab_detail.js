$(function () {
    function initMap(){
        createMap();//创建地图
        setMapEvent();//设置地图事件
        addMapControl();//向地图添加控件
        addMarker();//向地图中添加marker
    }

    //创建地图函数：
    function createMap(){
        var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
        var point = new BMap.Point($("#lon").val(),$("#lat").val());//定义一个中心点坐标
        map.centerAndZoom(point,14);//设定地图的中心点和坐标并将地图显示在地图容器中
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
        var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:1});
        map.addControl(ctrl_ove);
        //向地图中添加比例尺控件
        var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
        map.addControl(ctrl_sca);
    }

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
});