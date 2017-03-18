<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class KnowMore extends BaseAction{

	public function action(){
        FileUtil::requireService('LabDetailServ');
        $labDetailServ = new LabDetailServ();
        $rid = isset($_REQUEST['rid'])?trim($_REQUEST['rid']):0;
        $tname = isset($_REQUEST['tname'])?trim($_REQUEST['tname']):"";
        $obj = $labDetailServ->getMoreDetailById($rid);
        if($obj == null || $obj == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        $params = array();
        $params['obj'] = $obj;
        $params['type'] = $tname;
		return $params;
	}

}