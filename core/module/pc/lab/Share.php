<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Share extends BaseAction{
	
	public function action(){
		FileUtil::requireService('ShareServ');
        /*获取实验室一级和二级分类*/
        $shareServ = new ShareServ();
        $children = array();
        $shareTypes = $shareServ->getLabTypes();
        if($shareTypes === false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        for ($i=0;$i<count($shareTypes);$i++){
            $children [] = $shareServ->getChildLabTypes($shareTypes[$i]['lab_tid']);
        }
        /*获取实验室共享新闻*/
        $shareNews = $shareServ->getNews();
        if($shareNews === false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        /*获取4个合作实验室*/
        $shareLab = $shareServ->getLabs();
        if($shareLab === false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        /*获取3个热门实验室*/
        $hotLab = $shareServ->getHotLab();
        if($hotLab === false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }

        $localLab = $shareServ->getLabByAddress("苏州");
        if($localLab === false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }

        $user = UserAgent::getUser();
        $cl = array();
        if(!empty($user)){
            $colab = $shareServ->getCollectedLab($user['mid']);
            for($i = 0;$i<count($colab);$i++){
              $cl[] = $colab[$i]['aid'];
            }
        }
        FileUtil::requireService("AdvertServ");
        $Advert=new AdvertServ();

        /*页面调用*/
        $params = array();
        $params['style'] = 'share';
        $params['advertbanner']=$Advert->getAdvert(5,3);
        $params['labTypes'] = $shareTypes;
        $params['labChildTypes'] = $children;
        $params['news'] = $shareNews;
        $params['labs'] = $shareLab;
        $params['cl'] = $cl;
        $params['localLab'] = $localLab;
        $params['hotLab'] = $hotLab;
      /*  echo (json_encode($params['localLab']));
        exit(0);*/
        return $params;
	}

	
}