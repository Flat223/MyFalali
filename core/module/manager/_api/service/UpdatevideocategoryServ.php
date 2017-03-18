<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdatevideocategoryServ extends BaseAction
{
    public function action(){
        $title=isset($_POST['title'])?$_POST['title']:"";
        $intro=isset($_POST['intro'])?$_POST['intro']:"";
        $images=isset($_POST['images'])?$_POST['images']:"";
        $id=isset($_POST['id'])?$_POST['id']:"";
        $dbAgent = DBAgent::getInstance();
        $table='article';
        $conditionColumns=array('id');
        $conditionVals=array($id);
        $updateColumns=array('title','intro','images');
        $updateVals=array($title,$intro,$images);
        $result=array();
        if($dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals,$hasPrefix=true)){
            $result['ret']='1';
            $result['msg']='保存成功';
            exit(json_encode($result));
        }else{
            $result['ret']=0;
            $result['msg']='保存失败';
            exit(json_encode($result));
        }
    }

}