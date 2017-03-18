<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Index extends BaseAction{
	
	public function action(){
		$params = array();
		$params['style'] = 'home';
        FileUtil::requireService("NewsServ");
        FileUtil::requireService("ArticleServ");
        FileUtil::requireService("LabListServ");
        FileUtil::requireService("FriendlyServ");
        FileUtil::requireService("AdvertServ");
        $link=new FriendlyServ();
        $News=new NewsServ();
        $Article=new ArticleServ();
        $Lab=new LabListServ();
        $Advert=new AdvertServ();
        $params['indexadvert']=$Advert->getAdvert(2,4);
        $params['littleadvert']=$Advert->getAdvertbyrand(3,1);
        $params['longadvert']=$Advert->getAdvert(1,2);
        $params['hotlab']=$Lab->getLabBy('view_num desc',4);
        $params['hotarticle']=$Article->getArticleByView(4,'view_num desc');
        $params['category']=$Article->getArticleCategory(5);
        $params['newsflash']=$News->GetNewsBytime(10);
        $params['link']=$link->getFriendlyLink('createtime desc',6,1);
        $articleArray=$Article->GetAriticleBynum(15,1,0,1,1,null);
        if($articleArray === false){
	        FileUtil::load404Html();
			exit(0);
        }
        FileUtil::requireService('BannerServ');
        $serv = new BannerServ();
        $ban = $serv->getIndexCoopImg();
        $act = $serv->getIndexActivityImg();
        $params['img'] = $ban;
        $params['act'] = $act;
        $params['article'] = $articleArray;
        /*$params['topart']=array();*/
        $params['video']=$Article->getvideo(3,'time desc');
        $params['advert'] = $News->getAdvert();
/*
        echo(json_encode($articleArray));
        exit(0);
*/

        /*array_push($params['topart'],$articleArray[0]);
        array_push($params['topart'],$articleArray[1]);*/
        /*array_shift($params['article']);
        array_shift($params['article']);*/
        return $params;
	}
	
}