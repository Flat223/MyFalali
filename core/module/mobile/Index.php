<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Index extends BaseAction{
	
	public function action(){
        FileUtil::requireService("NewsServ");
        FileUtil::requireService("ArticleServ");
        FileUtil::requireService('AdvertServ');
        $News=new NewsServ();
        $Article=new ArticleServ();
        
        $articleArray=$Article->getMobileArticle(0,15);
        if($articleArray === false){
	        FileUtil::load404Html();
			exit(0);
        }
        $advert = new AdvertServ();
        $params['advert'] = $advert->getAdvert(6,4);
        $params['article'] = $articleArray;
        $params['topart']=$articleArray;
        $params['news']=$News->GetNewsBytime(20);
        $params['style'] = 'index';
        /*echo json_encode($params['news']);*/
        return $params;
	}
	
}