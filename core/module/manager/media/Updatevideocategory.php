<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Updatevideocategory extends BaseAction
{

    public function action()
    {
        $id=isset($_GET['id'])?$_GET['id']:"";
        FileUtil::requireService("ArticleServ");
        $Article=new ArticleServ();
        $params['category']=$Article->getArticleById(4,$id);
        if($params['category']['type']!=4){
            echo 'error';
            exit();
        }
        $params['style'] = 'media';
        return $params;
    }
}