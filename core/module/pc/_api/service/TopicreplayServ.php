<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class TopicreplayServ extends BaseAction{
    public function action(){
//        exit(json_encode($_POST));
        $topic_id=isset($_POST['topic_id'])?$_POST['topic_id']:"";
        $circle=isset($_POST['circle'])?$_POST['circle']:"";
        $mid=isset($_POST['mid'])?$_POST['mid']:"";
        $comment=isset($_POST['comment'])?$_POST['comment']:"";
        $DBAgent=DBAgent::getInstance();
        if(empty($mid)){
            $result['ret']='0';
            $result['msg']="请您先登录";
            exit(json_encode($result));
        }
        $insertColumns=array('mid','topic_id','content','time','status');
        $insertVals=array($mid,$topic_id,$comment,time(),1);
        if($DBAgent->insertRecord("topic_replay",$insertColumns,$insertVals,$hasPrefix=true)){
            $result['ret']='1';
            $result['msg']="已发布评论";
            FileUtil::requireService("InterestServ");
            $interest=new InterestServ();
            $interest->Addtalknum($circle);
            exit(json_encode($result));
        }else{
            $result['ret']='0';
            $result['msg']="评论失败".mysql_error();
            exit(json_encode($result));
        }

    }
}