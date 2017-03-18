<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetUserInfoServ extends BaseAction{
	
	public function action(){
		$ret=array();
		$user=UserAgent::getUser();
		if($user==null){
			$ret['ret']=0;
			$ret['msg']="未登录";
			return $ret;
		}
		FileUtil::requireService("UserServ");
		$service=new UserServ();
		$address=$service->getDefaultAddressId($user['mid']);
		$user['address_id']=$address['id'];
		$ret['ret']=1;
		$ret['data']=$user;
		return $ret;
	}
}