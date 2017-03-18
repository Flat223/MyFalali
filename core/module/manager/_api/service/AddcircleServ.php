<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddcircleServ extends BaseAction{
    public function action(){
//        exit(json_encode($_POST));
        $title=isset($_POST['title'])?$_POST['title']:"";
        $intro=isset($_POST['intro'])?$_POST['intro']:"";
        $label=isset($_POST['s'])?$_POST['s']:"";
        $logo=isset($_POST['logo'])?$_POST['logo']:"";
        $time=time();
        $dbAgent = DBAgent::getInstance();
        $table='interest_circle';
        $insertColumns=array('name','logo','intro','interest_labels','time','status');
        $insertVals=array($title,$logo,$intro,$label,$time,1);
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