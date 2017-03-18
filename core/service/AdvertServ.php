<?php
class AdvertServ
{
    public function __construct()
    {

    }
    public function getAdvert($type,$num){
        $sql="select * from #__textad where status=1 and type=$type order by id desc limit $num";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,array());
    }
    //随机获得一定数量的广告
    public function getAdvertbyrand($type,$num){
        $sql="select * from #__textad where status=1 and type=$type order by rand() limit $num";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,array());
//        return $sql;
    }

    public function getAdvertbytype($num,$page,$type=null){
        $a=($page-1)*$num;
        $sql="select * from #__textad where status=1";
        if($type && $type!=''){
            $sql.=" and type=$type";
        }
        $sql.=" limit $a,$num";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,array());
    }
    public function getAdvertCount($type=null){
        $sql="select count(*) as num from #__textad where status=1";
        if($type && $type!=''){
            $sql.=" and type!=$type";
        }
        $dbAgent = DBAgent::getInstance();
        $result=$dbAgent->querySingleRecord($sql,array());
        return $result['num'];
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
}