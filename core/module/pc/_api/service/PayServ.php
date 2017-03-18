<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class PayServ extends BaseAction{
	
	public function action(){
		$ret = array();
		$params = array();
		$ordercode = isset($_REQUEST['ordercode'])?$_REQUEST['ordercode']:"";
		$payType = isset($_REQUEST['payType'])?$_REQUEST['payType']:0;//1支付宝 2微信 3 线下支付
		$otype=isset($_REQUEST['otype'])?$_REQUEST['otype']:0;//订单类型 1产品订单  2会员充值订单
		
		if($otype<=0){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		if($ordercode == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		$payTypes = array('1','2','3');
		if(!in_array($payType, $payTypes)){
			$ret['ret'] = 0;
			$ret['msg'] = "支付方式无效";
			return $ret;
		}
		$user = UserAgent::getUser();
		$mid=$user['mid'];
		FileUtil::requireService("OrderServ");
		$orderService = new OrderServ();
		$ordercodelist=explode(',', $ordercode);
		if($payType==3){
			$url=isset($_REQUEST['url'])?trim($_REQUEST['url']):"";
			$callback=$orderService->setOrderOffline($ordercode,$mid,$url);
			if(!$callback){
				$ret['ret']=0;
				$ret['msg']="服务器错误，请稍后再试";
				return $ret;
			}
			$ret['ret']=1;
			$ret['msg']="订单提交成功正在审核中。。。";
			header('Location: '.'http://'.$_SERVER['HTTP_HOST'].'/pay/success.html?ordercode='.$ordercode."&type=".$otype."&offl=1");
		}
		if($otype==1){
			foreach($ordercodelist as $value){
				$order = $orderService->getOrderDetailByCode($value);
				$order['otype']=1;
				$params['order'][] = $order;
			}
		}else if($otype==2){
			foreach($ordercodelist as $value){
				$order=$orderService->getVipOrderDetail($value);
				$order['otype']=2;
				$params['order'][] = $order;
			}
		}
		
		foreach($params['order'] as $order1){
			if($order1 === false){
				$ret['ret'] = -1;
				$ret['msg'] = "服务器错误";
				return $ret;
			}
			if($order1 === null){
				$ret['ret'] = 0;
				$ret['msg'] = "无此订单";
				return $ret;
			}
			if($order1['payer_mid'] != $user['mid']){
				$ret['ret'] = 0;
				$ret['msg'] = "无权操作";
				return $ret;
			}
			if($order1['state'] != 1){
				$ret['ret'] = 0;
				$ret['msg'] = "此订单不可支付";
				return $ret;
			}
		}
		$params['type'] = 'user';
		$params['sidebar'] = 'myinfo';
		if($payType == 1){
			FileUtil::loadHtml("alipay.html",$params);
		}else if($payType == 2){
			FileUtil::loadHtml("wechat_pay.html",$params);
		}else{
			FileUtil::loadHtml("wechat_pay.html",$params);	//默认其他就微信支付吧
		}
	}
}
?>