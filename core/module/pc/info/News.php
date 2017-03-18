<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class News extends BaseAction{
    public function action(){
        $id=isset($_GET['id'])?$_GET['id']:0;
        $page=isset($_GET['page'])?$_GET['page']:1;
        FileUtil::requireService("NewsServ");
        FileUtil::requireService("AdvertServ");
        $Advert=new AdvertServ();
        $news = new NewsServ();
        $news->Addviewnum($id);
        $comment = $news->getCommentById($id,15,$page);
        $userList = array();
        for($i = 0;$i<count($comment);$i++){
            $userList[] = $news->getUserById($comment[$i]['mid']);
        }
        $params['user'] = UserAgent::getUser();
        $params['news']=$news->GetNewsById($id);
        if($params['news'] == null || $params['news'] == false){
            FileUtil::load404Html();
            exit(0);
        }
        $params['comment'] = $comment;
        $params['userList'] = $userList;
        $params['count']=$news->GetNewsnumByid($id);
        $params['othernews']=$news->GetNewsBytime(7,$page=1,$order='time desc',$id);
        $params['littleadvert']=$Advert->getAdvertbyrand(3,1);
        FileUtil::requireService('PageUtil');
        $pageUtil = new PageUtil(15,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/info/news.html?id='.$id;
        $params['pager'] = $pageUtil;
        return $params;
    }
}
