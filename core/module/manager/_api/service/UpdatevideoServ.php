<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdatevideoServ extends BaseAction{
    public function action(){
        $category=isset($_POST['category'])?$_POST['category']:"";
        $id=isset($_POST['id'])?$_POST['id']:"";
        $title=isset($_POST['title'])?$_POST['title']:"";
        $url=isset($_POST['url'])?$_POST['url']:"";
        $intro=isset($_POST['intro'])?$_POST['intro']:"";
        $content=isset($_POST['content'])?$_POST['content']:"";
        $images=isset($_POST['images'])?$_POST['images']:"";
        $dbAgent = DBAgent::getInstance();
        $table="article";
        $updateColumns=array('title','intro','content','images','categoryId','country');
        $updateVals=array($title,$intro,$content,$url,$category,$images);
        $conditionColumns=array('id');
        $conditionVals=array($id);
        if($dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals,$hasPrefix=true)){
            $result['ret']='1';
            $result['msg']='修改成功';
            exit(json_encode($result));
        }else{
            $result['ret']='0';
            $result['msg']='修改失败';
            exit(json_encode($result));
        }
    }
}