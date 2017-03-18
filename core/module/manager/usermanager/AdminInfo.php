<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AdminInfo extends BaseAction{

	public function action(){
		$params = array();
		$key = isset($_GET['key'])?trim($_GET['key']):"";
		
		$page = isset($_GET['page'])?trim($_GET['page']):1;
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
		
		$pageUtil = "";
        $count = 0;			
        $pagesize = 10;
		$baseUrl = "../usermanager/adminInfo.html?";
		
		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
		$adminArray = array();
		
		if($key != ""){
			$count = $service->searchAdminCountByKey($key);
	        if($count === false){
				FileUtil::load404Html();
				exit(0);
			}
			$pageUtil = new PageUtil($pagesize,$count,$page); 
			$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
			$adminArray = $service->searchAdminByKey($key,$index,$pagesize);
		} else {
			$count = $service->getAllAdminCount();
	        if($count === false){
				FileUtil::load404Html();
				exit(0);
			}
			$pageUtil = new PageUtil($pagesize,$count,$page); 
			$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
			$adminArray = $service->getAllAdmin($index,$pagesize);
		}
		
		$roleArray = $service->getAllRore();
        if($roleArray === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$params['style'] = 'usermanager';
		$params['substyle'] = 'adminInfo';
		$params['admin'] = $adminArray;
		$params['role'] = $roleArray;
		$params['baseurl'] = $baseUrl;
		$params['pager'] = $pageUtil;
		$params['count'] = $count;
		 
		return $params;
	}
}