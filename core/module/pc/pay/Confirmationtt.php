<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Confirmationtt extends BaseAction{
	
	public function action(){
		$codes=isset($_REQUEST['codes'])?trim($_REQUEST['codes']):"";
		$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
		if(empty($codes)){
			
		}
		$user=UserAgent::getUser();
		$mid=$user['mid'];
		FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		$codelist=explode(',', $codes);
		$totalmoney=0.00;
		foreach($codelist as $code){
			$order=$service->getOrderDetail($code,$mid);
			if($order['couponid']>0){
				$totalmoney+=$order['dis_fee'];
			}else{
				$totalmoney+=$order['tot_fee'];
			}
		}
		$params = array();
		$params['totalprice']=$totalmoney;
		$params['codes']=$codes;
		return $params;
	}
}