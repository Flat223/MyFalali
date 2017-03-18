<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Profile extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		FileUtil::requireService("UserServ");
		$service = new UserServ();
		$user2 = $service->getMemberByMid($user['mid']);
		if($user2 === false){
			FileUtil::load404Html();
			exit(0);
		}
		$_SESSION['user'] = $user2;
		
		FileUtil::requireService("UserServ");
		$service=new UserServ();
		$allLabs=$service->getInterestLabList();
// 		$myLabs = $service->getUserInterestLab($user2['mid']); 
/*
		if($labs===false){
			$params['ret']=0;
			$params['msg']="抱歉，服务器错误，请稍后再试";
			return $params;
		}
*/
		$params['style'] = 'user';
		$params['substyle'] = 'profile';
		$params['interestLabs']=$allLabs;
// 		$params['myLabs']=$myLabs;
		return $params;
	}
}