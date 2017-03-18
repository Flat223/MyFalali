<?php
class LabShareReleaseServ {
	public function __construct(){
		
	}
    /*发布共享实验室*/
    public function insertReleaseLab($lab){
       /* echo json_encode($lab);*/
        $table = "lab";
        $insertColumns = array('name','manager','manager_phone','intro','service_area','rules','mid','time','address','institude_id','status','release_time');
        $insertVals = array($lab['name'],$lab['manager'],$lab['manager_phone'],$lab['intro'],$lab['service_area'],
            $lab['rules'],$lab['mid'],$lab['time'],$lab['address'],$lab['org'],1,time());
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    public function getReleaseLab($serviceArea){
        $arr = array();
        $arr [] = $serviceArea;
        $sql = "select * from #__lab where service_area = ? AND status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }
    /*服务范围*/
    public function insertServiceRange($name,$labId){
        /* echo json_encode($lab);*/
        $table = "lab_service";
        $insertColumns = array('name','lab_id','service_type','status');
        $insertVals = array($name,$labId,1,0);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }
    /*服务仪器*/
    public function insertInstrument($name,$labId){
        /* echo json_encode($lab);*/
        $table = "lab_service";
        $insertColumns = array('name','lab_id','service_type','status');
        $insertVals = array($name,$labId,2,0);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    /*注册用户自己输入的公司名*/
   /* public function insertOrg($name){
        echo json_encode($lab);
        $table = "institude";
        $insertColumns = array('name','regist_time','status');
        $insertVals = array($name,time(),1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }*/

    public function getorgByName($name){
        $arr = array();
        $arr [] = $name;
        $sql = "select * from #__company_regist where name = ? AND status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

}
?>