<?php
class RecruitServ{
    public function __construct(){

    }

    public function getRecruitData(){
        $sql = "select * from #__recruit where status = 1";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }

    public function getRecruitDataById($id){
        $sql = "select * from #__recruit where id = $id and status = 1";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,array());
    }

    public function updateData($data){
        $table = "recruit";
        $dbAgent = DBAgent::getInstance();
        if($data['id'] != 0){
            $updateColumns = array('quarters','salary','type','place','content','`require`');
            $updateVals = array($data['quarters'],$data['salary'],$data['type'],$data['place'],$data['content'],$data['require']);
            $conditionColumns = array('id','status');
            $conditionVals = array($data['id'],1);
            return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
        }else{
            $insertColumns = array('quarters','salary','type','place','content','`require`','time','status');
            $insertVals = array($data['quarters'],$data['salary'],$data['type'],$data['place'],$data['content'],$data['require'],time(),1);
            return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
        }
    }
}
?>