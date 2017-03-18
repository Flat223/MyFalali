<?php
class CompanyServ{
	public function __construct(){
		
	}
	
	//获取绑定企业/高校信息
	function getUserCompanyInfo($cid){
		$arr = array();
		$arr[] = $cid;
		$sql = "select 	case when b.id is null then '未知' else b.name end as province_name,
						case when c.id is null then '' else c.name end as city_name,
						case when d.id is null then '' else d.name end as country_name,a.* from #__member a 
						left join #__area b on a.province = b.id 
						left join #__area c on a.city = c.id   
						left join #__area d on a.country = d.id   
						where a.mid = ? and a.status = 1 ";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
/*
    function getUserCompanyInfo($cid){
        $arr = array();
        $arr[] = $cid;
        $sql = "select * from #__company_check where id = ? and state = 2 and status = 1 ";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }
*/
    
    //根据mid获取企业信息
    function getCompanyInfoByMid($mid){
        $arr = array();
        $arr[] = $mid;
        $sql = "select * from #__company_check where mid = ? and state = 2 and status = 1 ";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

	//搜索企业数量;	
	function searchCompanyCount($key,$type){//1:高校  2:企业
		$arr = array();
		$arr[] = $type;
		$sql = "select count(*) as count from #__member where type = ? and status = 1 ";	
		if($key != ""){
			$arr[] = '%'.$key.'%';
			$sql.= "and name like ? ";			
		}
		$dbAgent = DBAgent::getInstance();
	    $result = $dbAgent->querySingleRecord($sql,$arr);
	    if($result === false){
		    return  false;
	    }
	    return $result['count'];
	}
	
	//搜索企业/高校列表
	function searchCompanyList($key,$type,$index,$size){//1:高校  2:企业
		$arr = array();
		$arr[] = $type; 
		$sql = "select 	case when b.id is null then '未知' else b.name end as province_name,
						case when c.id is null then '' else c.name end as city_name,
						case when d.id is null then '' else d.name end as country_name,a.* from #__member a 
						left join #__area b on a.province = b.id 
						left join #__area c on a.city = c.id   
						left join #__area d on a.country = d.id   
						where a.type = ? and a.status = 1 ";
		if($key != ""){
			$arr[] = '%'.$key.'%';
			$sql.= "and a.name like ? limit $index,$size ";			
		    $dbAgent = DBAgent::getInstance();
		    $result = $dbAgent->queryRecords($sql,$arr);
		    return $result;
		}
		return array();
	}
	
/*
    function searchCompanyList($key,$type){//1:高校  2:企业
        $arr = array();
        $arr[] = $type;
        $sql = "select * from #__company_check where type = ? and state = 2  and status = 1 ";
        if($key != ""){
            $arr[] = '%'.$key.'%';
            $sql.= "and name like ? ";
			$sql .= "order by mid desc limit $index,$size ";
            $dbAgent = DBAgent::getInstance();
            $result = $dbAgent->queryRecords($sql,$arr);
            return $result;
        }
        return array();
    }
*/
	
	//绑定企业/高校
    function bindCompany($mid,$cid,$type){//1:高校 2:企业
        $arr = array();
        $arr[] = $type == 1 ? 2 : 1;
		$arr[] = $cid;
		$arr[] = $mid;
		$sql = "update #__member set bind_status = ?,bind_company = ? where mid = ? and status = 1 ";
		$dbAgent = DBAgent::getInstance();
        return $dbAgent->query($sql,$arr);
    }
	
	//解绑企业/高校
	function unBindCompany($mid,$cid){	
		$table = "member";
		$updateColumns = array('bind_company','bind_status');
		$updateVals = array(0,0);
		$conditionColumns = array('mid');
		$conditionVals = array($mid);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}

    function updateUnBindingData($mid){
        $table = "binding_company";
        $updateColumns = array('status');
        $updateVals = array(0);
        $conditionColumns = array('mid','is_check','status');
        $conditionVals = array($mid,1,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

	//重新绑定企业/高校(将bind_status 置为0)
	function ReBindCompany($mid){	
		$table = "member";
		$updateColumns = array('bind_company','bind_status');
		$updateVals = array(0,0);
		$conditionColumns = array('mid');
		$conditionVals = array($mid);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//注册时上传公司信息
	function uploadCompanyInfo($mid,$type,$name,$image){
		$table = "company_check";
		$insertColumns = array("mid","type","name",'business_image',"time",'state',"status");
		$insertVals = array($mid,$type,$name,$image,time(),1,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	//获取所有注册待审核公司数量;
	function getAllRegistComCount($type){
		$arr = array();
		$arr[] = $type;
		$sql = "select count(*) as count from #__company_check where status = 1 ";	
		if($type != 0){
			$sql .= "and type = ?";
		}
		$dbAgent = DBAgent::getInstance();
	    $result = $dbAgent->querySingleRecord($sql,$arr);
	    if($result === false){
		    return  false;
	    }
	    return $result['count'];
	}
	
	//后台获取所有公司注册审核
	function getAllCheckedCompany($type,$index,$size){
		$arr = array();
		$arr[] = $type;
		$sql = "select * from #__company_check where status = 1 ";	
		if($type != 0){
			$sql .= "and type = ? ";
		}
		$sql .= "order by time desc limit $index,$size ";
		$dbAgent = DBAgent::getInstance();
		return $result = $dbAgent->queryRecords($sql,$arr);
	}
	
	function delCompanycheck($mid,$company_type){
		$table = "company_check";
		$updateColumns = array('status');
		$updateVals = array('0');
		$conditionColumns = array('mid','type','status');
		$conditionVals = array($mid,$company_type,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);	
	}
	
	//处理公司审核
	function operateCheckCompany($mid,$type,$company_type){
		$table = "company_check";
		if($type == 1){
			$updateColumns = array('state');
			$updateVals = array('2');
		} else {
			$updateColumns = array('state');
			$updateVals = array('3');
		}
		$conditionColumns = array('mid','type','status');
		$conditionVals = array($mid,$company_type,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);	
	}
	
	//获取审核中公司信息
	function getCompanyInfoOnUpdate($mid,$company_type){
		$arr = array();
		$arr[] = $company_type;
		$arr[] = $mid;
		$sql = "select * from #__company_check where type = ? and mid = ? and state = 1 and status = 1 "; 
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
	
	//更新公司身份
	function updateCompanyIdent($company){
		$table = "member";
		$updateColumns = array('type','name','face');
		$updateVals = array($company['type'],$company['name'],$company['business_image']);
		$conditionColumns = array('mid','type','status');
		$conditionVals = array($company['mid'],'4','1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//获取公司成员数量
	function getCompanyMemberCount($cid,$state){
		$arr = array();
		$arr[] = $cid;
		$arr[] = $state;
		$sql = "select count(*) as count from #__member where type = 2 and sub_type <> 0 and bind_company = ? and bind_status = ? and status = 1 ";
		$dbAgent = DBAgent::getInstance();
        $result = $dbAgent->querySingleRecord($sql,$arr);
        if($result === false){
	        return false;
        }
        return $result['count'];
	}
	
	//分页获取公司成员
	function getCompanyMember($cid,$state,$index,$size){
		$arr = array();
		$arr[] = $cid;
		$arr[] = $state;
		$sql = "select * from #__member where type = 2 and sub_type <> 0 and bind_company = ? and bind_status = ? and status = 1 limit $index,$size ";
		$dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
	}

    //后台获取所有未审核绑定企业申请
    function getBindingCompany($ch,$mid){
        $arr = array();
        $sql = "";
        $arr[] = $mid;
        if($ch == 1){
            $sql = "select b.* from #__binding_company b join #__company_check c on b.cid = c.id where c.mid = ? and b.status = 1 and b.is_check = 0 ORDER BY b.time desc";
        }else if($ch == 2){
            $sql = "select b.* from #__binding_company b join #__company_check c on b.cid = c.id where c.mid = ? and b.status = 1 and b.is_check = 1 ORDER BY b.time desc";
        }else if($ch == 3){
            $sql = "select b.* from #__binding_company b join #__company_check c on b.cid = c.id where c.mid = ? and b.status = 1 and b.is_check = 2 ORDER BY b.time desc";
        }
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    //后台分页获取所有未审核绑定企业申请
    function getPageBindingCompany($ch,$mid,$index,$pagesize){
        $arr = array();
        $arr[] = $mid;
        $sql = "";
        if($ch == 1){
            $sql = "select b.* from #__binding_company b join #__company_check c on b.cid = c.id where c.mid = ? and b.status = 1 and b.is_check = 0 ORDER BY b.time desc";
        }else if($ch == 2){
            $sql = "select b.* from #__binding_company b join #__company_check c on b.cid = c.id where c.mid = ? and b.status = 1 and b.is_check = 1 ORDER BY b.time desc";
        }else if($ch == 3){
            $sql = "select b.* from #__binding_company b join #__company_check c on b.cid = c.id where c.mid = ? and b.status = 1 and b.is_check = 2 ORDER BY b.time desc";
        }
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*//后台获取所有已审核审核绑定企业申请
    function getCDBindingCompany(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__binding_company where is_check = ? AND status = 1 ORDER BY TIME desc";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    //后台分页获取所有已审核绑定企业申请
    function getPageCDBindingCompany($index,$pagesize){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__binding_company where is_check = ? AND status = 1 ORDER BY TIME DESC ";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }*/

    //后台获取申请绑定企业的的企业
    function getCompanyById($id){
        $arr = array();
        $arr[] = $id;
        $sql = "select * from #__company_check where id = ? AND state = 2 and  status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    //后台获取申请绑定企业的用户
    function getMemberById($id){
        $arr = array();
        $arr[] = $id;
        $sql = "select * from #__member where mid = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }
    //根据id删除绑定企业数据后台
    function deleteData($id){
        $table = "binding_company";
        $updateColumns = array('status');
        $updateVals = array(0);
        $conditionColumns = array('id','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    //公司处理绑定请求
    function handleCompanyBind($cid,$mid,$type){
		$arr = array();
        $arr[] = $type == 1 ? 2 : 3;
		$arr[] = $cid;
		$arr[] = $mid;
		$sql = "update #__member set bind_status = ? where bind_company = ? and mid = ? and status = 1 ";
		$dbAgent = DBAgent::getInstance();
        return $dbAgent->query($sql,$arr);
    }
    
    //公司删除旗下成员
    function deleteCompanyMember($cid,$mid){
	    $arr = array();
		$arr[] = $cid;
		$arr[] = $mid;
		$sql = "update #__member set bind_status = 0,bind_company = 0 where bind_company = ? and mid = ? and bind_status = 2 and status = 1 ";
		$dbAgent = DBAgent::getInstance();
        return $dbAgent->query($sql,$arr);
    }
    
    //公司添加成员
    function addCompanyMember($cid,$member){
	    $table = "member";
		$insertColumns = array('type','regist_time','bind_status','bind_company','status');
		$insertVals = array(2,time(),2,$cid,1);
		
		if(isset($member['sub_type'])){
			$insertColumns[] = 'sub_type';
			$insertVals[] = $member['sub_type'];
		}
		if(isset($member['nickname'])){
			$insertColumns[] = 'nickname';
			$insertVals[] = $member['nickname'];
		}
		if(isset($member['realname'])){
			$insertColumns[] = 'name';
			$insertVals[] = $member['realname'];
		}
		if(isset($member['mobile'])){
			$insertColumns[] = 'mobile';
			$insertVals[] = $member['mobile'];
		}
		if(isset($member['password'])){
			$insertColumns[] = 'password';
			$insertVals[] = md5($member['password']);
		}
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    function getCompanyByName($name){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__company_check where name like \"%$name%\"  and state = 2 and status = ? ";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    //给用户发送消息
    public function sendUserBindingMessage($bj,$fid,$mid,$name,$reason){
        $table = "message";
        $insertColumns = array("from_id","mid","title","content","time","is_read","status");
        $insertVals = array();
        if($bj == 0){
            $reason = "恭喜你通过我司审核！";
            $insertVals = array($fid,$mid,"审核通知（".$name."）",$reason,time(),0,1);
        }else if($bj == 1){
            $insertVals = array($fid,$mid,"审核通知（".$name."）",$reason,time(),0,1);
        }
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }


}
?>