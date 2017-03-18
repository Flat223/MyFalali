<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ArticlecommentServ extends BaseAction{
    public function action(){
        $aid=isset($_POST['aid'])?$_POST['aid']:"";
        $mid=isset($_POST['mid'])?$_POST['mid']:"";
        $comment=isset($_POST['comment'])?$_POST['comment']:"";
        $DBAgent=DBAgent::getInstance();
        if(empty($mid)){
            $result['ret']='0';
            $result['msg']="请您先登录";
            exit(json_encode($result));
        }
        $insertColumns=array('mid','pid','content','time','status');
        $insertVals=array($mid,$aid,$comment,time(),1);
        if($DBAgent->insertRecord("article_comment",$insertColumns,$insertVals,$hasPrefix=true)){
            $result['ret']='1';
            $result['msg']="已发布评论";
            exit(json_encode($result));
        }else{
            $result['ret']='0';
            $result['msg']="评论失败".mysql_error();
            exit(json_encode($result));
        }

    }
}