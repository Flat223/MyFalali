<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddlabelServ extends BaseAction{
    public function action(){
        $name=isset($_POST['name'])?$_POST['name']:"";
        $dbAgent = DBAgent::getInstance();
        $table='interest_label';
        $insertColumns=array('name','status');
        $insertVals=array($name,1);
        if($dbAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true)){
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

?>