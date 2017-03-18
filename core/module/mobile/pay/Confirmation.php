<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Confirmation extends BaseAction{
	
	public function action(){
		$codes=isset($_REQUEST['codes'])?trim($_REQUEST['codes']):"";
		$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
		$otype=isset($_REQUEST['otype'])?trim($_REQUEST['otype']):0;//1产品 2会员充值
		if(empty($codes)){
			FileUtil::load404Html();
			exit(0);
		}
		$user=UserAgent::getUser();
		$mid=$user['mid'];
		FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		$codelist=explode(',', $codes);
		$totalmoney=0.00;
		if($otype==1){
			foreach($codelist as $code){
				$order=$service->getOrderDetail($code,$mid);
				if($order['couponid']>0){
					$totalmoney+=$order['dis_fee'];
				}else{
					$totalmoney+=$order['tot_fee'];
				}
			}
		}else if($otype==2){
			$order=$service->getVipOrderDetail($codes);
			$totalmoney=$order['pay_price'];
		}
/*
		echo($order);
		exit(0);
*/
		$params = array();
		$params['totalprice']=$totalmoney;
		$params['codes']=$codes;
		$params['addressid']=$id;
		$params['otype']=$otype;
		return $params;
	}
}