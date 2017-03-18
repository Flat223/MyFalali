<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Binding extends BaseAction{

	public function action(){
		$user = UserAgent::getUser();
		
		$key = isset($_GET['key'])?trim($_GET['key']):"";
		$page = isset($_GET['page'])?trim($_GET['page']):1;
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
		
		$pagesize = 5;
		$baseUrl = "../user/binding.html?";
		
		if($user['sub_type'] == 0 || $user['type'] == 4){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil::requireService("CompanyServ");
		$service = new CompanyServ();
		
		if ($user['bind_status'] == 0) {
			if(!empty($key)){
				$count = $service->searchCompanyCount($key,$user['type']);
		        if($count === false){
					FileUtil::load404Html();
					exit(0);
				}
			
				$pageUtil = new PageUtil($pagesize,$count,$page); 
				$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
				$companyArray = $service->searchCompanyList($key,$user['type'],$index,$pagesize);
				if($companyArray === false){
					FileUtil::load404Html();
					exit(0);				
				}
				
				$params['baseurl'] = $baseUrl;
				$params['pager'] = $pageUtil;
				$params['count'] = $count; 
				$params['search_company'] = $companyArray;
			}
		} else {
			$company = $service->getUserCompanyInfo($user['bind_company']);	
			$params['company'] = $company;
		}
		
		$params['style'] = 'user';
		$params['substyle'] = 'binding';
		return $params;
	}
}