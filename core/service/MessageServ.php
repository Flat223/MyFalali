<?php
class MessageServ{
	
	public function __construct(){
	    
    }
	
	//根据id查询单条消息
	function getMessageById($sid,$type){//1:用户消息 2:系统消息
		$arr=array();
		$arr[]= $sid;
		if ($type == 1) {
			$sql = "select case when a.from_id = 0 then '系统消息' else b.name end as sender,a.* from #__message a left join #__member b on a.from_id = b.mid where a.id = ? and a.status = 1 ";
		} else {
			$sql = "select * from #__message_system where id = ? and status = 1 ";
		}
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
	
	//查询我的系统消息
	function getSystemMessageByMid($mid){
		$arr=array();
		$arr[]= $mid;
		$sql = "select a.*,case when b.id is null then 0 else 1 end as is_read from #__message_system a left join #__message_system_member b on a.id = b.msg_id and b.mid = ? where b.id is null or b.msg_status = 1 order by a.time desc ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	
	//查询我的用户消息
	function getUserMessageByMid($mid){
		$arr=array();
		$arr[]=$mid;
		$sql = "select case when a.from_id = 0 then '系统消息' else b.name end as sender,a.* from #__message a left join #__member b on a.from_id = b.mid where a.mid = ? and a.status = 1 order by a.time desc ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	//删除我的用户消息
	function deleteUserMessage($sid,$mid){	
		$arr=array();
		$arr[]=$sid;
		$arr[]=$mid;
		$sql = "update #__message set status = 0 where find_in_set(id,?) and mid = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
	}
	
	//删除我的系统消息 (已读)
	function updateSysMessageStatus($sid,$mid){
		$table = "message_system_member";
		$updateColumns = array('msg_status');
		$updateVals = array('0');
		$conditionColumns = array('msg_id','mid','is_read','msg_status');
		$conditionVals = array($sid,$mid,1,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//删除我的系统消息 (未读)
	function insertSysMessageStatus($sid,$mid){
		$table = "message_system_member";
		$insertColumns = array("mid","msg_id","is_read","msg_status");
		$insertVals = array($mid,$sid,'0','0');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	//更新用户消息为已读
	function setMessageReaded($sid){
		$table = "message";
		$updateColumns = array('is_read');
		$updateVals = array('1');
		$conditionColumns = array('id','is_read','status');
		$conditionVals = array($sid,0,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//更新系统消息为已读
	function setSystemMessageReaded($mid,$sid){
		$table = "message_system_member";
		$insertColumns = array("mid","msg_id","is_read","msg_status");
		$insertVals = array($mid,$sid,'1','1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}	
	
	//发送消息
	function sendMessage($mid,$title,$content,$from_id=0){
		$table = "message";
		$insertColumns = array("from_id","mid","title","content",'time',"is_read",'status');
		$insertVals = array($from_id,$mid,$title,$content,time(),0,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}

}
?>