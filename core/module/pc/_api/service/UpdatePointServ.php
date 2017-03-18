<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdatePointServ extends BaseAction{
	
	public function action(){
		$ret=array();		
		$out_trade_no = $_GET['out_trade_no'];
		$user=UserAgent::getUser();
		$mid=$user['mid'];
        FileUtil::requireService("OrderServ");
		$orderService = new OrderServ();
		$order = $orderService->getOrderDetailByCode($out_trade_no);
		FileUtil::requireService("CouponServ");
		$couponService=new CouponServ();
		$callback1=false;
		if($order['couponid']>0){
			$money=$order['dis_fee'];
			$callback1=$couponService->updateUseStatus($mid,$order['couponid'],2);
			if(!$callback1){
				FileUtil::loadServerErrHtml();
				exit(0);
			} 
		}
		$ret['1']=$callback1;
		return $ret;
	}
}