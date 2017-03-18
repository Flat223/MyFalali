<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteInvoiceServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		$rid=isset($_REQUEST['rid'])?trim($_REQUEST['rid']):"";
		
		FileUtil::requireService("InvoiceServ");
		$service=new InvoiceServ();
 	 	$callback = $service->deleteUserInvoice($user['mid'],$rid);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "删除成功"; 
		return $ret;
	}
}

?>