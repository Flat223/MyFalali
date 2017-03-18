<?php
class AdminServ{
	
    //获取管理员
	function getAdmin($mobile,$password){
		$table = "admin";
		$columns = "*";
		$conditionColumns = array('mobile','password','status');
		$conditionVals = array($mobile,md5($password),'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals);
	}
	
	//根据手机号获取管理员
	function getAdminByMobile($mobile){
		$arr = array();
		$arr[] = $mobile;
		$sql = "select * from #__admin where mobile = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	//根据aid获取管理员
	function getAdminByAid($aid){
		$table = "admin";
		$columns = "*";
		$conditionColumns = array("aid",'status');
		$conditionVals = array($aid,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals);
	}
	
	//查询所有管理员数量
	function getAllAdminCount(){
		$sql = "select count(*) as count from #__admin where status = 1 ";
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->querySingleRecord($sql,array());
		if($result === false){
			return false;
		}
		return $result['count'];
	}
	
	//分页搜索所有管理员
	function getAllAdmin($index,$size){
		$sql = "select case when b.rid is null then '暂无' else b.name end as role,a.* from #__admin a left join #__role b on a.rid = b.rid and b.status = 1 where a.status = 1 order by a.aid asc limit $index,$size ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
	
	//获取所有角色身份
	function getAllRore(){
		$sql = "select * from #__role where status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
	
	//根据rid获取角色身份
	function getRoreByRid($rid){
		$sql = "select * from #__role where rid = $rid and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,array());
	}
	
	//设置管理员身份
	function setAdminIdent($aid,$rid){
		$arr = array();
		$arr[] = $rid;
		$arr[] = $aid;
		$sql = "update #__admin set rid = ? where aid = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->query($sql,$arr);
	}
	
	//根据字段搜索管理员数量
	function searchAdminCountByKey($key){
		$sql = "select count(*) as count from #__admin where status = 1 ";
		if($key != ""){
			if(Common::isInteger($key) && mb_strlen($key) == 11 ){
				$sql .= "and mobile = $key ";
			} else {
				$sql .= "and ( name like '%$key%' or nickname like '%$key%') ";	
			}
			$dbAgent=DBAgent::getInstance();
			$result = $dbAgent->querySingleRecord($sql,array());
			if($result === false){
				return false;
			}
			return $result['count'];
		}
		return 0;
	}
	
	//根据字段分页搜索管理员
	function searchAdminByKey($key,$index,$size){
		$sql = "select b.name as role,a.* from #__admin a join #__role b on a.rid = b.rid where a.status = 1 ";
		if($key != ""){
			if(Common::isInteger($key) && mb_strlen($key) == 11 ){
				$sql .= "and a.mobile = $key ";
			} else {
				$sql .= "and ( a.name like '%$key%' or a.nickname like '%$key%') ";	
			}
			$sql .= "limit $index,$size ";
			$dbAgent=DBAgent::getInstance();
			return $result=$dbAgent->queryRecords($sql,array());
		}
		return  array();
	}
	
	//更新管理员头像
	function updateFace($aid,$face){
		$arr = array();
		$arr[] = $face;
		$arr[] = $aid;
		$sql = "update #__admin set face = ? where aid = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->query($sql,$arr);
	}
	
	//添加管理员
	function addAdmin($admin){
		$table = "admin";
		$insertColumns = array('addtime','status');
		$insertVals = array(time(),1);
		
		if(isset($admin['rid'])){
			$insertColumns[] = 'rid';
			$insertVals[] = $admin['rid'];
		}
		if(isset($admin['nickname'])){
			$insertColumns[] = 'nickname';
			$insertVals[] = $admin['nickname'];
		}
		if(isset($admin['realname'])){
			$insertColumns[] = 'name';
			$insertVals[] = $admin['realname'];
		}
		if(isset($admin['mobile'])){
			$insertColumns[] = 'mobile';
			$insertVals[] = $admin['mobile'];
		}
		if(isset($admin['password'])){
			$insertColumns[] = 'password';
			$insertVals[] = md5($admin['password']);
		}
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	
	
	
	
	//查询所有用户数量
	function getAllUserCount(){
		$sql = "select count(*) as count from #__member where status = 1 ";
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->querySingleRecord($sql,array());
		if($result === false){
			return false;
		}
		return $result['count'];
	}
	
	//分页搜索所有用户
	function getAllUser($index,$size){
		$sql = "select case when a.bind_company = 0 then '无' else b.name end as company,a.* from #__member a left join #__member b on a.bind_company = b.mid where a.status = 1 limit $index,$size ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
	
	//查询筛选用户的数量
	function getUserCountByScreen($type,$sub_type){
		$arr = array();
		$arr[] = $type;
		$arr[] = $sub_type;
		$sql = "select count(*) as count from #__member where type = ? and sub_type = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->querySingleRecord($sql,$arr);
		if($result === false){
			return false;
		}
		return $result['count'];
	}
	
	//查询筛选所得用户
	function getUserByScreen($type,$sub_type,$index,$size){
		$arr = array();
		$arr[] = $type;
		$arr[] = $sub_type;
		$sql = "select case when a.bind_company = 0 then '无' else b.name end as company,a.* from #__member a left join #__member b on a.bind_company = b.mid where a.type = ? and a.sub_type = ? and a.status = 1 limit $index,$size ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	//根据字段搜索用户数量
	function searchUserCountByKey($key){
		$sql = "select count(*) as count from #__member where status = 1 ";
		if($key != ""){
			if(Common::isInteger($key) && mb_strlen($key) == 11 ){
				$sql .= "and mobile = $key ";
			} else {
				$sql .= "and ( name like '%$key%' or nickname like '%$key%') ";	
			}
			$dbAgent=DBAgent::getInstance();
			$result = $dbAgent->querySingleRecord($sql,array());
			if($result === false){
				return false;
			}
			return $result['count'];
		}
		return 0;
	}
	
	//根据字段分页搜索用户
	function searchUserByKey($key,$index,$size){
		$sql = "select case when a.bind_company = 0 then '无' else b.name end as company,a.* from #__member a left join #__member b on a.bind_company = b.mid where a.status = 1 ";
		if($key != ""){
			if(Common::isInteger($key) && mb_strlen($key) == 11 ){
				$sql .= "and a.mobile = $key ";
			} else {
				$sql .= "and ( a.name like '%$key%' or a.nickname like '%$key%') ";	
			}
			$sql .= "limit $index,$size ";
			$dbAgent=DBAgent::getInstance();
			return $result=$dbAgent->queryRecords($sql,array());
		}
		return  array();
	}
	
	//根据aid删除管理员(超级管理员可操作)
	function deleteAdminByAid($aid){
		$table = "admin";
		$updateColumns = array('status');
		$updateVals = array('0');
		$conditionColumns = array('aid','status');
		$conditionVals = array($aid,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//根据mid删除用户
	function deleteUserByMid($mid){
		$table = "member";
		$updateColumns = array('status');
		$updateVals = array('0');
		$conditionColumns = array('mid','status');
		$conditionVals = array($mid,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//查询所有高校
	function getAllCollege(){
		$sql = "select * from #__member where type = 1 and sub_type = 0 and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
	
	//查询所有公司
	function getAllCompany(){
		$sql = "select * from #__member where type = 2 and sub_type = 0 and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
	
	//获取高校下所有成员身份
	function getCollegeSubType(){
		$sql = "select * from #__college_member_type where status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
	
	//根据手机号获取用户
	function getUserByMobile($mobile){
		$arr = array();
		$arr[] = $mobile;
		$sql = "select * from #__member where mobile = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	//添加会员
	function addUser($user){
		$table = "member";
		$insertColumns = array('regist_time','status');
		$insertVals = array(time(),'1');
		
		if(isset($user['user_type'])){
			$insertColumns[] = 'type';
			$insertVals[] = $user['user_type'];
		}
		if(isset($user['sub_type'])){
			$insertColumns[] = 'sub_type';
			$insertVals[] = $user['sub_type'];
		}
/*
		if(isset($user['cid'])){
			$insertColumns[] = 'bind_company';
			$insertVals[] = $user['cid'];
			
			$insertColumns[] = 'bind_status';
			$insertVals[] = '2';	
		}
*/
		if(isset($user['nickname'])){
			$insertColumns[] = 'nickname';
			$insertVals[] = $user['nickname'];
		}
		if(isset($user['realname'])){
			$insertColumns[] = 'name';
			$insertVals[] = $user['realname'];
		}
		if(isset($user['mobile'])){
			$insertColumns[] = 'mobile';
			$insertVals[] = $user['mobile'];
		}
		if(isset($user['password'])){
			$insertColumns[] = 'password';
			$insertVals[] = md5($user['password']);
		}
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	//设置会员等级
	function setUserVip($mid,$vip){
		$table = "member";
		$updateColumns = array('vip_level');
		$updateVals = array($vip);
		$conditionColumns = array('mid','status');
		$conditionVals = array($mid,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);	
	}
	
}