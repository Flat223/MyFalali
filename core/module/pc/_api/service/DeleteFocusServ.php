<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteFocusServ extends BaseAction{
    public function action(){
//        exit(json_encode($_POST));
        $circle_id=isset($_POST['circle_id'])?$_POST['circle_id']:"";
        $user_id=isset($_POST['userid'])?$_POST['userid']:"";
        $table="circle_member";
        $DBAgent = DBAgent::getInstance();
        if($DBAgent->updateRecords($table,array('status'),array('0'),array('circle_id','mid'),array($circle_id,$user_id),$hasPrefix=true)){
            $result['ret']='1';
            $result['msg']='取消成功';
            exit(json_encode($result));
        }else{
            $result['ret']='0';
            $result['msg']='关注失败,请稍后再试';
            exit(json_encode($result));
        }
    }
}
?>