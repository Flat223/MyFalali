<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CooperateLab extends BaseAction{

    public function action(){
        $labId = isset($_REQUEST['labId'])?trim($_REQUEST['labId']):0;

        FileUtil::requireService("ShareServ");
        $serv = new ShareServ();
        $lab = $serv->getInitNumLab(0,15);



        $params = array();
        $params['lab'] = $lab;
        $params['style'] = 'lab';
        return $params;
    }

}