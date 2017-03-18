<?php
class EntityManager{
	
	private static $instance = null;
	private $log;
	
	private function __construct(){}
	
	public static function create(){
		if(!self::$instance instanceof self){
			self::$instance = new self();
			self::$instance ->log = new Log($_SERVER['DOCUMENT_ROOT'].'/core/logs/');
		}
		return self::$instance;
	}
	
	private function __clone(){}
	
	private function checkWrapBean($entity){
		if(!$entity instanceof BeanWrap){
			$className = get_class($entity); 
			$this->log->error("实体类 $className 不属于BeanWrap的包装类");
			return false;
	    }
	    $bean = $entity->obtainUnwrappedBean();
	    if(empty($bean)){
		    $this->log->error("BeanWrap的bean未定义");
		    return false;
	    }
	    $propStatus = $entity->obtainPropStatus();
	    if(empty($propStatus)){
		    $this->log->error("BeanWrap的propStatus未定义");
		    return false;
	    }
	    $mapping = $entity->obtainORMapping();
	    if(empty($mapping)){
		    $this->log->error("BeanWrap的ormapping未定义");
		    return false;
	    }
	    if(empty($mapping['table'])){
		    $this->log->error("BeanWrap的ormapping不存在table名称");
		    return false;
	    }
	    $table = $entity->obtainORMapping()['table'];
	    if(!isset($entity->obtainORMapping()['columns'])){
		    $this->log->error("BeanWrap的ormapping不存在columns");
		    return false;
	    }
		return true;	
	}
	
	public function update($entity){
		if(!$this->checkWrapBean($entity)){
			return false;
		}
	    $bean = $entity->obtainUnwrappedBean();
	    $className = get_class($bean); 
	    $propStatus = $entity->obtainPropStatus();
	    $table = $entity->obtainORMapping()['table'];
	    $columns = $entity->obtainORMapping()['columns'];
	    $conditionColumns = array();
	    $conditionVals = array();
	    foreach($columns as $name=>$field){
		    if($field['primary'] === 1){
			    $methodName = "get".ucfirst($name);
			    if(!method_exists($bean, $methodName)){
				    $this->log->error("$className 中未找到主键定义的get方法");
				    return false;
			    }
			    $arr = array($bean,$methodName);
			    $id = call_user_func_array($arr, array());
			    if($id === null){
				    $this->log->error("$className 中主键未赋值");
				    return false;
			    }
			    $conditionColumns[] = $field['column'];
				$conditionVals[] = $id;
			    break;
		    }
	    }
	    if(count($conditionColumns) === 0){
			$this->log->error("实体类 $className 未定义主键");
			return false;
	    }
	    $updateColumns = array();
	    $updateVals = array();
	    foreach($propStatus as $key=>$val){
		    if($val === 1){
			    foreach($columns as $name=>$field){
				    if($name === $key){
					    if($field['primary'] === 0){
						    $methodName = "get".ucfirst($name);
						    if(!method_exists($bean, $methodName)){
							    $this->log->error("$className 中未找到 $name 定义的get方法");
							    return false;
						    }
						    $arr = array($bean,$methodName);
						    $fieldVal = call_user_func_array($arr, array());
						    if($fieldVal === null){
							    $fieldVal = "";
						    }
						    $updateColumns[] = $field['column'];
						    $updateVals[] = $fieldVal;
					    }
					    break; 
				    }
			    }  
		    }
	    }
	    $dbAgent = DBAgent::getInstance();
	    return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals,false);	
	}
	
	public function add($entity){
		if(!$this->checkWrapBean($entity)){
			return false;
		}
		$bean = $entity->obtainUnwrappedBean();
		$className = get_class($bean);
		$propStatus = $entity->obtainPropStatus();
	    $table = $entity->obtainORMapping()['table'];
	    $columns = $entity->obtainORMapping()['columns'];
		$insertColumns = array();
	    $insertVals = array();
	    foreach($propStatus as $key=>$val){
		    if($val === 1){
			    foreach($columns as $name=>$field){
				    if($name === $key){
					    if($field['primary'] === 0){
						    $methodName = "get".ucfirst($name);
						    if(!method_exists($bean, $methodName)){
							    $this->log->error("$className 中未找到 $name 定义的get方法");
							    return false;
						    }
						    $arr = array($bean,$methodName);
						    $fieldVal = call_user_func_array($arr, array());
						    if($fieldVal !== null){
							    $insertColumns[] = $field['column'];
								$insertVals[] = $fieldVal;
						    }
					    }
					    break; 
				    }
			    }  
		    }
	    }
	    $dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals,false);
	}
	
	public function del($entity){
		if(!$this->checkWrapBean($entity)){
			return false;
		}
		$bean = $entity->obtainUnwrappedBean();
		$className = get_class($bean);
		$propStatus = $entity->obtainPropStatus();
	    $table = $entity->obtainORMapping()['table'];
	    $columns = $entity->obtainORMapping()['columns'];
		$conditionColumns = array();
		$conditionVals = array();
		foreach($columns as $name=>$field){
		    if($field['primary'] === 1){
			    $methodName = "get".ucfirst($name);
			    if(!method_exists($bean, $methodName)){
				    $this->log->error("$className 中未找到主键定义的get方法");
				    return false;
			    }
			    $arr = array($bean,$methodName);
			    $id = call_user_func_array($arr, array());
			    if($id === null){
				    $this->log->error("$className 中主键未赋值");
				    return false;
			    }
			    $conditionColumns[] = $field['column'];
				$conditionVals[] = $id;
			    break;
		    }
	    }
		if(count($conditionColumns) === 0){
			$this->log->error("实体类 $className 未定义主键");
			return false;
	    }
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->deleteRecords($table,$conditionColumns,$conditionVals,false);
	}
	
	public function get($className,$id){
		$file = $_SERVER['DOCUMENT_ROOT'].'/core/bean/'.$className.'.php';
		if(!file_exists($file)){
			$this->log->error("未找到文件 $file");
			return false;
		}
		require_once($file);
		$class = new ReflectionClass($className);
		$bean = $class->newInstance();
		$entity = BeanFactory::createWrapBean($bean);
		if(!$this->checkWrapBean($entity)){
			return false;
		}
	    $table = $entity->obtainORMapping()['table'];
	    $columns = $entity->obtainORMapping()['columns'];
	    $queryColumns = "*";
	    $conditionColumns = array();
	    $conditionVals = array();
		foreach($columns as $name=>$field){
		    if($field['primary'] === 1){
			    $conditionColumns[] = $field['column'];
				$conditionVals[] = $id;
			    break;
		    }
	    }
		if(count($conditionColumns) === 0){
			$this->log->error("实体类 $className 未定义主键");
			return false;
	    }
		$dbAgent = DBAgent::getInstance();
		$callback = $dbAgent->getSingleRecordFromTable($table,$queryColumns,$conditionColumns,$conditionVals,false);
		if($callback === false){
			return false;
		}
		if($callback === null){
			return null;
		}
		foreach($columns as $name=>$field){
			$methodName = "set".ucfirst($name);
			if(!method_exists($bean, $methodName)){
			    $this->log->error("$className 中未找到 $name 定义的set方法");
			    return false;
		    }
		    if(isset($callback[$field['column']])){
			    $arr = array($bean,$methodName);
			    call_user_func_array($arr,array($callback[$field['column']]));
		    }
	    }
	    return $bean;
	}
	
	public function getObj($className,$conditions=array(),$conditionVals=array()){
		$file = $_SERVER['DOCUMENT_ROOT'].'/core/entities/'.$className.'.php';
		if(!file_exists($file)){
			$this->log->error("未找到文件 $file");
			return false;
		}
		require_once($file);
		$class = new ReflectionClass($className);
		$bean = $class->newInstance();
		$entity = BeanFactory::createWrapBean($bean);
		if(!$this->checkWrapBean($entity)){
			return false;
		}
		if(count($conditions) != count($conditionVals)){
			$this->log->error("EntityManager类 getObjArray 方法中查询条件和条件值数量不一致");
			return false;
		}
		$table = $entity->obtainORMapping()['table'];
	    $columns = $entity->obtainORMapping()['columns'];
	    $queryColumns = "*";
	    $conditionColumns = array();
		foreach($conditions as $condition){
			$flag = 0;
			foreach($columns as $name=>$field){
				if($name === $condition){
					$flag = 1;
					$conditionColumns[] = $field['column'];
					break;
				}
		    }
			if($flag == 0){
				$this->log->error("EntityManager类 getObjArray 方法中找不到 $condition 对应的数据库字段");
				return false;
			}
		}
		$dbAgent = DBAgent::getInstance();
		$callback = $dbAgent->getSingleRecordFromTable($table,$queryColumns,$conditionColumns,$conditionVals,false);
		if($callback === false){
			return false;
		}
		if($callback === null){
			return null;
		}
		foreach($columns as $name=>$field){
			$methodName = "set".ucfirst($name);
			if(!method_exists($bean, $methodName)){
			    $this->log->error("$className 中未找到 $name 定义的set方法");
			    return false;
		    }
		    if(isset($callback[$field['column']])){
			    $arr = array($bean,$methodName);
			    call_user_func_array($arr,array($callback[$field['column']]));
		    }
	    }
	    return $bean;
	}
	
	public function getObjArray($className,$conditions=array(),$conditionVals=array(),$hasPage=false,$offset=0,$size=10,$orderby=''){
		$file = $_SERVER['DOCUMENT_ROOT'].'/core/bean/'.$className.'.php';
		if(!file_exists($file)){
			$this->log->error("未找到文件 $file");
			return false;
		}
		require_once($file);
		$class = new ReflectionClass($className);
		$bean = $class->newInstance();
		$entity = BeanFactory::createWrapBean($bean);
		if(!$this->checkWrapBean($entity)){
			return false;
		}
		if(count($conditions) != count($conditionVals)){
			$this->log->error("EntityManager类 getObjArray 方法中查询条件和条件值数量不一致");
			return false;
		}
		$table = $entity->obtainORMapping()['table'];
	    $columns = $entity->obtainORMapping()['columns'];
	    $queryColumns = "*";
	    $conditionColumns = array();
		foreach($conditions as $condition){
			$flag = 0;
			foreach($columns as $name=>$field){
				if($name === $condition){
					$flag = 1;
					$conditionColumns[] = $field['column'];
					break;
				}
		    }
			if($flag == 0){
				$this->log->error("EntityManager类 getObjArray 方法中找不到 $condition 对应的数据库字段");
				return false;
			}
		}
		$dbAgent = DBAgent::getInstance();
		$resultSet = $dbAgent->getRecordsFromTable($table,$queryColumns,$conditionColumns,$conditionVals,false,$hasPage,$offset,$size,$orderby);
		if($resultSet === null){
			return null;
		}
		$arr = array();
		foreach($resultSet as $result){
			$obj = $class->newInstance(); 
			foreach($columns as $name=>$field){
				$methodName = "set".ucfirst($name);
				if(!method_exists($bean, $methodName)){
				    $this->log->error("$className 中未找到 $name 定义的set方法");
				    return false;
			    }
			    if(isset($result[$field['column']])){
				    $arr2 = array($obj,$methodName);
				    call_user_func_array($arr2,array($result[$field['column']]));
			    }
		    }
		    $arr[] = $obj;
		}
		return $arr;
	}
	
	public function getObjArrayCount($className,$conditions=array(),$conditionVals=array()){
		$file = $_SERVER['DOCUMENT_ROOT'].'/core/entities/'.$className.'.php';
		if(!file_exists($file)){
			$this->log->error("未找到文件 $file");
			return false;
		}
		require_once($file);
		$class = new ReflectionClass($className);
		$bean = $class->newInstance();
		$entity = BeanFactory::createWrapBean($bean);
		if(!$this->checkWrapBean($entity)){
			return false;
		}
		if(count($conditions) != count($conditionVals)){
			$this->log->error("EntityManager类 getObjArray 方法中查询条件和条件值数量不一致");
			return false;
		}
		$table = $entity->obtainORMapping()['table'];
	    $columns = $entity->obtainORMapping()['columns'];
	    $conditionColumns = array();
		foreach($conditions as $condition){
			$flag = 0;
			foreach($columns as $name=>$field){
				if($name === $condition){
					$flag = 1;
					$conditionColumns[] = $field['column'];
					break;
				}
		    }
			if($flag == 0){
				$this->log->error("EntityManager类 getObjArray 方法中找不到 $condition 对应的数据库字段");
				return false;
			}
		}
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getRecordCountsFromTable($table,$conditionColumns,$conditionVals,false);
	}
	
	public function getRecords($sql,$params=array()){
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$params);
	}
	
	public function getSingleRecord($sql,$params=array()){
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$params);
	}
	
	public function query($sql,$params=array()){
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->query($sql,$params);
	}
	
	
}