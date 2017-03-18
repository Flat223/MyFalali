<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Account extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		FileUtil::requireService("InvoiceServ");
		$service=new InvoiceServ();
 	 	$invoiceArray = $service->getUserInvoiceList($user['mid'],1);
        FileUtil::requireService("IdentityServ");
        $Identity=new IdentityServ();

        $params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'account';
        $params['invoice'] = $invoiceArray;
        $params['user']=$user;
        $params['identity']=$Identity->GetUseridentity($user['mid']);
		return $params;
	}
}