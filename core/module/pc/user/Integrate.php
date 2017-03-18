<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Integrate extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		FileUtil::requireService("TransactionServ");
		$service=new TransactionServ();
		$transaction=$service->getUserTransaction($user['mid'],3);
		if($transaction === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$is_sign = false;
		FileUtil::requireService('UserSignServ');
        $service = new UserSignServ();
        $signRecord = $service->getSignRecordById($user['mid']);
        if($signRecord === false){
			FileUtil::load404Html();
			exit(0);
		}
		if($signRecord != null){
			if(date('Y-m-d') == date("Y-m-d",$signRecord['sign_time'])){
				$is_sign = true;  
			}
		}
		
		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'integrate';	
		$params['transaction'] = $transaction;	
		$params['is_sign'] = $is_sign;	
		return $params;
	}
	
}