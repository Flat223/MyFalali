<?php
class LabDetailServ{
	
	//构造函数->默认载入函数
    public function __construct(){
	    
    }
    /*根据id获取实验室*/
    function getLabDetail($labId){
        if(!empty($labId)){
            $table = "lab";
            $columns = "*";
            $conditionColumns = array('lab_id','status');
            $conditionVals = array($labId,1);
            $dbAgent = DBAgent::getInstance();
            return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals,$hasPrefix=true);
        }
    }

    //更新浏览次数
    public function updateViewNum($labId,$viewNum){
        $table = "lab";
        $updateColumns = array('view_num');
        $updateVals = array($viewNum+1);
        $conditionColumns = array('lab_id');
        $conditionVals = array($labId);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    /*根据id获取实验室的科研数据*/
    function getLabResearch ($labId){
        if(!empty($labId)){
            $table = "lab_datum";
            $columns = "*";
            $conditionColumns = array('lab_id','status');
            $conditionVals = array($labId,1);
            $dbAgent = DBAgent::getInstance();
            return $dbAgent->getRecordsFromTable($table,$columns,$conditionColumns,$conditionVals,$hasPrefix=true,$hasPage=false,$offset=0,$size=10);
        }else{
            FileUtil::loadServerErrHtml();
        }
    }

    /*获取实验室科研数据的类型*/
    function getResearchType (){
            $table = "research_type";
            $columns = "*";
            $conditionColumns = array('status');
            $conditionVals = array(1);
            $dbAgent = DBAgent::getInstance();
            return $dbAgent->getRecordsFromTable($table,$columns,$conditionColumns,$conditionVals,$hasPrefix=true,$hasPage=false,$offset=0,$size=10);
    }

    /*根据实验室机构 id 获取机构*/
    function getBelongInstitude($institude_id){
            $table = "company_regist";
            $columns = "*";
            $conditionColumns = array('id','status');
            $conditionVals = array($institude_id,1);
            $dbAgent = DBAgent::getInstance();
            return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals,$hasPrefix=true);
    }

    /*获取所有机构*/
    public function getAllInstitude(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__institude where status = ?";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取所属机构其他实验室*/
    function getInstitudeOtherLab($institudeId,$labId){
        $arr = array();
        $arr[] = $institudeId;
        $arr[] = $labId;
        $sql = "select * from #__lab where institude_id = ? and lab_id <> ? and status = 1 and is_check = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*根据labId获取该实验室的专家*/
    function getExpert($labId){
        $arr = array();
        $arr[] = $labId;
        $sql = "select a.* from #__member a join #__lab_expert b on a.mid=b.mid where b.lab_id= ? and a.status=1 and b.status=1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*根据专家id获取专家信息*/
    function getExpertInfoById($id){
        $arr = array();
        $arr[] = $id;
        $sql = "select * from #__member where mid = ? and status=1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    /*根据labId获取实验室的服务范围*/
    function  getServiceRangeByLabId($labId){
        $arr = array();
        $arr[] = $labId;
        $sql = "select * from #__lab_service where lab_id = ? and service_type = 1 and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*根据labId获取实验室的仪器资源*/
    function  getInstrumentByLabId($labId){
        $arr = array();
        $arr[] = $labId;
        $sql = "select * from #__lab_service where lab_id = ? and service_type = 2 and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*科研数据 了解更多*/
    function getMoreDetailById($rid){
        $arr = array();
        $arr[] = $rid;
        $sql = "select * from #__lab_datum where id = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    /*获取发布者姓名*/
    public function getMemberBymid($mid){
        $arr = array();
        $arr[] = $mid;
        $sql = "select m.name  from #__member m where mid = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    /*获取分类*/
    public function getTypeNameBytid($tid){
        $arr = array();
        $arr[] = $tid;
        $sql = "select * from #__lab_type t where lab_tid = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    /*更新实验室信息*/
    function updateLabInfo($labid,$newlab){
        $table = "lab";
        if(isset($newlab['name'])){
            $updateColumns[] = 'name';
            $updateVals[] = $newlab['name'];
        }
        if(isset($newlab['manager'])){
            $updateColumns[] = 'manager';
            $updateVals[] = $newlab['manager'];
        }
        if(isset($newlab['phone'])){
            $updateColumns[] = 'manager_phone';
            $updateVals[] = $newlab['phone'];
        }
        if(isset($newlab['zyx'])){
            $updateColumns[] = 'speciality_credit';
            $updateVals[] = $newlab['zyx'];
        }
        if(isset($newlab['kyhj'])){
            $updateColumns[] = 'environment_credit';
            $updateVals[] = $newlab['kyhj'];
        }
        if(isset($newlab['jlx'])){
            $updateColumns[] = 'discipline_credit';
            $updateVals[] = $newlab['jlx'];
        }
        if(isset($newlab['kyry'])){
            $updateColumns[] = 'research_staff_credit';
            $updateVals[] = $newlab['kyry'];
        }
        if(isset($newlab['view_num'])){
            $updateColumns[] = 'view_num';
            $updateVals[] = $newlab['view_num'];
        }
        if(isset($newlab['service_area'])){
            $updateColumns[] = 'service_area';
            $updateVals[] = $newlab['service_area'];
        }
        if(isset($newlab['type'])){
            $updateColumns[] = 'type_id';
            $updateVals[] = $newlab['type'];
        }
        if(isset($newlab['org'])){
            $updateColumns[] = 'institude_id';
            $updateVals[] = $newlab['org'];
        }
        if(isset($newlab['address'])){
            $updateColumns[] = 'address';
            $updateVals[] = $newlab['address'];
        }
        if(isset($newlab['rules'])){
            $updateColumns[] = 'rules';
            $updateVals[] = $newlab['rules'];
        }
        if(isset($newlab['intro'])){
            $updateColumns[] = 'intro';
            $updateVals[] = $newlab['intro'];
        }
        if(isset($newlab['lat'])){
            $updateColumns[] = 'lat';
            $updateVals[] = $newlab['lat'];
        }
        if(isset($newlab['lon'])){
            $updateColumns[] = 'lon';
            $updateVals[] = $newlab['lon'];
        }
        if(isset($newlab['stars'])){
            $updateColumns[] = 'stars';
            $updateVals[] = $newlab['stars'];
        }
        if(isset($newlab['logo'])){
            $updateColumns[] = 'logo';
            $updateVals[] = $newlab['logo'];
        }

        $conditionColumns = array('lab_id','status');
        $conditionVals = array($labid,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

}