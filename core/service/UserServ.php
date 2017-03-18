<?php
class UserServ{
	
    //更新会员密码
	function updateMemberPassword($mid,$mobile,$password){
		$table = "member";
		$updateColumns = array('password');
		$updateVals = array(md5($password));
		$conditionColumns = array('mid','mobile');
		$conditionVals = array($mid,$mobile);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}

    //更新会员信息
	function updateMemberInfo($mid,$member){
		$table = "member";
		$updateColumns = array();
		$updateVals = array();
		if(isset($member['name'])){
			$updateColumns[] = 'name';
			$updateVals[] = $member['name'];
		}
		if(isset($member['face'])){
			$updateColumns[] = 'face';
			$updateVals[] = $member['face'];
		}
		if(isset($member['nickname'])){
			$updateColumns[] = 'nickname';
			$updateVals[] = $member['nickname'];
		}
		if(isset($member['sex'])){
			$updateColumns[] = 'sex';
			$updateVals[] = $member['sex'];
		}
		if(isset($member['career'])){
			$updateColumns[] = 'career';
			$updateVals[] = $member['career'];
		}
		if(isset($member['identity_num'])){
			$updateColumns[] = 'identity_num';
			$updateVals[] = $member['identity_num'];
		}
		
		if(isset($member['province'])){
			$updateColumns[] = 'province';
			$updateVals[] = $member['province'];
		}
		if(isset($member['country'])){
			$updateColumns[] = 'country';
			$updateVals[] = $member['country'];
		}
		if(isset($member['city'])){
			$updateColumns[] = 'city';
			$updateVals[] = $member['city'];
		}
		if(isset($member['address'])){
			$updateColumns[] = 'address';
			$updateVals[] = $member['address'];
		}
		
/*
		if(isset($member['education'])){
			$updateColumns[] = 'education';
			$updateVals[] = $member['education'];
		}
		if(isset($member['city'])){
			$updateColumns[] = 'city';
			$updateVals[] = $member['city'];
		}
		if(isset($member['residential_district'])){
			$updateColumns[] = 'residential_district';
			$updateVals[] = $member['residential_district'];
		}
*/
		
		if(isset($member['university'])){
			$updateColumns[] = 'university';
			$updateVals[] = $member['university'];
		}
		if(isset($member['political_identity'])){
			$updateColumns[] = 'political_identity';
			$updateVals[] = $member['political_identity'];
		}
		if(isset($member['personal_desc'])){
			$updateColumns[] = 'personal_desc';
			$updateVals[] = $member['personal_desc'];
		}
		if(isset($member['education_experience'])){
			$updateColumns[] = 'education_experience';
			$updateVals[] = $member['education_experience'];
		}
		if(isset($member['work_experience'])){
			$updateColumns[] = 'work_experience';
			$updateVals[] = $member['work_experience'];
		}
		if(isset($member['patent'])){
			$updateColumns[] = 'patent';
			$updateVals[] = $member['patent'];
		}
		if(isset($member['research_projects'])){
			$updateColumns[] = 'research_projects';
			$updateVals[] = $member['research_projects'];
		}
		if(isset($member['interest_labels'])){
			$updateColumns[] = 'interest_labels';
			$updateVals[] = $member['interest_labels'];
		}

		$conditionColumns = array('mid','status');
		$conditionVals = array($mid,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}

    //更新会员头像
	function updateMemberFace($mid,$face){
		$table = "member";
		$updateColumns = array('face');
		$updateVals = array($face);
		$conditionColumns = array('mid');
		$conditionVals = array($mid);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//获取会员
	function getMember($mobile,$password){
		$table = "member";
		$columns = "*";
		$conditionColumns = array('mobile','password','status');
		$conditionVals = array($mobile,md5($password),'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals);
	}
	
	//注册时保存用户身份信息;
	function saveUserIdentity($mid,$tid,$subtid){
		$table = "member";
		if(($tid == 2 && $subtid == 0) || $tid == 3){
			$check = $tid;
			$tid = 4;//公司或供应商注册时身份默认为个人;
			$updateColumns = array('type','sub_type','company_check');	
			$updateVals = array($tid,$subtid,$check);
		} else {
			$updateColumns = array('type','sub_type');
			$updateVals = array($tid,$subtid);
		}
		
		$conditionColumns = array('mid','status');
		$conditionVals = array($mid,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//获取默认收货地址信息
	function getDefaultAddress($mid){
		$arr=array();
		$arr[]=$mid;
		$sql="select * from #__address where mid= ? and status= 1 and is_default= 1 ";
		$dbAgent = DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	
	//注册时保存用户所选行业信息;
	function saveUserIndustry($mid,$sid){
		$table = "member";
		$updateColumns = array('industry_ids');
		$updateVals = array($sid);
		$conditionColumns = array('mid','status');
		$conditionVals = array($mid,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//获取购物车列表
	function getCartList($mid){
		$arr=array();
		$arr[]=$mid;
		$sql="select * from #__cart where mid = ? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	//获取购物车信息详情
	function getCartDeatilById($id){
		$arr=array();
		$arr[]=$id;
		$sql="select * from #__cart where id=? and status=1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	//获取会员，根据手机号
	function getMemberByMobile($mobile){
		$table = "member";
		$columns = "*";
		$conditionColumns = array("mobile",'status');
		$conditionVals = array($mobile,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals);
	}
	
	//根据mid获取会员
	function getMemberByMid($mid){
		$table = "member";
		$columns = "*";
		$conditionColumns = array("mid",'status');
		$conditionVals = array($mid,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals);
	}
	
	//根据加密mid获取会员
/*
	function getMemberByMidMd5($md5){
		$arr = array();
		$arr[] = $md5;
		$sql = "select * from #__member where md5(concat('".date('Y-m-d')."',mid)) = ? and status = 1 ";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
*/
	
	//添加会员
	function addMember($mobile,$password){
		$table = "member";
		$insertColumns = array("type","mobile","password","status","regist_time");
		$insertVals = array("4",$mobile,md5($password),"1",time());
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	//获取所有兴趣标签列表
	function getInterestLabList(){
		$arr=array();
		$sql="select * from #__interest_label where status= 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
	
	//获取我的兴趣标签
	function getUserInterestLab($mid){		
		$arr=array();
		$arr[]=$mid;
		$sql= "select a.interest_labels as id, GROUP_CONCAT(b.name) as interest_label from #__member a join #__interest_label b on  FIND_IN_SET(b.label_id,a.interest_labels) where a.mid = ? group by a.mid ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
	//获取用户地址
	function getUserAddress($mid){
		$arr=array();
		$arr[]=$mid;
		$sql="select * from #__address where mid=? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	//获取地址信息详情
	function getAddressDetail($id){
		$arr=array();
		$arr[]=$id;
		$sql="select * from #__address where id=? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
	
	//获取我的实验室
	function getLaboratoryByMid($mid){
		$arr=array();
		$arr[]=$mid;
		$sql="select * from #__lab where mid= ? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	//获取用户默认地址的id
	function getDefaultAddressId($mid){
		$arr=array();
		$arr[]=$mid;
		$sql="select * from #__address where mid = ? and is_default = 1 and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	//删除实验室
	function deleteLaboratoryById($lid){
		$table = "lab";
		$updateColumns = array('status');
		$updateVals = array('0');
		$conditionColumns = array('lab_id','status');
		$conditionVals = array($lid,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//删除购物车商品
	function deleteShopcart($mid,$id){
		$table = "cart";
		$updateColumns = array('status');
		$updateVals = array('0');
		$conditionColumns = array('mid','id');
		$conditionVals = array($mid,$id);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}

    //删除收藏
    function unfavorite($mid,$id,$type){
        $table = "collection";
        $updateColumns = array('status');
        $updateVals = array('0');
        $conditionColumns = array('mid','aid','type');
        $conditionVals = array($mid,$id,$type);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);

    }

	//加入收藏夹
	function insertCollection($id,$mid,$type){
		$table = "collection";
		$insertColumns = array("mid","type","aid","status","time");
		$insertVals = array($mid,$type,$id,"1",time());
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	//验证收藏夹是否存在
	function getCollection($id,$mid,$type){
		$arr=array();
		$arr[]=$id;
		$arr[]=$mid;
		$arr[]=$type;
		$sql="select * from #__collection where aid=? and mid=? and type=? and status =1";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
	//更新购物车数量	
	function updateShopcart($id,$mid,$num){
		$table = "cart";
		$updateColumns = array('num');
		$updateVals = array($num);
		$conditionColumns = array('mid','id','status');
		$conditionVals = array($mid,$id,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	//更新用户积分
	function updatepoint($mid,$point){
		$arr=array();
		$arr[]=$point;
		$arr[]=$mid;
		$sql="update #__member set accumulated_points=accumulated_points+? where status=1 and mid=?";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
	}
	//获取优惠卷
	function getCoupon($mid){
		$time=time();
		$arr=array();
		$arr[]=$mid;
		$sql="select * from #__coupon where mid=? and start_time<".$time." and end_time>".$time." and use_status=1 and status=1 ";
		$dbAgent = DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	//查询我的收藏(1:商品/2:实验室)
	function getUserCollection($type,$mid){
		$arr=array();
		$arr[]=$mid;
		$arr[]=$type;
		if ($type == 1) {
			$sql="select a.type as coll_type,b.* from #__collection a join #__product b on a.aid = b.pid where a.mid = ? and a.type = ? and a.status = 1 ";
		} else {
			$sql="select a.type as coll_type,b.* from #__collection a join #__lab b on a.aid = b.lab_id where a.mid = ?  and a.type = ? and a.status= 1 ";
		}
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
//获取提交审核的用户信息列表
	function getUseridentity($num,$page,$status){
        $dbAgent = DBAgent::getInstance();
        $sql="select labring_member.nickname,labring_member.is_certificate,labring_identity.* from labring_identity left join labring_member on labring_identity.mid=labring_member.mid where labring_identity.status=$status";
        $a=($page-1)*$num;
        $sql.=" limit $a,$num";
        return $dbAgent->queryRecords($sql,array());
    }
//获取上面那个方法的COUNT
    function getUserIdentityCount($status){
        $sql="select count(*) as num from labring_identity where status=$status";
        $dbAgent=DBAgent::getInstance();
        $result=$dbAgent->querySingleRecord($sql,array());
        return $result['num'];
    }
    
    //获取高校或公司下所有成员
    function getAllCompanyMember($cid,$type){
	    $arr = array();
	    $arr[] = $type;
	    $arr[] = $cid; 
        $sql="select * from #__member where type = ? and sub_type <> 0 and bind_status = 2 and bind_company = ? and status = 1 ";
        $dbAgent=DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,$arr);
    }
}