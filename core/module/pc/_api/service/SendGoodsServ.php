<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SendGoodsServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user == null){
			$ret['ret'] = -1;
			$ret['msg'] = "请登录";
			return $ret;
		}
		if($user['type'] != 3){
			$ret['ret'] = -1;
			$ret['msg'] = "你的身份不是供应商";
			return $ret;
		}
		
		FileUtil::requireService("ShopServ");
		$service=new ShopServ();
		$shop = $service->getShopByMid($user['mid']);
		if($shop === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误0，请稍后再试";
			return $ret;
		}
		if($shop == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先申请店铺";
			return $ret;
		}
		
		$order_code=isset($_REQUEST['order_code'])?trim($_REQUEST['order_code']):0;
		$logistics=isset($_REQUEST['logistics'])?trim($_REQUEST['logistics']):'';
		$logistics_code=isset($_REQUEST['logistics_code'])?trim($_REQUEST['logistics_code']):0;
		
		if($order_code == 0 || $logistics == '' || $logistics_code == '0'){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		
		FileUtil::requireService("ShopOrderServ");
		$service=new ShopOrderServ();
        $callback = $service->confirmSendGoods($shop['sid'],$order_code,$logistics,$logistics_code);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		$ret = array();
		$ret['ret'] = 1;
		$ret['msg'] = "发货成功"; 
		return $ret;
	}
}

?>