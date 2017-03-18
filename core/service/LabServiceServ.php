<?php
class LabServiceServ{
	
	//构造函数->默认载入函数
    public function __construct(){
	    
    }
    
    /*获取实验室仪器*/
    function getLabInstrument(){
        $arr = array();
        $arr [] = 2;
        $sql = "select * from #__lab_service where service_type = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取实验室仪器*/
    function getLabByN($name){
        $arr = array();
        $arr [] = $name;
        $sql = "select * from #__lab where name = ? and status = 1 AND is_check = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    /*分页获取实验室仪器*/
    function getPageLabInstrument($index,$pagesize){
        $arr = array();
        $arr [] = 2;
        $sql = "select * from #__lab_service where service_type = ? and status = 1 ORDER BY lab_id ASC ";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*搜索实验室仪器*/
    function getLabInstrumentBySearch($data){
        $arr = array();
        $arr [] = 2;
        $sql = "select * from #__lab_service where name like \"%$data%\" and service_type = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页搜索实验室仪器*/
    function getPageLabInstrumentBySearch($data,$index,$pagesize){
        $arr = array();
        $arr [] = 2;
        $sql = "select * from #__lab_service where name like \"%$data%\" and service_type = ? and status = 1";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取实验室服务范围*/
    function getLabServiceRange(){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__lab_service where service_type = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /**/
    function getLabByName($name){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__lab where name like \"%$name%\" and is_check = ? AND status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页获取实验室服务范围*/
    function getPageLabServiceRange($index,$pagesize){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__lab_service where service_type = ? and status = 1 ORDER BY lab_id ASC";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*搜索实验室服务*/
    function getLabServiceBySearch($data){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__lab_service where name like \"%$data%\" and service_type = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页搜索实验室服务*/
    function getPageLabServiceBySearch($data,$index,$pagesize){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__lab_service where name like \"%$data%\" and service_type = ? and status = 1";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取所属实验室*/
    function getBelongLab($id){
        $arr = array();
        $arr [] = $id;
        $sql = "select lab.name from #__lab lab where lab_id = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    /*根据id删除服务*/
    public function deleteServiceById($id){
        $table = "lab_service";
        $updateColumns = array('status');
        $updateVals = array(0);
        $conditionColumns = array('service_id','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    /*根据id获取服务信息*/
    function getServiceById($id){
        $arr = array();
        $arr [] = $id;
        $sql = "select * from #__lab_service where service_id = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    /**/
    function getAllLab(){
        $arr = array();
        $arr [] = 1;
        $sql = "select lab.lab_id,lab.name from #__lab lab where is_check = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }
    /**/
    function getLabById($id){
        $arr = array();
        $arr [] = $id;
        $sql = "select lab.lab_id,lab.name from #__lab lab where lab_id = ? AND is_check = 1 and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    /*更新s\服务*/
    function updateService($id,$data){
        $table = "lab_service";
        if(isset($data['name'])){
            $updateColumns[] = 'name';
            $updateVals[] = $data['name'];
        }
        if(isset($data['cycle'])){
            $updateColumns[] = 'service_cycle';
            $updateVals[] = $data['cycle'];
        }
        if(isset($data['price'])){
            $updateColumns[] = 'price';
            $updateVals[] = $data['price'];
        }
        if(isset($data['labId'])){
            $updateColumns[] = 'lab_id';
            $updateVals[] = $data['labId'];
        }
        $conditionColumns = array('service_id','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    /*添加服务仪器*/
    public function insertInstrument($data){
        $table = "lab_service";
        $insertColumns = array('name','lab_id','service_cycle','price','service_type','status');
        $insertVals = array($data['name'],$data['labId'],$data['cycle'],$data['price'],2,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    /*添加服务范围*/
    public function insertServiceRange($data){
        $table = "lab_service";
        $insertColumns = array('name','lab_id','service_cycle','price','service_type','status');
        $insertVals = array($data['name'],$data['labId'],$data['cycle'],$data['price'],1,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

}