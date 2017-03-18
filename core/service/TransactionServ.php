<?php
class TransactionServ{
	
	public function __construct(){
	    
    }
	
	//获取交易记录 type 1:会员消费 2:科研基金 3:积分
	function getUserTransaction($mid,$type){
		$arr=array();
		$arr[]=$type;
		$arr[]=$mid;
		$arr[]=$mid;
		$sql="select * from #__transaction_log where type = ? and (party_a = ? or party_b = ?) and status=1 ORDER BY time desc ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}	
	
	function addTransactionRecord($record){
		$table = "transaction_log";
		$insertColumns = array("party_a","party_b","type","pay_type","currency","number",'remarks','time','state','status');
		$insertVals = array($record['party_a'],$record['party_b'],$record['type'],$record['pay_type'],$record['currency'],$record['number'],$record['remarks'],time(),'1','1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
}
?>