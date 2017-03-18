<?php
class CollegeServ{
        
    //获取高校下所有成员身份
	function getCollegeSubType(){
		$sql = "select * from #__college_member_type where status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
        
    //获取科研基金
    function getUserFund($mid,$collegeMid){
	    $arr = array();
	    $arr[] = $mid;
	    $arr[] = $collegeMid;
	    $sql = "select * from #__college_member_fund where mid = ? and college_mid = ? and status = 1 ";
	    $dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
    }
    
    //高校成员获取个人科研基金订单记录
    function getFundOrderRecord($mid,$collegeMid){
	    $arr = array();
	    $arr[] = $collegeMid;
	    $arr[] = $mid;
	    $sql = "select * from #__order where payment_type = 2 and mid = ? and payer_mid = ? and status = 1 ";
	    $dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
    }
    
    //申请科研基金
   function applyFund($mid,$collegeMid){
		$table = "college_fund_apply";
		$insertColumns = array("mid","college_mid","state","apply_time","status");
		$insertVals = array($mid,$collegeMid,"1",time(),'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
   } 
   
   //获取最近一次科研基金申请记录
   function getApplyFundRecord($mid,$collegeMid){
	   $arr = array();
	   $arr[] = $mid;
	   $arr[] = $collegeMid;
	   $sql = "select * from #__college_fund_apply where mid = ? and college_mid = ? and status = 1 order by apply_time desc limit 1 ";
	   $dbAgent=DBAgent::getInstance();
	   $result=$dbAgent->queryRecords($sql,$arr);
	   if($result === false){
		   return false;
	   }
	   if($result == null){
		   return null;
	   }
	   return $result[0];
   }
   
   //查询所有科研基金申请记录数量
   function getAllApplyFundCount(){
	   $sql = "select count(*) as count from #__college_fund_apply a join #__member b on a.mid = b.mid join #__member c on a.college_mid = c.mid where a.status = 1 and b.status = 1 and c.status = 1 ";
	   $dbAgent=DBAgent::getInstance();
	   $result=$dbAgent->querySingleRecord($sql,array());
	   if($result === false){
		   return false;
	   }
	   return $result['count'];
   }
   
   //分页查询所有科研基金申请记录
   function getAllApplyFundRecord(){ 
	   $sql = "select a.*,b.*,c.name as collegename from #__college_fund_apply a join #__member b on a.mid = b.mid join #__member c on a.college_mid = c.mid where a.status = 1 and b.status = 1 and c.status = 1 order by a.apply_time desc  ";
	   $dbAgent=DBAgent::getInstance();
	   return $result=$dbAgent->queryRecords($sql,array());
   }
   
   //审核科研基金结果
   function operateResearchFund($mid,$collegeMid,$fund,$type){
		$table = "college_fund_apply";
		if($type == 1){
			$updateColumns = array('fund','state');
			$updateVals = array($fund,'2');
		} else {
			$updateColumns = array('state');
			$updateVals = array('3');
		}
		$conditionColumns = array('mid','college_mid','status');
		$conditionVals = array($mid,$collegeMid,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
   }
   
   //添加科研基金
   function updateResearchFund($mid,$sb,$collegeMid,$fund){
		$arr1=array();	   
		$arr1[]=$mid;
		$arr1[]=$collegeMid;
		$sql1="select count(*) as num from #__college_member_fund where mid=? and college_mid=? and status=1";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql1,$arr1);
		$num=$result['num'];
		if($num>0){
		  	$arr = array();
			$arr[]=$fund;
			$arr[]=$mid;
			$arr[]=$collegeMid;
			$sql = "update #__college_member_fund set total_fund = total_fund + ? where mid = ? and college_mid = ? and status = 1 ";
			return $dbAgent->query($sql,$arr);
		}else {
			$table = "college_member_fund";
			$insertColumns = array("mid","college_mid","sub_type","total_fund","status");
			$insertVals = array($mid,$collegeMid,$sb,$fund,1);
			$dbAgent = DBAgent::getInstance();
			return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
		}
		
		
   }
   
   //删除申请科研基金记录
   function deleteApplyFundRecord($mid,$collegeMid){
	   	$table = "college_fund_apply";
		$updateColumns = array('status');
		$updateVals = array('0');
		$conditionColumns = array('mid','college_mid');
		$conditionVals = array($mid,$collegeMid);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
   }
   
}
?>