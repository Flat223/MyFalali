<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddtopicServ extends BaseAction{
    public function action(){
        $mid=isset($_POST['mid'])?$_POST['mid']:"";
        $title=isset($_POST['title'])?$_POST['title']:"";
        $content=isset($_POST['content'])?$_POST['content']:"";
        $circle=isset($_POST['circle_id'])?$_POST['circle_id']:"";
        $dbAgent=DBAgent::getInstance();
        if(empty($mid)){
            $result['ret']='0';
            $result['msg']='您还没有登录，请先登录';
            exit(json_encode($result));
        }
        FileUtil::requireService("TopicServ");
        $topic=new TopicServ();
        $check=$topic->Checkisfollow($circle,$mid);
        if(!$check){
            $result['ret']='0';
            $result['msg']='您还没关注这个圈子,请先关注';
            exit(json_encode($result));
        }
        $table='topic';
        $insertColumns=array('circle_id','title','content','time','mid','status');
        $insertVals=array($circle,$title,$content,time(),$mid,1);
        if($dbAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true)){
            $result['ret']=1;
            $result['msg']='发布成功';
            FileUtil::requireService("InterestServ");
            $interest=new InterestServ();
            $interest->Addtalknum($circle);
            exit(json_encode($result));
        }else{
            $result['ret']=0;
            $result['msg']='失败';
            exit(json_encode($result));
        }
    }
}
