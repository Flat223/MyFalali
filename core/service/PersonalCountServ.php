<?php
class PersonalCountServ{

	//获取用户未读消息数量
	function getUserNoReadMessageNumById($mid){
		$arr = array();
		$arr[] = $mid;
		$sql = "select count(*) a from #__message where mid = ? and is_read = 0 and status = 1";
		$dbAgent = DBAgent::getInstance();
		$result = $dbAgent->queryRecords($sql,$arr);
		return $result['0']['a'];
	}

	//获取个人订单数量
	function getUserPersonalOrderNumById($mid){
		$arr = array();
		$arr[] = $mid;
		$sql = "select count(*) a from #__order where mid = ? and type = 4 AND status = 1 and state < 5 ";
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->queryRecords($sql,$arr);
		return $result['0']['a'];
	}

	//获取需求订单数量
	function getUserNeedOrderNumById($mid){
		$arr = array();
		$arr[] = $mid;
		$sql = "select count(*) a from #__order where mid = ? and type = 1 AND status = 1 and state < 5 ";
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->queryRecords($sql,$arr);
		return $result['0']['a'];
	}

	//获取采购订单数量
	function getUserShopOrderNumById($mid){
		$arr = array();
		$arr[] = $mid;
		$sql = "select count(*) a from #__order where mid = ? and type = 2 AND status = 1 and state < 5 ";
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->queryRecords($sql,$arr);
		return $result['0']['a'];
	}

}	
?>
