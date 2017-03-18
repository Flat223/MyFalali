<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditAdvert extends BaseAction{

	public function action(){
        $id = isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
        FileUtil::requireService("NewsServ");
        $serv = new NewsServ();
        $advert = $serv->getAdvertById($id);
        $params = array();
        $params['advert'] = $advert;
        $params['substyle'] = 'advert';
		return $params;
	}

}