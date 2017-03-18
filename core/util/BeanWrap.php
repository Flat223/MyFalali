<?php
class BeanWrap{
	
	private $bean;
	private $log;
	private $ORMapping;
	private $propStatus;
	
	public function __construct($bean,$ORMapping,$propStatus){
		$this->bean = $bean;
		$this->ORMapping = $ORMapping;
		$this->propStatus = $propStatus;
		$this->log = new Log($_SERVER['DOCUMENT_ROOT'].'/core/logs/');
	}
	
	public function __call($method,$argument){
		if($this->bean === null){
			$this->log->error("BeanWrap中bean属性未赋予实体对象");
			return false;
		}
		if(!method_exists($this->bean, $method)){
			$className = get_class($this->bean); 
			$this->log->error("$className 中 $method 方法未定义");
			return false;
		}
		if($this->startWith($method,"set")){
			preg_match('/^set([a-z_]*$)/i', $method,$matches);
			if(count($matches)>1){
				$val = lcfirst($matches[1]);
				if(isset($this->propStatus[$val])){
					$this->propStatus[$val] = 1;
				}
			}
		}
		$arr = array($this->bean,$method);
		$callback = call_user_func_array($arr, $argument);
		return $callback;
	}
	
	public function obtainUnwrappedBean(){
		return $this->bean;
	}
	
	public function obtainORMapping(){
		return $this->ORMapping;
	}
	
	public function obtainPropStatus(){
		return $this->propStatus;
	}
	
	public function resetStatus(){
		if(empty($this->propStatus)){
			return;
		}
		$count = count($this->propStatus);
		for($i=0;$i<$count;$i++){
			$this->propStatus[$i] = 0;
		}
	}
	
	private function startWith($str,$needle){
		return strpos($str, $needle) === 0;
	}
	
	private function endWith($haystack, $needle) {   
		$length = strlen($needle);  
		if($length == 0){    
		  return true;  
		}  
		return (substr($haystack, -$length) === $needle);
	}
	
}