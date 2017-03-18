<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class NotInterestServ extends BaseAction
{

    public function action()
    {
        $circle_id=isset($_POST['circle_id'])?$_POST['circle_id']:"";
        $userid=isset($_POST['userid'])?$_POST['userid']:"";
        $time=time();
        $DBAgent = DBAgent::getInstance();
        $table="notinterest_member";
        $insertColumns=array('circle_id','mid','time','status');
        $insertVals=array($circle_id,$userid,$time,1);
        if($DBAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true)){
            $ret['ret']='1';
            $ret['msg']='成功';
            exit(json_encode($ret));
        }else{
            $ret['ret']='0';
            $ret['msg']='失败'.mysql_error();
            exit(json_encode($ret));
        }
    }
}
?>