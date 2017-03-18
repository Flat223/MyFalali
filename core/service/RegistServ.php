<?php
class RegistServ{
	
	//获取高校下属身份
	function getCollegeTypes(){
		$table = "college_member_type";
		$columns = array('sub_type','name');
		$conditionColumns = array('status');
		$conditionVals = array(1);
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->getRecordsFromTable($table,$columns,$conditionColumns,$conditionVals);
	}
	
	//获取行业类型
	function getIndustryTypes(){
		$table = "industry";
		$columns = array('industry_id','name','image');
		$conditionColumns = array('status');
		$conditionVals = array(1);
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->getRecordsFromTable($table,$columns,$conditionColumns,$conditionVals);
	}

    /*注册用户*/
    public function insertUser($user){
        $table = "member";
        $insertColumns = array('name','mobile','password','regist_time',"type","status");
        $insertVals = array($user['name'],$user['mobile'],$user['password'],time(),4,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    /*查询用户by手机*/
    public function getUserByTel($tel){
        $arr = array();
        $arr[] = $tel;
        $sql = "select * from labring_member where mobile = ?";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }
	
	
	
	
	
	
	
	
	
	
	
}
	
	
	
	
	