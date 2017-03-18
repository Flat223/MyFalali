<?php
class ShareServ{
	
	//构造函数->默认载入函数
    public function __construct(){
	    
    }
    /*获取一级实验室分类*/
    public function getLabTypes(){
        $table = "lab_type";
        $columns = "*";
        $conditionColumns = array('level','status');
        $conditionVals = array(1,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->getRecordsFromTable($table,$columns,$conditionColumns,$conditionVals,$hasPrefix=true,$hasPage=false,$offset=0,$size=10);
    }

    /*获取二级实验室分类*/
    public function getChildLabTypes($labId){
        $arr = array();
        $arr[] = $labId;
        $sql = "select * from #__lab_type where parentid = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,$arr);
    }

    /*获取实验室共享新闻*/
    public function getNews(){
        $table = "news";
        $columns = "*";
        $conditionColumns = array('status');
        $conditionVals = array(1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->getRecordsFromTable($table,$columns,$conditionColumns,$conditionVals,$hasPrefix=true,$hasPage=true,$offset=0,$size=5);
    }

    /*获取合作实验室4个*/
    public function getLabs(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__lab where status = ? and is_check = 1 ORDER BY stars DESC limit 4";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取所有合作实验室*/
    public function getAllLab(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__lab where status = ? and is_check = 1 ORDER BY stars DESC";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取15合作实验室=====手机端*/
    public function getInitNumLab($start,$end){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__lab where status = ? and is_check = 1 ORDER BY stars DESC";
        $sql.= " limit ".$start.",".$end;
      /*  echo $sql;*/
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页获取所有合作实验室*/
    function getPageAllLab($index,$pagesize){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__lab where status = ? and is_check = 1 ORDER BY stars DESC";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取热门实验室3个*/
    public function getHotLab(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__lab where status = ? and is_check = 1 ORDER BY view_num DESC limit 3";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取更多热门实验室*/
    public function getMoreHotLab(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__lab where status = ? and is_check = 1 ORDER BY view_num DESC";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页获取更多热门实验室*/
    function getPageMoreHotLab($index,$pagesize){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__lab where status = ? and is_check = 1 ORDER BY view_num DESC";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*根据当前城市获取该城市下的实验室*/
    public function getLabByAddress($address){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__lab where address like \"%$address%\" and status = ? and is_check = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*根据地址搜索匹配的实验室，显示在地图上*/
    public function getLabInfoByAddress($address){
        $arr = array();
        $arr[] = 1;
        $sql = "select a.name,a.manager_phone,a.lat,a.lon,a.address from #__lab a where address like \"%$address%\" and status = ? and is_check = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取未审核的实验室*/
    public function getNotCheckLab(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__lab where status = ? and is_check = 0";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页获取未审核的实验室*/
    public function getPageNotCheckLab($index,$pagesize){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__lab where status = ? and is_check = 0 ORDER BY time desc";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取未审核的发布信息*/
    public function getNotCheckRelease(){
        $arr = array();
        $arr[] = 1;
        $arr[] = time();
        $sql = "select * from #__release where status = ? and endDate > ? and is_check = 0";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页获取未审核的发布信息*/
    public function getPageNotCheckRelease($index,$pagesize){
        $arr = array();
        $arr[] = 1;
        $arr[] = time();
        $sql = "select * from #__release where status = ? and endDate > ? and is_check = 0 ORDER BY endDate desc";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取已审核的发布信息*/
    public function getCheckedRelease(){
        $arr = array();
        $arr[] = 1;
        $arr[] = time();
        $sql = "select * from #__release where status = ? and endDate > ? and is_check = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页获取已审核的发布信息*/
    public function getPageCheckedRelease($index,$pagesize){
        $arr = array();
        $arr[] = 1;
        $arr[] = time();
        $sql = "select * from #__release where status = ? and endDate > ? and is_check = 1 ORDER BY endDate desc";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取已审核的实验室*/
    public function getCheckedLab(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__lab where status = ? and is_check = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页获取已审核的实验室*/
    public function getPageCheckedLab($index,$pagesize){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__lab where status = ? and is_check = 1 ORDER BY time desc";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*根据名称获取实验室*/
    function getNCLabByName($name){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__lab where name like \"%$name%\" AND is_check = 0 AND status = ?";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页根据名称获取未审核实验室*/
    function getPageNCLabByName($index,$pagesize,$name){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__lab where name like \"%$name%\" AND is_check = 0 AND status = ? ORDER BY time desc";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*根据名称获取发布需求*/
    function getNCReleaseByName($name){
        $arr = array();
        $arr [] = 1;
        $arr [] = time();
        $sql = "select * from #__release where title like \"%$name%\" AND is_check = 0  AND status = ? and endDate > ?";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页根据名称获取未审核发布需求*/
    function getPageNCReleaseByName($index,$pagesize,$name){
        $arr = array();
        $arr [] = 1;
        $arr [] = time();
        $sql = "select * from #__release where title like \"%$name%\" AND is_check = 0 AND status = ? and endDate > ? ORDER BY endDate desc";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*根据名称获取发布信息*/
    function getCDReleaseByName($name){
        $arr = array();
        $arr [] = 1;
        $arr [] = time();
        $sql = "select * from #__release where title like \"%$name%\" AND is_check = 1 AND status = ? and endDate > ?";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页根据名称获取未审核发布信息*/
    function getPageCDReleaseByName($index,$pagesize,$name){
        $arr = array();
        $arr [] = 1;
        $arr [] = time();
        $sql = "select * from #__release where title like \"%$name%\" AND is_check = 1 AND status = ? and endDate > ? ORDER BY endDate desc";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*根据名称获取实验室*/
    function getCDLabByName($name){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__lab where name like \"%$name%\" AND is_check = 1 AND status = ?";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页根据名称获取未审核实验室*/
    function getPageCDLabByName($index,$pagesize,$name){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__lab where name like \"%$name%\" AND is_check = 1 AND status = ? ORDER BY time desc";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*判断是否已收藏*/
    public function isCollectioned($mid,$labId){
        $arr = array();
        $arr [] = $mid;
        $arr [] = $labId;
        $sql = "select * from #__collection where mid = ? and aid = ? AND type = 2 and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    /*判断是否已收藏*/
    public function getCollectedLab($mid){
        $arr = array();
        $arr [] = $mid;
        $sql = "select c.aid from #__collection c where c.mid = ? and type = 2 and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*收藏实验室*/
    public function collectionLab($mid,$labId){
        $table = "collection";
        $insertColumns = array("mid",'aid','type','time',"status");
        $insertVals = array($mid,$labId,2,time(),1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    /*预约*/
    public function bespeak($mid){
        $table = "bespeak";
        $insertColumns = array("mid",'time',"status");
        $insertVals = array($mid,time(),1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }


}