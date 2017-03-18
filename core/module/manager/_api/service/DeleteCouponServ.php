<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteCouponServ extends BaseAction{
    public function action(){
        $id=isset($_POST['id'])?$_POST['id']:"";
        $dbAgent = DBAgent::getInstance();
        $table='coupon_list';
        $updateColumns=array('status');
        $updateVals=array('0');
        $conditionColumns=array('id','status');
        $conditionVals=array($id,1);
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