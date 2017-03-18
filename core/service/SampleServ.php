<?php
class SampleServ{
	
	//构造函数->默认载入函数
    public function __construct(){
	    
    }
	
	function getProductTypes($id){
		$table = "product_type";
		$columns = "*";
		$conditionColumns = array('ptid');
		$conditionVals = array($id);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals); 
	}
	
	
	
	
}