<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class video extends BaseAction{
    public function action(){
        $id=isset($_GET['id'])?$_GET['id']:0;
        FileUtil::requireService("ArticleServ");
        $Article = new ArticleServ();
        $video = $Article->getArticleById(3,$id);
        $Article->Addviewnum($id);

        if($video== false){
            FileUtil::load404Html();
            exit(0);
        }
        $comment = $Article->getCommentById($id,15,1);
        $userList = array();
        for($i = 0;$i<count($comment);$i++){
            $userList[] = $Article->getUserById($comment[$i]['mid']);
        }

        $params['user'] = UserAgent::getUser();
        $params['video']=$video;
        $params['comment'] = $comment;
        $params['userList'] = $userList;
//        $params['userList'] = $userList;
        return $params;
    }
}