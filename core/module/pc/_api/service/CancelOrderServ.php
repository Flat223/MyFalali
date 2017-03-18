<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CancelOrderServ extends BaseAction{
    public function action(){
	    $user = UserAgent::getUser();
        $order_code=isset($_REQUEST['order_code'])?trim($_REQUEST['order_code']):"";
        
        FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		FileUtil::requireService("CouponServ");
		$couservice=new CouponServ();
		
		$order = $service->getOrderByOrderCode($order_code);
		if($order === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1,请稍后再试";
			return $ret;
		}
		if(empty($order)){
			$ret['ret'] = 0;
			$ret['msg'] = "未找到该订单";
			return $ret;
		}
		
		$productArray = $service->getProductArrayByOrder($order_code);
		if($productArray === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2,请稍后再试";
			return $ret;
		}
		if(empty($productArray)){
			$ret['ret'] = 0;
			$ret['msg'] = "取消订单失败,未找到该订单下的产品";
			return $ret;
		}
		$skuids = "";
		foreach ($productArray as $product){
			if(empty($skuids)){
				$skuids = $product['skuid'];	
			} else {
				$skuids .= ",".$product['skuid'];	
			}
		}
		
        $callback = $service->cancelOrder($user['mid'],$order_code);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误3,请稍后再试";
			return $ret;
		}
		if($order['couponid']>0){
			$cid=$order['couponid'];
			$coupon=$couservice->getCouponDetailS($cid,$user['mid']);
			if($coupon!=null&&$coupon!==false){
				$callbackc=$couservice->updateUseStatus($user['mid'],$cid,1);	
				if($callbackc===false){
					$ret['ret'] = 0;
					$ret['msg'] = "抱歉，服务器错误4,请稍后再试";
					return $ret;
				}
			}
		}
		//更新商品库存信息
		$callback = $service->updateSkuInfoOnCancelOrder($skuids);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误4,请稍后再试";
			return $ret;
		}

				 
		$ret['ret'] = 1;
		$ret['msg'] = "已取消该订单"; 
		return $ret;
    }
}