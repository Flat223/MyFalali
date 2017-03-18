<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Member extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		FileUtil::requireService("TransactionServ");
		$service=new TransactionServ();
		$transaction=$service->getUserTransaction($user['mid'],1);
		
		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'member';	
		$params['transaction'] = $transaction;	
		return $params;
	}
	
}