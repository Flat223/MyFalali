<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RecommendArticleServ extends BaseAction{
    public function action(){
        exit(json_encode($_POST));
        $id=isset($_POST['id'])?$_POST['id']:"";
        $dbAgent = DBAgent::getInstance();
        $time=time();
        $table="article";
        $updateColumns=array('city');
        $updateVals=array($time);
        $conditionColumns=array('pid');
        $conditionVals=array($id);
        if($dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals)){
            $ret['ret']=1;
            $ret['msg']='成功';
        }else{
            $ret['ret']=0;
            $ret['msg']='失败';
        }
        exit(json_encode($ret));
    }

}
