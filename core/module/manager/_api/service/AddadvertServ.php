<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddadvertServ extends BaseAction{
    public function action(){
//        exit(json_encode($_POST));
        $title=isset($_POST['title'])?$_POST['title']:"";
        $type=isset($_POST['type'])?$_POST['type']:"";
        $url=isset($_POST['url'])?$_POST['url']:"";
        $images=isset($_POST['image'])?$_POST['image']:"";
        $time=time();
        $dbAgent = DBAgent::getInstance();
        $table='textad';
        $insertColumns=array('url','description','status','image','type');
        $insertVals=array($url,$title,1,$images,$type);
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