<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Message extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user['type'] != 3){
			FileUtil::load404Html();
			exit(0);
		}
		FileUtil::requireService("ShopServ");
		$service=new ShopServ();
		$shop = $service->getShopByMid($user['mid']);
		if($shop === false){
			FileUtil::load404Html();
			exit(0);
		}
		if(empty($shop)){
			FileUtil::load404Html();
			exit(0);
		} 
		
		$type=isset($_GET['type'])?trim($_GET['type']):"1";
        $page = isset($_GET['page'])?trim($_GET['page']):1;
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
		
		if($type != 1 && $type != 2){
			FileUtil::load404Html();
			exit(0);
		}
		
		$pagesize = 10;
		$baseUrl = "../myshop/message.html?type=".$type;
	
		$count = $service->getShopMessageCount($shop['sid'],$type);
        if($count === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$pageUtil = new PageUtil($pagesize,$count,$page); 
		$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
		$message = $service->getShopMessage($shop['sid'],$type,$index,$pagesize);
		if($message === false){
			FileUtil::load404Html();
			exit(0);				
		}
		
		$params['style'] = 'myshop';
		$params['substyle'] = 'message';
		$params['message'] = $message;
		$params['count'] = $count;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = $baseUrl;
		return $params;
	}
	
}