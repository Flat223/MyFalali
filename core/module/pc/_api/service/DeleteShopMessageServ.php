<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteShopMessageServ extends BaseAction{
	
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
		
		$rids = isset($_REQUEST['rids'])?trim($_REQUEST['rids']):"";
		$order_codes = isset($_REQUEST['order_codes'])?trim($_REQUEST['order_codes']):"";
		
		if(empty($rids) && empty($order_codes)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		
		FileUtil::requireService("ShopServ");
		$service = new ShopServ();
		
		if(!empty($rids)) {
			$callback = $service->deleteRemindMesage($rids,$shop['sid']);
		}
		if(!empty($order_codes)){
			$callback = $service->deleteNewOrderMesage($order_codes,$shop['sid']); 
		}
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
		}
		
		$ret['ret'] = 1;
		$ret['msg'] = "删除成功"; 
		return $ret;
	}
}

?>