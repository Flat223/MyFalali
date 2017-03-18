<?php
class CircleServ{
	
	public function __construct(){
	    
    }
    //根据ID取圈子信息
    function getCircleByid($id){
        $table = "interest_circle";
        $columns = "*";
        $conditionColumns = array('circle_id','status');
        $conditionVals = array($id,'1');
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals);
    }
    //查询我的所有圈子数量
	function getUserCircleCount($mid){
		$arr=array();
		$arr[]=$mid;
		$sql= "select count(*) as count from #__interest_circle a join #__circle_member b on a.circle_id = b.circle_id where b.mid = ? and b.status = 1";	
		
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->querySingleRecord($sql,$arr);
		if($result === false){
			return false;
		}
		return $result['count'];	
	}
	
	//获取圈子动列表
	function getUserCircleList($mid,$index,$size){
		$arr=array();
		$arr[]=$mid;
		$sql= "select a.* from #__interest_circle a join #__circle_member b on a.circle_id = b.circle_id where b.mid = ? and b.status = 1 and a.status=1 limit $index,$size ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	
	//获取圈子动态
	function getUserCircleDynamic($cid,$size){
		$arr=array();
		$arr[]=$cid;
		$sql = "select b.name as author,b.face,a.* from #__topic a join #__member b on a.mid = b.mid where a.circle_id = ? and a.status = 1 order by a.time desc limit $size ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	//根据id获取圈子内容和用户信息
    function getTopicByid($id,$page,$num){
        $a=($page-1)*$num;
        $dbAgent=DBAgent::getInstance();
        $sql="select labring_member.mid as mmid,labring_member.name,labring_member.face,labring_member.nickname,labring_topic.* from labring_topic left join labring_member on labring_member.mid=labring_topic.mid where labring_topic.circle_id=$id limit $a,$num";
        return $dbAgent->queryRecords($sql,array());
    }
    //获取推荐好友
    public function getFriendByCid($cid,$mid,$num){
        $dbAgent=DBAgent::getInstance();
        $sql="select * from labring_member where mid in(select mid from labring_circle_member where circle_id=$cid) and mid not in(select mid2 from labring_friends where mid=$mid) and mid!=$mid and status!=0 order by mid limit $num";
        return $dbAgent->queryRecords($sql,array());

    }
    //根据circle_id获取圈子里的帖数
    public function getCountByCid($cid){
        $dbAgent=DBAgent::getInstance();
        $sql="select count(*) as a from labring_topic where circle_id=$cid and status=1";
        $num=$dbAgent->queryRecords($sql,array());
        return $num[0]['a'];
    }
    public function Checkcircle($mid,$cid)
    {
        $DBAgent=DBAgent::getInstance();
        $sql = "select * from labring_circle_member where mid=$mid and circle_id=$cid";
        $check = $DBAgent->querySingleRecord($sql, array());
        if ($check != null && $check['status'] == '1') {
            return 0;
        }else {
            return 1;
        }
    }
    
    //加入新圈子时获取的推荐的圈子
	function getRecommendCircleList($size){
		$sql = "select * from #__interest_circle where status = 1 order by member limit $size ";
	    $dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,array());
	}
	
	//根据字段搜索圈子(加入新圈子界面)
	function searchCircleByKey($key){
		$arr = array();
		$sql = "select * from #__interest_circle where status = 1 ";
		if($key != ""){
		   	$arr[] = '%'.$key.'%';
		   	$sql .= "and name like ? ";
	   	}
	   	
	   	$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	//获取所有圈子
    public function GetAllCircle($page,$num){
        $a=($page-1)*$num;
        $sql="select * from labring_interest_circle where status=1 order by time desc limit $a,$num";
        $dbAgent=DBAgent::getInstance();
		$circle=$dbAgent->queryRecords($sql,array());
        foreach($circle as $k=>$v){
            $sql2="select * from labring_interest_label where FIND_IN_SET(label_id,'".$v['interest_labels']."')";
            $circle[$k]['label']=$dbAgent->queryRecords($sql2,array());
        }
        return $circle;
    }
    public function GetNum(){
        $sql="select count(*) as num from labring_interest_circle where status=1";
        $dbAgent=DBAgent::getInstance();
        $result=$dbAgent->queryRecords($sql,array());
        return $result[0]['num'];
    }
    public function Getlabel(){
        $sql="select * from labring_interest_label where status=1";
        $dbAgent=DBAgent::getInstance();
        $result=$dbAgent->queryRecords($sql,array());
        return $result;
    }

    /*获取圈子成员*/
    public function getCircleMembers($cid){
        $arr = array();
        $arr[] = $cid;
        $sql="select m.* from labring_member m join labring_circle_member cm on m.mid = cm.mid where cm.circle_id = ? and m.status=1";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页获取圈子成员*/
    public function getPageCircleMembers($cid,$index,$pagesize){
        $arr = array();
        $arr[] = $cid;
        $sql="select m.* from labring_member m join labring_circle_member cm on m.mid = cm.mid where cm.circle_id = ? and m.status=1";
        $sql .= " limit $index,$pagesize";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    public function insertCircleComment($data){
        $table = "topic";
        $insertColumns = array('circle_id','mid','content','time','status');
        $insertVals = array($data['id'],$data['mid'],$data['content'],time(),1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

}
?>