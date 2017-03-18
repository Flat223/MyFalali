<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Advert extends BaseAction{

	public function action(){
        $page = isset($_REQUEST['page'])?trim($_REQUEST['page']):1;
        $type=isset($_REQUEST['type'])?$_REQUEST['type']:'';
        FileUtil::requireService("AdvertServ");
        $Advert=new AdvertServ();
        $params['advert']=$Advert->getAdvertbytype(10,$page,$type);
        $params['count']=$Advert->getAdvertCount($type);
        $params['substyle'] = 'advert';
        return $params;

	}

}