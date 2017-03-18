<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class OrderRefundServ extends BaseAction{
    public function action(){
	    $user = UserAgent::getUser();
        $order_code=isset($_REQUEST['order_code'])?trim($_REQUEST['order_code']):"";
        $pid=isset($_REQUEST['pid'])?trim($_REQUEST['pid']):"";
        $reason=isset($_REQUEST['reason'])?trim($_REQUEST['reason']):"";
        $otherReason=isset($_REQUEST['otherReason'])?trim($_REQUEST['otherReason']):"";
        $refundImg=isset($_REQUEST['refundImg'])?trim($_REQUEST['refundImg']):"";
        
        if(empty($order_code) || empty($pid)){
	    	$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
        } 
        
        FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		$callback = $service->refundOrderProduct($order_code,$pid,$reason,$otherReason,$refundImg);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
		
        $callback = $service->updateOrderState($order_code,8);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}
		
		$callback = $service->updateOrderProductState($order_code,$pid,2);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
			return $ret;
		}
				 
		$ret['ret'] = 1;
		$ret['msg'] = "退款申请成功"; 
		return $ret;
    }
}