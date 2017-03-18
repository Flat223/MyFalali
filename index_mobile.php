<?php
	if (version_compare(PHP_VERSION, '5.1.2', '>=')) {
	    //魔法函数，按需索取，系统性能提升30%
		spl_autoload_register("_autoload");
	} else {
	    /**
	     * Fall back to traditional autoload for old PHP versions
	     * @param string $classname The name of the class to load
	     */
	    function __autoload($classname)
	    {
	        _autoload($classname);
	    }
	}
	
	function _autoload($class_name) {
		$file = dirname(__FILE__).'/core/util/'.$class_name.'.php';
		if(file_exists($file)){
			require_once($file);
		}else{
// 			include(dirname(__FILE__).'/html/'.Theme.'/404.html');
			echo($class_name.'未找到');
			exit(0);
		}
	}

	include_once(dirname(__FILE__).'/core/iChuk.Config.php');//载入配置参数
	ini_set("display_errors", "On"); //打开错误提示
	error_reporting(E_ALL | E_STRICT);
	header("Content-type: text/html; charset=utf-8");
	$GLOBALS['theme'] = "mobile";
	$GLOBALS['api_dir'] = "service|api|do";
	
	require_once(dirname(__FILE__).'/core/FileLoader.php');
	$fileLoader = new FileLoader();
	$fileLoader->loadfile();
	
?>