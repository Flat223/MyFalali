define(function(require){
    var $ = require('jquery');
    var layer;
    require('/layui/layui.js');
    if (window.layui) {
        layui.config({
            dir: '/layui/'
        });
        layui.use(['layer', 'element'], function () {
            layer = layui.layer;
        });
        layui.use('form', function () {
            var form = layui.form();
        });
    }
});