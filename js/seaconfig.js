seajs.config({
    base : '/js/',       //seajs支持的base路径
    alias : {           //路径配置
        '$' : 'jquery.min.js',
        'jquery' : 'jquery.min.js',
        'layui':'/layui/layui.js',
        'md5':'md5.js',
        'swiper':'/js/swiper.min.js'
    },
    preload:['jquery']
});

