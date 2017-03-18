<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Updatecooperate extends BaseAction{

    public function action(){
        $id=isset($_GET['id'])?$_GET['id']:"";
        FileUtil::requireService("ArticleServ");
        $Article=new ArticleServ();


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
        if($params['article']['type']!='2' && $params['article']['type']!='5'){
            FileUtil::load404Html();
            exit(0);

        }
        return $params;
    }





}