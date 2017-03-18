seajs.config({
    base : 'http://luqiwang.com/js/',       //seajs支持的base路径
    alias : {           //路径配置
        '$' : 'jquery.js',
        'jquery' : 'jquery.js',
	    'layer':'layer/layer.js',
	    'jplayer':'jplayer/jplayer/jquery.jplayer.min.js',
    },
    preload:['jquery']
});

