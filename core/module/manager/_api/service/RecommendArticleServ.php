<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RecommendArticleServ extends BaseAction{
    public function action(){
//        exit(json_encode($_POST));
        $id = isset($_POST['id'])?$_POST['id']:"";
        $flag = isset($_POST['flag'])?$_POST['flag']:"";
        $dbAgent = DBAgent::getInstance();
        $time=time();
        $table="article";
        if($flag == 1){
            $updateColumns=array('recommend');
            $updateVals=array(0);
        }else{
            $updateColumns=array('recommend');
            $updateVals=array(1);
        }
        $conditionColumns=array('id','status');
        $conditionVals=array($id,1);
        if($dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals)){
            $ret['ret']=1;
            $ret['msg']='操作成功！';
        }else{
            $ret['ret']=0;
            $ret['msg']='抱歉,服务器错误,请稍后再试';
        }
        exit(json_encode($ret));
    }

}
