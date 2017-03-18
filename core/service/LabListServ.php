<?php
class LabListServ{
	
	//构造函数->默认载入函数
    public function __construct(){
	    
    }
    
    /*根据id获取实验室类型*/
    function getLabType($labtId){
        $arr = array();
        $arr [] = $labtId;
        $sql = "select * from #__lab_type where lab_tid = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    /*根据id获取类型*/
    function getChildType($labtId){
        $arr = array();
        $arr [] = $labtId;
        $sql = "select * from #__lab_type where parentid = ? AND status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取一级分类*/
    function getParentType(){
        $arr = array();
        $arr [] = 1;
        $arr [] = 1;
        $sql = "select * from #__lab_type where level = ? AND status = ?";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*根据子类型的id获取实验室总数*/
    function getLabCountById($typeId,$obtype){
        $arr = array();
        $arr[] = (int)$typeId;
        $arr[] = (int)$typeId;
        $sql = "SELECT lab.* FROM #__lab lab JOIN (select GROUP_CONCAT(labtype.lab_tid) as tids from #__lab_type labtype where labtype.parentid = ? OR labtype.lab_tid = ?) labids ON FIND_IN_SET(lab.type_id,labids.tids)where lab.status = 1 and lab.is_check = 1";
        if($obtype == 1 || $obtype == null || $obtype == 0){
            $sql .= " ORDER BY lab.stars DESC";
        }else if($obtype == 2){
            $sql .= " ORDER BY lab.view_num DESC";
        }
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,$arr);
    }

    /*分页获取实验室*/
    function getLabById($typeId,$obtype,$index,$pagesize){
        $arr = array();
        $arr[] = $typeId;
        $arr[] = $typeId;
        $sql = "SELECT lab.* FROM #__lab lab JOIN (select GROUP_CONCAT(labtype.lab_tid) as tids from #__lab_type labtype where labtype.parentid = ? OR labtype.lab_tid = ?) labids ON FIND_IN_SET(lab.type_id,labids.tids)where lab.status = 1 and lab.is_check = 1 ";
        if($obtype == 1 || $obtype == null || $obtype == 0){
            $sql .= " ORDER BY lab.stars DESC";
        }else if($obtype == 2){
            $sql .= " ORDER BY lab.view_num DESC";
        }
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,$arr);
    }

    /*输入关键字搜索*/
    public function getLabByinfo($info,$page,$pagesize){
        $dbAgent = DBAgent::getInstance();
        $index=(intval($page)-1)*4;
        $sql="select * from labring_lab where status = 1 and is_check = 1 and name like \"%$info%\" order by lab_id desc limit $index,$pagesize";
        $sqlall="select count(*) as a from labring_lab where status = 1 and is_check = 1 and name like \"%$info%\" ";
        $result['data']=$dbAgent->queryRecords($sql,array());
        $count=$dbAgent->querySingleRecord($sqlall,$params=array());
        $result['count']=$count['a'];
        return $result;
    }
    //根据条件数目来获取实验室
    public function getLabBy($order,$num){
        $dbAgent = DBAgent::getInstance();
        $sql="select * from labring_lab where status=1 and is_check = 1 order by $order limit $num";
        return $result=$dbAgent->queryRecords($sql,array());
    }

    /*根据id删除实验室*/
    public function deleteLabById($id){
        $table = "lab";
        $updateColumns = array('status');
        $updateVals = array(0);
        $conditionColumns = array('lab_id','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    /*根据id删除发布信息*/
    public function deleteReleaseById($id){
        $table = "release";
        $updateColumns = array('status');
        $updateVals = array(0);
        $conditionColumns = array('id','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    /*根据id审核实验室*/
    public function checkLabById($id){
        $table = "lab";
        $updateColumns = array('is_check');
        $updateVals = array(1);
        $conditionColumns = array('lab_id','status','is_check');
        $conditionVals = array($id,1,0);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    /*根据id审核发布需求*/
    public function checkReleaseById($id){
        $table = "release";
        $updateColumns = array('is_check');
        $updateVals = array(1);
        $conditionColumns = array('id','status','is_check');
        $conditionVals = array($id,1,0);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    /*添加实验室*/
    public function addLab($newlab){
        $table = "lab";
        $insertColumns = array('name','manager','manager_phone','speciality_credit','environment_credit','discipline_credit','research_staff_credit','view_num','service_area','type_id','institude_id','address','rules','intro','lat','lon','stars','time','mid','logo','status','is_check');
        $insertVals = array($newlab['name'],$newlab['manager'],$newlab['phone'],$newlab['zyx'],$newlab['kyhj'], $newlab['jlx'],$newlab['kyry'],$newlab['view_num'],$newlab['service_area'],$newlab['type'],
                    $newlab['org'],$newlab['address'],$newlab['rules'],$newlab['intro'],$newlab['lat'], $newlab['lon'],$newlab['stars'],$newlab['time'],$newlab['mid'],$newlab['logo'],1,0);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

}