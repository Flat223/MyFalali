<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class FocusServ extends BaseAction{
    public function action(){
        $circle_id=isset($_POST['circle_id'])?$_POST['circle_id']:"";
        $user_id=isset($_POST['userid'])?$_POST['userid']:"";
        $time=time();
        $table="circle_member";
        $insertColumns=array('circle_id','mid','time','status');
        $insertVals=array($circle_id,$user_id,$time,'1');
        $DBAgent = DBAgent::getInstance();
        $sql="select * from labring_circle_member where mid=$user_id and circle_id=$circle_id";
        $check=$DBAgent->querySingleRecord($sql,array());
        if($check!=null && $check['status']=='1'){
            $result['ret']='0';
            $result['msg']='您已经关注过';
            exit(json_encode($result));
        }
        if($check!=null && $check['status']=='0'){
            if($DBAgent->updateRecords($table,array('status'),array('1'),array('circle_id','mid'),array($circle_id,$user_id),$hasPrefix=true)){
                $result['ret']='1';
                $result['msg']='关注成功';
                exit(json_encode($result));
            }
        }
        if($DBAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true)){
            $result['ret']='1';
            $result['msg']='关注成功';
            exit(json_encode($result));

        }else{
            $result['ret']='0';
            $result['msg']='关注失败,请稍后再试';
            exit(json_encode($result));

        }
    }
}
?>