<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CompanyMember extends BaseAction{

    public function action(){
        $user = UserAgent::getUser();
        
        $state = isset($_GET['state'])?trim($_GET['state']):1;
		$page=isset($_GET['page'])?$_GET['page']:1;
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
		
		$pagesize = 8;
		$baseUrl = "../user/companyMember.html?";
        
        FileUtil::requireService('CompanyServ');
        $service = new CompanyServ();
        
        $count = $service->getCompanyMemberCount($user['mid'],$state);
        if($count === false){
			FileUtil::load404Html();
			exit(0);
		}
	
		$pageUtil = new PageUtil($pagesize,$count,$page); 
		$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
		$memberArray = $service->getCompanyMember($user['mid'],$state,$index,$pagesize);
		if($memberArray === false){
			FileUtil::load404Html();
			exit(0);				
		}

        $params = array();
        $params['style'] = 'user';
        $params['substyle'] = 'companyMember';
   		$params['count'] = $count;
        $params['member'] = $memberArray;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = $baseUrl;
        return $params;
    }
}