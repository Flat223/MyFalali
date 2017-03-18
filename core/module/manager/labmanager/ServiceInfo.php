<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ServiceInfo extends BaseAction{

	public function action(){
        $id = isset($_GET['id'])?trim($_GET['id']):'';
        $type = isset($_GET['type'])?trim($_GET['type']):'';
        FileUtil::requireService('LabServiceServ');
        $infoServ = new LabServiceServ();
        $info = $infoServ->getServiceById($id);
        $sl = $infoServ->getLabById($info['lab_id']);
		
        if($type == 1){
	    	$params['substyle'] = 'labServiceRange';    
        } else if($type == 2){
	        $params['substyle'] = 'labInstrument';
        }
        $params['info'] = $info;
        $params['sl'] = $sl;
        $params['type'] = $type;
		return $params;
	}

}