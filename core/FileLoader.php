<?php 
class FileLoader{
	
	private $log;
	
	//构造函数->默认载入函数
    public function __construct(){
	    require_once dirname(__FILE__).'/util/Log.php';
	    $this->log = new \cy\Log($_SERVER['DOCUMENT_ROOT'].'/core/logs/');
    }
    
    function loadfile(){
	    $param1 = isset($_REQUEST['__param1'])?trim($_REQUEST['__param1']):"";
		$param2 = isset($_REQUEST['__param2'])?trim($_REQUEST['__param2']):"";
		$param3 = isset($_REQUEST['__param3'])?trim($_REQUEST['__param3']):"";
		if($param1 == "" || $param1 == "404"){
			$this->load('404');
			return;
		}
		$file = "";
		$isJson = false;
		if(isset($GLOBALS['api_dir'])){
			$jsonDirs = explode("|", $GLOBALS['api_dir']);
			if(in_array($param1, $jsonDirs)){
				$isJson = true;
			}
		}
/*
		$jsonDirs = explode("|", API_DIR);
		if(in_array($param1, $jsonDirs)){
			$isJson = true;
		}
*/
		$theme = Theme;
		if(isset($GLOBALS['theme'])){
			$theme = $GLOBALS['theme'];
		}
		if($param2 == ""){
			$param = ucfirst($param1);
			$file = $_SERVER['DOCUMENT_ROOT'].'/core/module/'.$theme.'/'.$param.'.php';
			$path = '/'.$param1.'.html';
			$html = $param1.'.html';
			$this->load($file,$html,$path,$param);
			return;
		}
		if($param3 == ""){
			$param = ucfirst($param2);
			if($isJson){
				$file = $_SERVER['DOCUMENT_ROOT'].'/core/module/'.$theme.'/_api/'.$param1.'/'.$param.'.php';
				if(!file_exists($file)){
					$file = $_SERVER['DOCUMENT_ROOT'].'/core/module/_api/'.$param1.'/'.$param.'.php';
				}
			}else{
				$file = $_SERVER['DOCUMENT_ROOT'].'/core/module/'.$theme.'/'.$param1.'/'.$param.'.php';
			}
			$path = '/'.$param1.'/'.$param2.'.html';
			$html = $param1.'.'.$param2.'.html';
			$this->load($file,$html,$path,$param,$isJson);
			return;
		}
		$param = ucfirst($param3);
		if($isJson){
			$file = $_SERVER['DOCUMENT_ROOT'].'/core/module/'.$theme.'/_api/'.$param1.'/'.$param2.'/'.$param.'.php';
			if(!file_exists($file)){
				$file = $_SERVER['DOCUMENT_ROOT'].'/core/module/_api/'.$param1.'/'.$param2.'/'.$param.'.php';
			}
		}else{
			$file = $_SERVER['DOCUMENT_ROOT'].'/core/module/'.$theme.'/'.$param1.'/'.$param2.'/'.$param.'.php';
		}
		$path = '/'.$param1.'/'.$param2.'/'.$param3.'.html';
		$html = $param1.'.'.$param2.'.'.$param3.'.html';
		$this->load($file,$html,$path,$param,$isJson);
	}
	
	private function load($file,$html="",$path="",$param="",$isJson=false){
		$theme = Theme;
		if(isset($GLOBALS['theme'])){
			$theme = $GLOBALS['theme'];
		}
		if($file == '404' || !file_exists($file)){
			include($_SERVER['DOCUMENT_ROOT'].'/html/'.$theme.'/404.html');
			return;
		}
		if(!$isJson){
			if($html == ""){
				include($_SERVER['DOCUMENT_ROOT'].'/html/'.$theme.'/404.html');
				return;
			}
			$htmlpath = $_SERVER['DOCUMENT_ROOT'].'/html/'.$theme.'/'.$html;
			if(!file_exists($htmlpath)){
			    include($_SERVER['DOCUMENT_ROOT'].'/html/'.$theme.'/404.html');
			    return;
		    }
		}
		if($param == ""){
			include($_SERVER['DOCUMENT_ROOT'].'/html/'.$theme.'/404.html');
			return;
		}
		if($path != ""){
			$callback = $this->handleFilter($path);
			if($callback == 'failed'){
				return;
			}
		}
		require_once $file;
		require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
		$reflectionClass = new ReflectionClass($param);
		$instance = $reflectionClass->newInstance();
		if(!$instance instanceof BaseAction){
			$className = get_class($instance);
			$this->log->error("$param 不是 BaseAction的子类");
			FileUtil::load404Html();
			return ;
		}
		AccessCommon::addAccessLog($path);
		$result = $instance->action();
		if($result == '404'){
			FileUtil::load404Html();
			return;
		}
		if($result == '500'){
			FileUtil::loadServerErrHtml();
			return;
		}
		if(!is_array($result)){
			$result = array();
		}
		if(!$isJson){
			$htmlpath = $_SERVER['DOCUMENT_ROOT'].'/html/'.$theme.'/'.$html;
			FileUtil::loadHtml2($htmlpath,$result);
		}else{
			header("Content-Type:application/x-javascript;charset=utf-8");
			echo(json_encode($result));
		}
	}
	
	private function handleFilter($path){
		$data = $this->loadFilters();
		if($data === false){
			return 'pass';
		}
		$classes = $data['classes'];
		$patterns = $data['patterns'];
		$patternStr = '/^[0-9a-zA-Z\*\.\/_-\|\(\)]+$/';
		require_once($_SERVER['DOCUMENT_ROOT'].'/core/filter/BaseFilter.php');
		foreach($patterns as $pattern){
			$ptStr = $pattern['pattern'];
			if(!preg_match($patternStr, $ptStr)){
				$this->log->error("$ptStr 存在非法字符串");
				return 'pass';
			}
			$ptStr = str_replace(".", "\.", $ptStr);
			$ptStr = str_replace("*", "[\s\S]*",$ptStr);
			$ptStr = str_replace("/", "\/",$ptStr);
			$ptStr = '/^'.$ptStr.'$/';
			$theme = Theme;
			if(isset($GLOBALS['theme'])){
				$theme = $GLOBALS['theme'];
			}
			if(preg_match($ptStr, $path)){
				foreach($classes as $class){
					if($pattern['name'] == $class['name']){
						$filterFile = $_SERVER['DOCUMENT_ROOT'].'/core/filter/instances/'.$theme.'/'.$class['class'].'.php';
						if(!file_exists($filterFile)){
							$this->log->error("$filterFile 文件未找到");
							return 'pass';
						}
						require_once($_SERVER['DOCUMENT_ROOT'].'/core/filter/instances/'.$theme.'/'.$class['class'].'.php');
						$reflectionClass = new ReflectionClass($class['class']);
						$instance = $reflectionClass->newInstance();
						if(!$instance instanceof BaseFilter){
							$className = get_class($instance);
							$this->log->error("$className 不是 BaseFilter的子类");
							return 'pass';
						}
						if(!$instance->doFilter($path)){
							return "failed";
						};
					}
				}
			}
		}
		return 'success';
	}
	
	private function loadFilters(){
		$theme = Theme;
		if(isset($GLOBALS['theme'])){
			$theme = $GLOBALS['theme'];
		}
		$xmlFile = $_SERVER['DOCUMENT_ROOT'].'/core/filter/xml/'.$theme.'/filter.xml'; 
		if(!file_exists($xmlFile)){
			$this->log->error("未找到xml文件 $xmlFile");
			return false;
		}
		$filterClasses = array();
		$filterPatterns = array();
		libxml_disable_entity_loader(false);
		$dom = new DOMDocument("1.0","UTF-8");
		$dom->load($xmlFile);
		$filters = $dom->getElementsByTagName("filters")->item(0)->getElementsByTagName("filter");
		foreach($filters as $filter){
			$class = array();
			foreach($filter->attributes as $attr){
				if($attr->name == 'name'){
					$class['name'] = $attr->value;
				}
				if($attr->name == 'class'){
					$class['class'] = $attr->value;
				}
			}
			if(!isset($class['name']) || !isset($class['class'])){
				$this->log->error("$xmlFile 中存在filter标签未定义name或class属性");
				return false;
			}
			if($class['name'] == "" || $class['class'] == ""){
				$this->log->error("$xmlFile 中filter标签name或class属性不能为空");
				return false;
			}
			$filterClasses[] = $class;
		}
		$mappings = $dom->getElementsByTagName("filter-mappings")->item(0)->getElementsByTagName("filter-mapping");
		foreach($mappings as $mapping){
			$pattern = array();
			foreach($mapping->attributes as $attr){
				if($attr->name == 'name'){
					$pattern['name'] = $attr->value;
				}
				if($attr->name == 'pattern'){
					$pattern['pattern'] = $attr->value;
				}
			}
			if(!isset($pattern['name']) || !isset($pattern['pattern'])){
				$this->log->error("$xmlFile 中存在filter-mapping标签未定义name或pattern属性");
				return false;
			}
			if($pattern['name'] == "" || $pattern['pattern'] == ""){
				$this->log->error("$xmlFile 中filter-mapping标签name或pattern属性不能为空");
				return false;
			}
			$filterPatterns[] = $pattern;
		}
		return array('classes'=>$filterClasses,'patterns'=>$filterPatterns);
	}
	
}
?>