<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Morearticle extends BaseAction{
    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        FileUtil::requireService("ArticleServ");
        FileUtil::requireService('PageUtil');
        $Article=new ArticleServ();
        $rets=$Article->GetAriticleBynum(10,$page,$categoryid='0',1,1,'');
        foreach($rets as $k=>$v){
            $rets[$k]['pic']=$this->GetStringImg($v['content']);
        }

        $params['article']=$rets;
        $params['count']=$Article->getAllArticleCount('1');
        $params['hotart']=$Article->getArticleByView(3,'view_num desc');
        $pageUtil = new PageUtil(10,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/info/morearticle.html?';
        $params['pager'] = $pageUtil;
        return $params;
    }
    function GetStringImg($string){
        $preg = "/<img.*?src=[\'\"](.+?)[\'\"].*?>/i";
        preg_match_all($preg, $string, $match);
        $imgurl = $match[1];
        return $imgurl;
    }
}
