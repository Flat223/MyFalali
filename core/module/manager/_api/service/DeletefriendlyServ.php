<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeletefriendlyServ extends BaseAction{
    public function action(){
        $lid=isset($_POST['lid'])?$_POST['lid']:"";
        $dbAgent = DBAgent::getInstance();
        $table='friendly_link';
        $updateColumns=array('status');
        $updateVals=array('0');
        $conditionColumns=array('lid');
        $conditionVals=array($lid);
        if($dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals,$hasPrefix=true)){
            $result['ret']='1';
            $result['msg']='成功';
            exit(json_encode($result));
        }else{
            $result['ret']='0';
            $result['msg']='失败';
            exit(json_encode($result));
        }
    }
}