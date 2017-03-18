<?php
class TopicServ{

    //根据用户获取帖子,用户信息
    public function GetTopicById($id){
        $DBAgent = DBAgent::getInstance();
        $sql="select labring_interest_circle.circle_id as cid,labring_interest_circle.name as cname,labring_member.name as mname,labring_member.face,labring_topic.* from labring_topic left join labring_member
            on labring_member.mid=labring_topic.mid left join labring_interest_circle on labring_interest_circle.circle_id=labring_topic.circle_id where labring_topic.topic_id=$id  and labring_topic.status=1";
        $result=$DBAgent->queryRecords($sql,array());
        return $result[0];
    }

    //根据用户获取他的其他帖子
    public function GetTopicByMid($mid,$num){
        $DBAgent = DBAgent::getInstance();
        $sql="select labring_interest_circle.name as cname,labring_topic.* from labring_topic left join labring_interest_circle on labring_interest_circle.circle_id=
              labring_topic.circle_id where labring_topic.mid=$mid and labring_topic.status=1 order by labring_topic.time desc limit $num";
        $result=$DBAgent->queryRecords($sql,array());
        return $result;
    }
    //查看某人发了多少帖子
    public function GetNumBymid($mid){
        $DBAgent = DBAgent::getInstance();
        $sql="select count(*) as a from labring_topic where mid=$mid and status=1";
        $result=$DBAgent->queryRecords($sql,array());
        return $result['0']['a'];
    }

    //根据Topic_id查询所有回帖
    public function Getreplay($id,$page=1,$num=15){
        $a=($page-1)*$num;
        $DBAgent = DBAgent::getInstance();
        $sql="select labring_member.name,labring_member.face,labring_topic_replay.* from labring_topic_replay left join labring_member on labring_topic_replay.mid=labring_member.mid where labring_topic_replay.topic_id=$id and labring_topic_replay.status=1 limit $a,$num";
        $result=$DBAgent->queryRecords($sql,array());
        return $result;
    }
    //根据id查询贴数
    public function Getreplaynum($id){
        $DBAgent = DBAgent::getInstance();
        $sql="select count(*) as a from labring_topic_replay where topic_id=$id and status=1";
        $result=$DBAgent->queryRecords($sql,array());
        return $result['0']['a'];
    }
    //验证用户有没有关注这个圈子
    public function Checkisfollow($circle_id,$mid){
        $DBAgent = DBAgent::getInstance();
        $sql="select count(*) as num from #__circle_member where circle_id=$circle_id and mid=$mid and status=1";
        $result=$DBAgent->querySingleRecord($sql,array());
        if($result['num']<=0){
            return false;
        }else{
            return true;
        }
    }

}
?>