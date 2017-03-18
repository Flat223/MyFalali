<?php
class AreaServ{
	
	//获取所有城市
	function getCities(){
		$table = "area";
		$columns = "*";
		$conditionColumns = array('category');
		$conditionVals = array('2');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getRecordsFromTable($table,$columns,$conditionColumns,$conditionVals);
	}
	
	//根据id获取地区
	function getAreaById($id){
		$table = "area";
		$columns = "*";
		$conditionColumns = array("id");
		$conditionVals = array($id);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals);
	}
	
	
	
	
	
}
?>