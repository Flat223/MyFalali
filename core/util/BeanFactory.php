<?php
class BeanFactory{
	
	private function __construct(){}
	
	private function __clone(){}
	
	public static function createWrapBean($bean){
		$log = new Log($_SERVER['DOCUMENT_ROOT'].'/core/logs/');
		$className = get_class($bean);
		$mapping = BeanFactory::getMapping($className,$log);
		if($mapping === false){
			return false;
		}
		$propStatus = BeanFactory::getPropStatus($className);
		$beanWrap = new BeanWrap($bean,$mapping,$propStatus);
		return $beanWrap;
	}
	
	private static function getMapping($className,$log){
		$xmlFile = $_SERVER['DOCUMENT_ROOT'].'/core/xml/'.$className.'.orm.xml';
		if(!file_exists($xmlFile)){
			$log->error("未找到此文件 $xmlFile");
			return false;
		}
		$mapping = array();
		$dom = new DOMDocument("1.0","UTF-8");
		$dom->load($xmlFile);
		$entity = $dom->getElementsByTagName("entity")->item(0);
		foreach($entity->attributes as $attr){
			if($attr->name == 'table'){
				$mapping['table'] = $attr->value;
				break;
			}
		}
		$columns = array();
		$id = $entity->getElementsByTagName("id")->item(0);
		if($id == null){
			$log->error($className.'.orm.xml 文件未设定主键列');
			return false;
		}
		$columns[$id->nodeValue] = array();
		foreach($id->attributes as $attr){
			if($attr->name == 'column'){
				$columns[$id->nodeValue]['column'] = $attr->value;
			}
		}
		if(!isset($columns[$id->nodeValue]['column']) || $columns[$id->nodeValue]['column'] == ""){
			$log->error($className.'.orm.xml 文件主键列未设定字段映射');
			return false;
		}
		$columns[$id->nodeValue]['primary'] = 1;
		$fields = $entity->getElementsByTagName("field");
		for($i=0;$i<$fields->length;$i++){
			$filed = $fields->item($i);
			if($filed->nodeValue === ""){
				$log->error($className.'.orm.xml 文件field标签内容有空值');
				return false;
			}
			$columns[$filed->nodeValue] = array();
			foreach($filed->attributes as $attr){
				if($attr->name == 'column'){
					$columns[$filed->nodeValue]['column'] = $attr->value;
				}
				if($attr->name == 'type'){
					$columns[$filed->nodeValue]['type'] = $attr->value;
				}
			}
			if(!isset($columns[$filed->nodeValue]['column']) || $columns[$filed->nodeValue]['column'] === ""){
				$log->error($className.'.orm.xml 文件field标签未定义column属性或是属性为空');
				return false;
			}
			$columns[$filed->nodeValue]['primary'] = 0;
		}
		$mapping['columns'] = $columns;
		return $mapping;
	}
	
	private static function getPropStatus($className){
		$propStatus = array();
		$class = new ReflectionClass($className);
		$methods = $class->getMethods();
		foreach($methods as $method){
			preg_match('/^set([a-z_]*$)/i', $method->getName(),$matches);
			if(count($matches)>1){
				$propStatus[lcfirst($matches[1])] = 0;
			}
		}
		return $propStatus;
	}
	
}
	
	
	
	
	
	
	
	
	