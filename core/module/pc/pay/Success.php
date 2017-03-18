<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Success extends BaseAction{
	
	public function action(){
		$params = array();
		$codes=isset($_REQUEST['ordercode'])?trim($_REQUEST['ordercode']):"";
		$ordertype=isset($_REQUEST['type'])?trim($_REQUEST['type']):0;//订单类型 1产品订单  2会员充值订单
		$offline=isset($_REQUEST['offl'])?trim($_REQUEST['offl']):0;
		$user=UserAgent::getUser();
		$mid=$user['mid'];
		FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		$codelist=explode(',', $codes);
		if($ordertype==1){
			$order=$service->getOrderDetailByCode($codelist[0]);
			$detail=$order['province_name'].$order['city_name'].$order['country_name'].$order['detail_address'].'('.$order['consignee'].'收)'.$order['mobile'];
			$params['detail']=$detail;
			if($order['payment_type']==2){
				$params['type']=2;
			}else{
				$params['type']=0;
			}
			$price=0;
			foreach($codelist as $value){
				$order1=$service->getOrderDetailByCode($value);
				if($order['couponid']>0){
					$price+=$order['dis_fee'];
					}else{
					$price+=$order['tot_fee'];
				}
			}
			$params['price']=$price;
		}else if($ordertype==2){
			$order=$service->getVipOrderDetail($codes);
			$params['price']=$order['pay_price'];
		}
		$params['offline']=$offline;
		$params['otype']=$ordertype;
		return $params;
	}
	
}