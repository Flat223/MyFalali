<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdatenewsServ extends BaseAction
{
    public function action(){
        $title=isset($_REQUEST['title'])?$_REQUEST['title']:"";
        $content=isset($_REQUEST['content'])?$_REQUEST['content']:"";
        $id=isset($_REQUEST['id'])?$_REQUEST['id']:"";
        $url=isset($_REQUEST['href'])?$_REQUEST['href']:"";
        $dbAgent = DBAgent::getInstance();
        $table='news';
        $conditionColumns=array('id');
        $conditionVals=array($id);
        $updateColumns=array('title','content','url');
        $updateVals=array($title,$content,$url);
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