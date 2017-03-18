<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class BinDingsCompany extends BaseAction{

    public function action(){
        FileUtil::requireService("LabDetailServ");
        $serv = new LabDetailServ;
        $org = $serv->getAllInstitude();


        $params = array();
        $params['org'] = $org;
        return $params;
    }

}