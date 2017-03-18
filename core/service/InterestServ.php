<?php
class InterestServ{
    //获取所有标签
    public function GetAllLabel(){
        $DBAgent=DBAgent:: getInstance();
        $label=$DBAgent->getRecordsFromTable('interest_label','*',$conditionColumns=array('status'),$conditionVals=array('1'),$hasPrefix=true,$hasPage=false,$offset=1,$size=5,$orderby='label_id');
        foreach($label as $k=>$v){
            $label2[$v['label_id']]=$v['name'];
        }
        return $label2;
    }
    //根据用户获取五个最热门的圈子,提出不敢兴趣的
    public function GetFiveHotByUser($user,$num,$page){
        $a=($page-1)*$num;
        $DBAgent=DBAgent:: getInstance();
        $sql="select * from labring_interest_circle where circle_id not in(select circle_id from labring_notinterest_member where 1=1";
        if(isset($user)){
            $sql.=" and mid=$user";
        }
        $sql.=" and status=1) order by view_num DESC limit $a,$num";
        $hot=$DBAgent->queryRecords($sql,array());
//        $hot=$DBAgent->getRecordsFromTable('interest_circle','*',$conditionColumns=array('status'),$conditionVals=array('1'),$hasPrefix=true,$hasPage=true,$offset=1,$size=5,$orderby='view_num DESC');
        foreach($hot as $k=>$v){
            $industry_ids=explode(',',$v['industry_ids']);
            $interest_labels=explode(',',$v['interest_labels']);
            $hot[$k]['industry_ids']=$industry_ids;
            $hot[$k]['interest_labels']=$interest_labels;
        }
        return $hot;
    }
    //获取圈子总数
    public function GetHotCount($user){
        $sql="select count(*) as num from labring_interest_circle where circle_id not in(select circle_id from labring_notinterest_member where 1=1";
        if(isset($user)){
            $sql.=" and mid=$user";
        }
        $sql.=" and status=1)";
        $DBAgent=DBAgent:: getInstance();
        $hot=$DBAgent->querySingleRecord($sql,array());
        return $hot['num'];

    }
    //获取五个不感兴趣的最热门最热门的圈子,
    public function GetFiveHot($user){
        $DBAgent=DBAgent:: getInstance();
        $sql="select * from labring_interest_circle where circle_id in(select circle_id from labring_notinterest_member where mid=$user and status=1) order by view_num DESC limit 1,5";
        $hot=$DBAgent->queryRecords($sql,array());
        foreach($hot as $k=>$v){
            $industry_ids=explode(',',$v['industry_ids']);
            $interest_labels=explode(',',$v['interest_labels']);
            $hot[$k]['industry_ids']=$industry_ids;
            $hot[$k]['interest_labels']=$interest_labels;
        }
        return $hot;
    }
    //根据兴趣获取圈子
    public function GetInterestLabels($inter,$user){
        $DBAgent=DBAgent:: getInstance();
        $sql="select * from labring_interest_circle where FIND_IN_SET('".$inter."'".",interest_labels) and circle_id not in(select circle_id from labring_circle_member where mid=".$user." and status =1) and circle_id not in(select circle_id from labring_notinterest_member where mid=".$user." and status=1) AND status = 1 limit 5";
        $ret=$DBAgent->queryRecords($sql,array());
        return $ret;
    }
    //根据行业获取圈子
    public function GetIndustryIds($indus,$user){
        $DBAgent=DBAgent:: getInstance();
        $sql="select * from labring_interest_circle where FIND_IN_SET('".$indus."'".",industry_ids) and circle_id not in(select circle_id from labring_circle_member where mid=".$user." and status=1) and circle_id not in(select circle_id from labring_notinterest_member where mid=".$user." and status=1)AND status = 1 limit 5";
        $res=$DBAgent->queryRecords($sql,array());
        return $res;

    }
    //当仍然凑不够五条时，根据ID倒去五条,剔除不感兴趣的
    public function GetById($user){
        $DBAgent=DBAgent:: getInstance();
        $sql="select * from labring_interest_circle where circle_id not in(select circle_id from labring_circle_member where mid=".$user." and status=1) and circle_id not in(select circle_id from labring_notinterest_member where mid=".$user." and status=1) and status=1 order by circle_id desc limit 5";
        $reu=$DBAgent->queryRecords($sql,array());
        return $reu;
    }
    //仍然凑不够五条时候，根据Id去找，包含了不感兴趣的
    public function GetBynotinterest($user){
        $DBAgent=DBAgent:: getInstance();
        $sql="select * from labring_interest_circle where circle_id not in(select circle_id from labring_circle_member where mid=".$user." and status=1) and circle_id in(select circle_id from labring_notinterest_member where mid=".$user." and status=1) and status=1 order by circle_id desc limit 5";
        $rev=$DBAgent->queryRecords($sql,array());
        return $rev;
    }
    public function GetbyTime($num){
        $DBAgent=DBAgent:: getInstance();
        $sql="select * from labring_interest_circle where status=1 order by time limit $num";
        $rev=$DBAgent->queryRecords($sql,array());
        return $rev;
    }
//阅读量加一
    public function Addnum($id){
        $dbAgent = DBAgent::getInstance();
        $sql="update labring_interest_circle set view_num=view_num+1 where circle_id=$id";
        if($dbAgent->query($sql,array())){
            return true;
        }else{
            return false;
        }
    }
    //评论加一

    public function Addtalknum($id){
        $dbAgent = DBAgent::getInstance();
        $sql="update labring_interest_circle set talk_num=talk_num+1 where circle_id=$id";
        if($dbAgent->query($sql,array())){
            return true;
        }else{
            return false;
        }
    }
    //登录用户的圈子首页展示
    //根据用户关注获取圈子的帖子，用户关注的排在前面，没关注的排在后面
    //$mid是用户的mid
    public function GetuserTopicA($mid,$num,$page){
        $a=($page-1)*$num;
        $sql="select C.nickname,C.name as uname,C.mobile,C.face,A.name,B.* from labring_interest_circle A RIGHT JOIN (
              (select * from (select * from labring_topic where circle_id in(select circle_id from labring_circle_member where mid=$mid and status=1) 
              and status=1 order by topic_id desc)D)
              union all (select * from (select * from labring_topic where circle_id not in(select circle_id from labring_circle_member where mid=$mid) 
              and status=1 order by topic_id desc)E)
              union all (select * from (select * from labring_topic where circle_id in(select circle_id from labring_circle_member where mid=$mid 
              and status=0) and status=1 order by topic_id desc)F))
              B on A.circle_id=B.circle_id left JOIN
              labring_member C on C.mid=B.mid where A.status = 1  limit $a,$num";
        $dbAgent = DBAgent::getInstance();
        $rev=$dbAgent->queryRecords($sql,array());
        return $rev;
    }

    //获取关注的圈子评论
    public function getCircleComment($mid){
        $sql = "SELECT
                    count(*) as co
                FROM
                    labring_topic t
                JOIN labring_member m ON t.mid = m.mid
                JOIN labring_interest_circle ic ON t.circle_id = ic.circle_id
                JOIN (
                    SELECT
                        GROUP_CONCAT(c.circle_id) AS cids
                    FROM
                        labring_circle_member m
                    JOIN labring_interest_circle c ON m.circle_id = c.circle_id
                    WHERE
                        m.mid = $mid
                    AND c. STATUS = 1
                ) circles ON FIND_IN_SET(t.circle_id, circles.cids)
                WHERE
                    t. STATUS = 1
                AND m. STATUS = 1";
        $dbAgent = DBAgent::getInstance();
        $result = $dbAgent->queryRecords($sql,array());
        return $result[0]['co'];
    }

    //获取关注的圈子评论
    public function getPageCircleComment($mid,$index,$pagesize){
        $sql = "SELECT
                    t.*, m.nickname,m.face,ic.name
                FROM
                    labring_topic t
                JOIN labring_member m ON t.mid = m.mid
                JOIN labring_interest_circle ic ON t.circle_id = ic.circle_id
                JOIN (
                    SELECT
                        GROUP_CONCAT(c.circle_id) AS cids
                    FROM
                        labring_circle_member m
                    JOIN labring_interest_circle c ON m.circle_id = c.circle_id
                    WHERE
                        m.mid = $mid
                    AND c. STATUS = 1
                ) circles ON FIND_IN_SET(t.circle_id, circles.cids)
                WHERE
                    t. STATUS = 1
                AND m. STATUS = 1";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }

    //未登录用户的圈子首页展示
    public function GetuserTopicB($num,$page){
        $a=($page-1)*$num;
        $sql="select C.nickname,C.name as uname,C.mobile,C.face,A.name,B.* from 
                labring_interest_circle A RIGHT JOIN labring_topic
                B on A.circle_id=B.circle_id left JOIN
                labring_member C on C.mid=B.mid order by topic_id limit $a,$num";
        $dbAgent = DBAgent::getInstance();
        $rev=$dbAgent->queryRecords($sql,array());
        return $rev;

    }

    public function GetAllTopicCount(){
        $sql="select count(*) as num from labring_topic where status=1";
        $dbAgent = DBAgent::getInstance();
        $rev=$dbAgent->querySingleRecord($sql,array());
        return $rev['num'];
    }
}
?>