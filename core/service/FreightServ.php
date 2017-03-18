<?php
class FreightServ{
	
	//保存运费模板
	function saveFreight($freight){
		$table = "freight";
		$insertColumns = array('sid','name','pro_province','pro_city','pro_county','pro_county_id','delivery_time_section',
					'type','area_limit_flag','valuation_unit','express_flag','express','ems_flag','ems','mail_flag',
					'mail','time','uptime','status');
		$insertVals = array($freight['sid'],$freight['name'],$freight['province'],$freight['city'],$freight['county'],
					$freight['countyid'],$freight['delivery_time_section'],$freight['type'],$freight['areaLimit'],
					$freight['unit'],$freight['express_flag'],$freight['express'],$freight['ems_flag'],$freight['ems'],
					$freight['mail_flag'],$freight['mail'],$freight['time'],$freight['time'],$freight['status']);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	//根据名称和店铺查找运费模板（查重）
	function getFreightByNameAndSid($name,$sid){
		$table = "freight";
		$columns = "*";
		$conditionColumns = array('sid','name','status');
		$condtionVals = array($sid,$name,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$condtionVals);
	}
	
	//根据id获取运费模板
	function getFreightById($freId){
		$table = "freight";
		$columns = "*";
		$conditionColumns = array('fre_id','status');
		$condtionVals = array($freId,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$condtionVals);
	}
	
	//获取店铺所有运费模板
	function getFreights($sid){
		$table = 'freight';
		$columns = '*';
		$conditionColumns = array('sid','status');
		$condtionVals = array($sid,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getRecordsFromTable($table,$columns,$conditionColumns,$condtionVals,true,false,0,0,'fre_id desc');
	}
	
	//获取店铺运费模板的数量
	function getFreightCount($sid){
		$table = 'freight';
		$conditionColumns = array('sid','status');
		$condtionVals = array($sid,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getRecordCountsFromTable($table,$conditionColumns,$condtionVals);
	}
	
	//店家查询运费模板
	function getShopFreight($sid){
		$arr = array();
		$arr[] = $sid;
		$sql = "select * from #__freight where sid = ? and status = 1 ";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}	
	
	//获取运费模板详情
	function getFreightDetail($id){
		$arr=array();
		$arr[]=$id;
		$sql="select * from #__freight where fre_id= ? and status=1";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
	
	//根据运费模板和店铺查找绑定产品数量
	function getProductCountByFreidAndSid($fid,$sid){
		$table = "product";
		$conditionColumns = array("fre_id","sid","status");
		$condtionVals = array($fid,$sid,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getRecordCountsFromTable($table,$conditionColumns,$condtionVals);
	}
	
	//删除运费模板
	function delFreightByFreid($freId){
		$table = "freight";
		$updateColumns = array('status');
		$updateVals = array('0');
		$conditionColumns = array("fre_id","status");
		$condtionVals = array($freId,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$condtionVals);
	}
	
	
	
	
}
?>