<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Index extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
        FileUtil::requireService("ManagerServ");
        $serv = new ManagerServ();
		$admin2 = $service->getAdminByAid($admin['aid']);
		if($admin2 === false){
			FileUtil::load404Html();
			exit(0);
		}
		$_SESSION['admin'] = $admin2;
        $fund = $serv->getTodayFundCount();
        $rel = $serv->getTodayReleaseCount();
        $lab = $serv->getTodayLabCount();
        $order = $serv->getTodayOrderCount();
        $med = $serv->getTodayMediaCount();
        $vip = $serv->getTodayVipCount();

		$params = array();
		$params['style'] = 'home';
        $params['fund'] = $fund;
        $params['rel'] = $rel;
        $params['lab'] = $lab;
        $params['order'] = $order;
        $params['med'] = $med;
        $params['vip'] = $vip;
		return $params;
	}
}