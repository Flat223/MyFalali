<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ConfirmReceiveGoods extends BaseAction{
    public function action(){
	    $user = UserAgent::getUser();
	    if($user == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
        $order_code=isset($_REQUEST['order_code'])?trim($_REQUEST['order_code']):"";
        if($order_code == 0){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		
        FileUtil::requireService("OrderServ");
		$service=new OrderServ();
        $callback = $service->updateOrderState($order_code,'4');
        
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
				 
		$ret['ret'] = 1;
		$ret['msg'] = "已确认收货"; 
		return $ret;
    }
}