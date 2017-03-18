<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabType extends BaseAction{

	public function action(){
        $id = isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
        FileUtil::requireService('LabListServ');
        $serv = new LabListServ();
        $type1 = $serv->getParentType();
        if($type1 == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        $type2 = $serv->getChildType(1);
        if($type2 == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }


        $params = array();
        $params['style'] = 'labmanager';
		$params['substyle'] = 'labType';
        $params['type1'] = $type1;
        $params['type2'] = $type2;
		return $params;
	}

}