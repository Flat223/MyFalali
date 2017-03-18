<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddfriendServ extends BaseAction{
    public function action(){
//        exit(json_encode($_POST));
        $name=isset($_POST['title'])?$_POST['title']:"";
        $link=isset($_POST['url'])?$_POST['url']:"";
        $logo=isset($_POST['image'])?$_POST['image']:"";
        $time=time();
        $dbAgent = DBAgent::getInstance();
        $table='friendly_link';
        $insertColumns=array('name','logo','link','createtime','status');
        $insertVals=array($name,$logo,$link,$time,1);
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