<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetShopMemberServ extends BaseAction{

    public function action(){
        $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
        FileUtil::requireService('ShopServ');
        $serv = new ShopServ();
        $users = $serv->getShopMember($name);
        return $users;
    }
}