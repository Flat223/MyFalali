<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RecruitInfo extends BaseAction{

	public function action(){
        FileUtil::requireService('RecruitServ');
        $serv = new RecruitServ();
        $data = $serv->getRecruitData();

        $params = array();
		$params['substyle'] = 'recruitInfo';
        $params['data'] = $data;
		return $params;
	}

}