<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class MyLab extends BaseAction{

    public function action(){
        $user = UserAgent::getUser();
        FileUtil::requireService("UserServ");
        $service=new UserServ();
        $laboratory=$service->getLaboratoryByMid($user['mid']);

        $params = array();
        $params['style'] = 'user';
        $params['substyle'] = 'lab';
        $params['laboratory'] = $laboratory;
        return $params;
    }

}