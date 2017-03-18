<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddfriendServ extends BaseAction{
    public function action(){
        $mid=isset($_POST['mid'])?$_POST['mid']:"";
        $mid2=isset($_POST['mid2'])?$_POST['mid2']:"";
        $dbAgent=DBAgent::getInstance();
        $table="friends";
        $insertColumns=array('mid','mid2','status');
        $insertVals=array($mid,$mid2,1);
        if($dbAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true)){
            $result['ret']='1';
            $result['msg']='关注成功';
            exit(json_encode($result));
        }else{
            $result['ret']='0';
            $result['msg']='关注失败';
            exit(json_encode($result));
        }

    }
}