<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Video extends BaseAction{

    public function action(){
        $id=isset($_GET['id'])?$_GET['id']:'';
        $page=isset($_GET['page'])?$_GET['page']:1;
        $info=isset($_GET['info'])?$_GET['info']:"";
        FileUtil::requireService("VideoServ");
        FileUtil::requireService("ArticleServ");
        $Article=new ArticleServ();
        $Video=new VideoServ();
        $num=10;
        $params['video']=$Video->GetvideoByid($id,$num,$page,$info);
        $params['category']=$Article->getArticleByView(3,'id desc',$type=4);
        $params['count']=$Video->GetVideoCount($info);
        $category=array();
        foreach ($params['category'] as $v){
           $category[$v['id']]=$v['title'];
        }
        $params['cate']=$category;
		$params['substyle'] = 'video';
        return $params;
    }

}