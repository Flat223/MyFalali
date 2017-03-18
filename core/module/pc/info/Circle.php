<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Circle extends BaseAction
{

    public function action()
    {
        $id=isset($_GET['id'])?$_GET['id']:"";
        $page=isset($_GET['page'])?$_GET['page']:1;
        FileUtil::requireService("ArticleServ");
        FileUtil::requireService('PageUtil');
        FileUtil::requireService('NewsServ');
        FileUtil::requireService('CircleServ');
        $Article=new ArticleServ();
        $circle=new CircleServ();

        $params['article']=$Article->GetAriticleByCategoryid(0,$id,$type=4,$num=10,$order='time desc');
        if($params['article']== false){
            FileUtil::load404Html();
            exit(0);
        }
        $params['hot']=$Article->getArticleByView(5,'view_num desc',$type=4);
        $params['circle']=$circle->getCircleByid($id);
        $params['count']=$Article->getCountbycategoryid(4,$id);
        $pageUtil = new PageUtil(10,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/info/morenews.html?';
        $params['pager'] = $pageUtil;
        return $params;
    }
}