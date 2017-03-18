<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Friends extends BaseAction{
	
	public function action(){
		$params = array();
		$user = UserAgent::getUser();
		$type = isset($_GET['type'])?$_GET['type']:1;
        $info = isset($_GET['searchinfo'])?trim($_GET['searchinfo']):"";
        $page = isset($_GET['page'])?trim($_GET['page']):1;
        if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
				
        $pagesize = 5;
        $baseUrl = "/user/friends.html?";
		
		FileUtil::requireService("FriendsServ");
		$service=new FriendsServ();
		
		if(!empty($info)){
			$friendArray = $service->searchfriends($info);	
			if($friendArray === false){
				FileUtil::load404Html();
				exit(0);
			}	
		} else {
			$friendArray=$service->getUserfriends($user['mid'],$type);
			if($friendArray === false){
				FileUtil::load404Html();
				exit(0);
			}
			
			if(!empty($friendArray)){
				foreach($friendArray as $friend){
			        if(empty($fids)){
				        $fids = $friend['mid'];
			        } else {
				        $fids .= ",".$friend['mid'];
			        }
		        }
				$count = count($friendArray);
				$pageUtil = new PageUtil($pagesize,$count,$page); 
				$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
				$dynamic = $service->getfriendsDynamic($fids,$index,$pagesize);	
				if($dynamic === false){
					FileUtil::load404Html();
					exit(0);
				}
				$params['dynamic'] = $dynamic;
				$params['baseurl'] = $baseUrl;
				$params['pager'] = $pageUtil;
				$params['count'] = $count;
			}
		} 
		
		$params['style'] = 'user';
		if(!isset($_GET['type'])){
			$params['substyle'] = 'friends';	
		}
		$params['friends'] = $friendArray;
		return $params;
	}
}