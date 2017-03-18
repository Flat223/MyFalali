<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabShareServ extends BaseAction{
	public function action(){
        $address = isset($_REQUEST['address'])?trim($_REQUEST['address']):0;
        FileUtil::requireService('ShareServ');
        $service = new ShareServ();
       $lab = $service->getLabInfoByAddress("苏州");
        echo json_encode($lab);
        exit(0);
        return $lab;
		
	}
}
