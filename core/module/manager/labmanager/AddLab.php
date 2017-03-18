<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddLab extends BaseAction{

	public function action(){
        $user = UserAgent::getUser();

        FileUtil::requireService('LabDetailServ');
        $sererv = new LabDetailServ();
        $org = array();
        $org = $sererv->getAllInstitude();
        if($org == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        $params['substyle'] = 'labListing';
        $params['org'] = $org;
		return $params;
	}

}