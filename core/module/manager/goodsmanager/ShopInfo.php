<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ShopInfo extends BaseAction{

    public function action(){
        FileUtil::requireService('ShopServ');
        $serv = new ShopServ();
        $type = $serv->getShopType();
        if($type == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }

        $params = array();
        $params['type'] = $type;
		$params['substyle'] = 'shopInfo';
        return $params;
    }

}