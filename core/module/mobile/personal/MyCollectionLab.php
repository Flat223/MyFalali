<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class MyCollectionLab extends BaseAction{

    public function action(){
        $user = UserAgent::getUser();
        FileUtil::requireService("UserServ");
        $service=new UserServ();
        $lab=$service->getUserCollection(2,$user['mid']);

        $params = array();
        $params['lab'] = $lab;
        return $params;
    }

}