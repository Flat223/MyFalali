<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetLabServ extends BaseAction{

    public function action(){
        $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
        FileUtil::requireService('LabServiceServ');
        $serv = new LabServiceServ();
        $labs = $serv->getLabByName($name);
        return $labs;
    }
}