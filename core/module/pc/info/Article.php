<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Article extends BaseAction{

    public function action(){
        $id=isset($_GET['id'])?$_GET['id']:"";
        $page=isset($_GET['page'])?$_GET['page']:1;
        FileUtil::requireService("ArticleServ");
        FileUtil::requireService("UserServ");
        $Article=new ArticleServ();
        $params['user'] = UserAgent::getUser();
        $Article->Addviewnum($id);

        $comment = $Article->getCommentById($id,15,1);
        $userList = array();
        for($i = 0;$i<count($comment);$i++){
            $userList[$i] = $Article->getUserById($comment[$i]['mid']);
        }

        $params['article']=$Article->GetAriticle_Writer($id);
        if($params['article'] == null || $params['article'] == false){
            FileUtil::load404Html();
            exit(0);

        }
        $params['next']=$Article->GetAriticle_Writer($id+1);

        $params['category']=$Article->getThisCategroy($params['article']['categoryId']);
        $params['warticle']=$Article->getArticleByMid(1,$params['article']['mid']);
        $params['wcount']=$Article->getUserArticleCount($params['article']['mid']);
        $params['aboutA']=$Article->GetAriticleByCategoryid($params['article']['id'],$params['article']['categoryId'],$type=1,$num=6);
        $params['comment'] = $comment;
        $params['userList'] = $userList;
        $params['count']=$Article->GetCommentnumByid($id);
        FileUtil::requireService('PageUtil');
        $pageUtil = new PageUtil(15,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/info/article.html?id='.$id;
        $params['pager'] = $pageUtil;
        return $params;
    }





}