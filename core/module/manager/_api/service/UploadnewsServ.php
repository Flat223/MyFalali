<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UploadnewsServ extends BaseAction
{
    public function action()
    {
        $title=isset($_REQUEST['title'])?$_REQUEST['title']:"";
        $url=isset($_REQUEST['href'])?$_REQUEST['href']:"";
        $content=isset($_REQUEST['content'])?$_REQUEST['content']:"";
        /*$images=isset($_POST['images'])?$_POST['images']:"";*/
        $dbAgent = DBAgent::getInstance();
        $table='news';
        $time=time();
        $insertColumns=array('title','content','time','status','url');
        $insertVals=array($title,$content,$time,1,$url);
        if($dbAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true)){
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