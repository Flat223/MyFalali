<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Topic extends BaseAction
{

    public function action()
    {
        $id=isset($_GET['id'])?$_GET['id']:"";
        $page=isset($_GET['page'])?$_GET['page']:1;
        FileUtil::requireService("TopicServ");
        FileUtil::requireService("UserServ");
        FileUtil::requireService("InterestServ");
        $interest=new InterestServ();
        $params['user'] = UserAgent::getUser();
        $topic=new TopicServ();
        $params['topic']=$topic->GetTopicById($id);
        if($params['topic'] == null || $params['topic'] == false){
            FileUtil::load404Html();
            exit(0);
        }
        $params['more']=$topic->GetTopicByMid($params['topic']['mid'],5);
        $params['count']=$topic->GetNumBymid($params['topic']['mid']);
        $params['replay']=$topic->Getreplay($params['topic']['topic_id'],$page);
        $params['count']=$topic->Getreplaynum($params['topic']['topic_id']);
        FileUtil::requireService('PageUtil');
        $pageUtil = new PageUtil(10,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/info/topic.html?id='.$id;
        $params['pager'] = $pageUtil;
        $interest->Addnum($params['topic']['circle_id']);

        return $params;
    }
}