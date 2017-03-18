<?php
class NewsServ{
    public function __construct()
    {
    }

    //获取最新新闻资讯(7*24h咨询)
    public function GetNewsBytime($num,$page=1,$order='time desc',$not=null,$info=null){
        $DBAgent=DBAgent::getInstance();
        $a=($page-1)*$num;
        $sql="select * from labring_news where status=1";
        if($not){
            $sql.=" and id!=$not";
        }
        if(!empty($info)){
            $sql.=" and title like '%$info%'";
        }
        $sql.=" order by $order";
        if($num!=0){
            $sql.=" limit $a,$num";
        }
        $result=$DBAgent->queryRecords($sql,array());
        return $result;
    }
    public function GetNewsThisweek(){
        $DBAgent=DBAgent::getInstance();
        $time=time()-7*24*3600;
        $sql="select * from #__news where status=1 and time>$time order by id desc";
        $result=$DBAgent->queryRecords($sql,array());
        return $result;
    }
    //根据ID来获取NEW
    public function GetNewsById($id){
        $DBAgent=DBAgent::getInstance();
        $sql="select * from labring_news where status=1 and id=$id";
        $result=$DBAgent->queryRecords($sql,array());
        $return = "";
        if(!empty($result))
        {
	        $return = $result['0'];
        }
        return $return;
    }
    //获取新闻总数
    public function Getcount($info=null){
        $dbAgent = DBAgent::getInstance();
        $sql="select count(*) as num from labring_news where status=1";
        if($info){
            $sql.=" and title like '%$info%'";
        }
        $result=$dbAgent->querySingleRecord($sql,array());
        return $result['num'];
    }

    /*根据id获取该新闻评论*/
    public function getCommentById($id,$num,$page=1){
        $a=($page-1)*$num;
        $arr = array();
        $arr[] = $id;
        $sql="select * from #__news_comment where pid = ? and status = 1 ORDER BY time desc limit $a,$num";
        $DBAgent=DBAgent::getInstance();
        return $DBAgent->queryRecords($sql,$arr);
    }

    /*根据评论的用户id 获取该用户*/
    public function getUserById($uid){
        $arr = array();
        $arr[] = $uid;
        $sql="select u.mid,u.name,u.nickname from #__member u  where mid = ? and status = 1";
        $DBAgent=DBAgent::getInstance();
        return $DBAgent->querySingleRecord($sql,$arr);
    }
    //根据新闻ID获取评论数
    public function GetNewsnumByid($id){
        $DBAgent = DBAgent::getInstance();
        $sql="select count(*) as a from labring_news_comment where pid=$id and status=1";
        $result=$DBAgent->queryRecords($sql,array());
        return $result['0']['a'];
    }
    //根据id阅读数数加一
    public function Addviewnum($id){
        $dbAgent = DBAgent::getInstance();
        $sql="update labring_news set view_num=view_num+1 where id=$id";
        if($dbAgent->query($sql,array())){
            return true;
        }else{
            return false;
        }
    }

    /*获取广告*/
    public function getAdvert($type=null){
        $sql="select * from labring_textad where status = 1";
        if($type){
            $sql.=" and type=$type";
        }
        $DBAgent=DBAgent::getInstance();
//        return $DBAgent->queryRecords($sql,array());
        return $sql;
    }

    /*获取分页广告*/
    public function getPageAdvert($index,$pagesize,$type=null){
        $sql="select * from labring_textad where status = 1";
        if($type){
            $sql.=" and type=?";
        }
        $sql .= " limit $index,$pagesize";
        $DBAgent=DBAgent::getInstance();
        return $DBAgent->queryRecords($sql,array());
    }

    /*根据id删除广告*/
    public function deleteAdvertById($id){
        $table = "textad";
        $updateColumns = array('status');
        $updateVals = array(0);
        $conditionColumns = array('id','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    /*更新广告*/
    function updateAdvert($id,$data){
        $table = "textad";
        if(isset($data['desc'])){
            $updateColumns[] = 'description';
            $updateVals[] = $data['desc'];
        }
        if(isset($data['url'])){
            $updateColumns[] = 'url';
            $updateVals[] = $data['url'];
        }
        $conditionColumns = array('id','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    /*根据id获取广告*/
    public function getAdvertById($id){
        $arr = array();
        $arr[] = $id;
        $sql="select * from labring_textad where id = ? and status = 1";
        $DBAgent=DBAgent::getInstance();
        return $DBAgent->querySingleRecord($sql,$arr);
    }

    /*添加广告*/
    public function insertAdvert($data){
        $table = "textad";
        $insertColumns = array('url','description','status');
        $insertVals = array($data['url'],$data['desc'],1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }
    //根据关键字来搜索新闻
    public function Searchnews($info){
        $sql="select * from labring_news where title like \"%$info%\"; order by id desc";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }
}
?>