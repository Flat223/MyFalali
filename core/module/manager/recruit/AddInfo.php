<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddInfo extends BaseAction{

	public function action(){
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        FileUtil::requireService('RecruitServ');
        $serv = new RecruitServ();
        $data = $serv->getRecruitDataById($id);

        $params = array();
		$params['substyle'] = 'recruitInfo';
        $params['data'] = $data;
		return $params;
	}

}