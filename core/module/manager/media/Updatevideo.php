<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Updatevideo extends BaseAction{
    public function action(){
        $id=isset($_GET['id'])?$_GET['id']:"";
        FileUtil::requireService("ArticleServ");
        $Article=new ArticleServ();
        FileUtil::requireService("VideoServ");
        $Video=new VideoServ();
        $params['video']=$Video->GetonevideoByid($id);
        $params['category']=$Article->getArticleByView(3,'id desc',$type=4);
		$params['substyle'] = 'video';
        return $params;
    }
}