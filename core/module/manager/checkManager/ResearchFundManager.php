<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ResearchFundManager extends BaseAction{

	public function action(){
		$params = array();
		
		$page = isset($_GET['page'])?trim($_GET['page']):1;
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
		
		$pagesize = 10;
		$baseUrl = "../checkManager/researchFundManager.html?";
	      
		FileUtil::requireService("CollegeServ");
		$service=new CollegeServ();
		
		$count = $service->getAllApplyFundCount();
        if($count === false){
			FileUtil::load404Html();
			exit(0);
		}
		$pageUtil = new PageUtil($pagesize,$count,$page); 
		$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
		$fundRecord=$service->getAllApplyFundRecord($index,$pagesize);
		if($fundRecord === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
		$subTypeArray = $service->getCollegeSubType();
		if($subTypeArray === false){
			FileUtil::load404Html();
			exit(0);
		}	

		$params['style'] = 'checkManager';
		$params['substyle'] = 'researchFundManager';
		$params['sub_type'] = $subTypeArray;
		$params['record'] = $fundRecord;
		$params['baseurl'] = $baseUrl;
		$params['pager'] = $pageUtil;
		$params['count'] = $count;
		return $params;
	}
}