<?php
class FriendsServ{
	
	public function __construct(){
	    
    }
    
	//获取我的好友数量 $type:1,关注 2,粉丝
	function getUserFriendsCount($mid,$type){		
		$arr=array();
		$arr[]=$mid;
		if($type == 1){
			$sql= "select count(*) as count from #__friends where mid = ? and status = 1 ";	
		} else {
			$sql= "select count(*) as count from #__friends where mid2 = ? and status = 1 ";
		}
		
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->querySingleRecord($sql,$arr);
		if($result === false){
			return false;
		}
		return $result['count'];
	}
	
	//获取好友列表
	function getUserfriends($mid,$type){
		$arr = array();
		$arr[]=$mid;
		if($type == 1){
			$sql="select b.* from #__friends a join #__member b on a.mid2 = b.mid where a.mid = ? and a.status = 1 and b.status = 1 ";
		} else {
			$sql="select b.* from #__friends a join #__member b on a.mid = b.mid where a.mid2 = ? and a.status = 1  and b.status = 1 ";
		}
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	
	//获取所有好友mid
	function getUserfriendsMid($mid){
		$arr=array();
		$arr[]=$mid;
		$sql="select b.mid from #__friends a join #__member b on a.mid2 = b.mid where a.mid = ? and a.status =1";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
		
	//分页查询好友动态
	function getFriendsDynamic($fids,$index,$size){//type 1:关注 2:粉丝
		$arr = array();
		$arr[] = $fids;
		$sql="select a.*,b.* from #__member a join #__article b on a.mid = b.mid where find_in_set(a.mid,?) and a.status = 1 and b.type = 1 and b.status = 1 order by b.time desc limit $index,$size ";
        $dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	
	//获取推荐好友列表(有共同圈子)
	function getRecommendfriends($mid,$friends,$size){
		$arr = array();
		$arr[] = $mid;
		$arr[] = $mid;
		$arr[] = $friends;
		$sql = "select distinct b.mid,c.* from #__circle_member a join #__circle_member b on a.circle_id = b.circle_id join #__member c on b.mid = c.mid where a.mid = ? and b.mid != ? and !find_in_set(b.mid,?) and a.status = 1 and b.status = 1 order by b.time limit $size ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	
	//检查是否是好友;
    function checkfriends($mid,$mid2){
        $dbAgent=DBAgent::getInstance();
        $table="friends";
        $columns="*";
        $conditionColumns=array('mid','mid2','status');
        $conditionVals=array($mid,$mid2,1);
        $result=$dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals,$hasPrefix=true);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    //取消关注好友
    function unfollow($mid,$follow_mid){
	    $arr=array();
		$arr[]=$mid;
		$arr[]=$follow_mid;
		$sql = "update #__friends set status = 0 where mid = ? and mid2 = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
    }
    
    //搜索好友
    function searchfriends($info){
        $sql="select * from #__member where (sub_type <> 0 or type = 4) and status = 1 ";
        if(Common::isInteger($info) && mb_strlen($info) == 11 ){
			$sql .= "and mobile = $info ";
		} else {
			$sql .= "and ( name like '%$info%' or nickname like '%$info%') ";	
		}
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }
}

?>