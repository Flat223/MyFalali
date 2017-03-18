<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddactivityServ extends BaseAction{
    public function action(){
        $title=isset($_POST['title'])?$_POST['title']:"";
        $intro=isset($_POST['intro'])?$_POST['intro']:"";
        $content=isset($_POST['content'])?$_POST['content']:"";
        $images=isset($_POST['images'])?$_POST['images']:"";
        $time=time();
        $dbAgent = DBAgent::getInstance();
        $table='article';
        $insertColumns=array('type','title','intro','content','time','status','images');
        $insertVals=array('5',$title,$intro,$content,$time,1,$images);
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