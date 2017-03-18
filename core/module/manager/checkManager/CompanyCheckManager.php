<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CompanyCheckManager extends BaseAction{

	public function action(){
		$params = array();
		
		$page = isset($_GET['page'])?trim($_GET['page']):1;
		$company_type=isset($_REQUEST['company_type'])?trim($_REQUEST['company_type']):"0";
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
	
        $pagesize = 10;
		$baseUrl = "../checkManager/companyCheckManager.html?";
		if(!empty($company_type)){
	        $baseUrl .= "company_type=".$company_type;
        }
		
		FileUtil::requireService("CompanyServ");
		$service = new CompanyServ();
		$userArray = array();
		
		$count = $service->getAllRegistComCount($company_type);
        if($count === false){
			FileUtil::load404Html();
			exit(0);
		}
		$pageUtil = new PageUtil($pagesize,$count,$page); 
		$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
		$companyArray = $service->getAllCheckedCompany($company_type,$index,$pagesize);
		if($companyArray === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$params['style'] = 'checkManager';
		$params['substyle'] = 'companyCheckManager';
		$params['company'] = $companyArray;
		$params['baseurl'] = $baseUrl;
		$params['pager'] = $pageUtil;
		$params['count'] = $count;
		return $params;
	}
}