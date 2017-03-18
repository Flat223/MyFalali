<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class MyCollectionMall extends BaseAction{

    public function action(){
        $user = UserAgent::getUser();
        FileUtil::requireService("UserServ");
        $service=new UserServ();
        $product=$service->getUserCollection(1,$user['mid']);

        $params = array();
        $params['product'] = $product;
        return $params;
    }

}