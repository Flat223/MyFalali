<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabSearchServ extends BaseAction{
	
	public function action()
    {
        $data = isset($_REQUEST['resinfo']) ? trim($_REQUEST['resinfo']) : "";

        FileUtil::requireService("LabListServ");
        $service = new LabListServ();
        $lab = $service->getLabByName($data);



        $params = array();
        $params['lab'] = $lab;
        return $params;
    }
}