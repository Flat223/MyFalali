<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class MemberInfo extends BaseAction{

	public function action(){
		$key = isset($_GET['key'])?trim($_GET['key']):"";
		$type = isset($_GET['type'])?trim($_GET['type']):"";
		$sub_type = isset($_GET['sub_type'])?trim($_GET['sub_type']):"";
		
		$page = isset($_GET['page'])?trim($_GET['page']):1;
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
		
		$pageUtil = "";
        $count = 0;			
        $pagesize = 10;
		$baseUrl = "../usermanager/memberInfo.html?";
		
		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
		$userArray = array();
		
		if(!empty($key)){
			$count = $service->searchUserCountByKey($key);
	        if($count === false){
				FileUtil::load404Html();
				exit(0);
			}
			$pageUtil = new PageUtil($pagesize,$count,$page); 
			$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
			$userArray = $service->searchUserByKey($key,$index,$pagesize);
		} else if(!empty($type)){
			$count = $service->getUserCountByScreen($type,$sub_type);
	        if($count === false){
				FileUtil::load404Html();
				exit(0);
			}
			$pageUtil = new PageUtil($pagesize,$count,$page); 
			$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
			$userArray = $service->getUserByScreen($type,$sub_type,$index,$pagesize);
		} else {
			$count = $service->getAllUserCount();
	        if($count === false){
				FileUtil::load404Html();
				exit(0);
			}
			$pageUtil = new PageUtil($pagesize,$count,$page); 
			$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
			$userArray = $service->getAllUser($index,$pagesize);
		}
		$subTypeArray = $service->getCollegeSubType();
		if($subTypeArray === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$params['style'] = 'usermanager';
		$params['substyle'] = 'memberInfo';
		$params['user'] = $userArray;
		$params['sub_type'] = $subTypeArray;
		$params['baseurl'] = $baseUrl;
		$params['pager'] = $pageUtil;
		$params['count'] = $count;
		return $params;
	}
}