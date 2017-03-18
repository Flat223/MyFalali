<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GroupManager extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user['type'] != 3){
			FileUtil::load404Html();
			exit(0);
		}
		$page=isset($_REQUEST['page'])?trim($_REQUEST['page']):1;
		FileUtil::requireService("ShopServ");
		$service=new ShopServ();
		$shop = $service->getShopByMid($user['mid']);
		if($shop === false){
			FileUtil::load404Html();
			exit(0);
		}
		if($shop == null){
			FileUtil::load404Html();
			exit(0);
		} 
		$sid = $shop['sid'];
		
		FileUtil::requireService("GroupServ");
		$groupservice=new GroupServ();
		
		$num=10;
		$count=$groupservice->getGroupCount($sid);
		$grouplist=$groupservice->getGroupList($sid,$num,$page);
		foreach($grouplist as $key=>$group){
			$temp="";
			foreach($group['product'] as $product){
				$temp.=$product['pname'].",";
			}
			$grouplist[$key]['pname']=substr($temp, 0,strlen($temp)-1);
		}
		$params['style'] = 'myshop';
		$params['substyle'] = 'groupManager';
		$params['grouplist']=$grouplist;
		$params['count']=$count;
        $pageUtil = new PageUtil(10,$count,$page);
		$params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/myshop/groupManager.html';
        $params['pager'] = $pageUtil;
  
		return $params;
				

	}
	
}