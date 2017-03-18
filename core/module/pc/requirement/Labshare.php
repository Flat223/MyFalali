<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Labshare extends BaseAction{
	
	public function action(){
        FileUtil::requireService('LabDetailServ');
        $infoServ = new LabDetailServ();

        $params = array();
        return $params;
	}
	
}