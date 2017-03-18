<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteOrderServ extends BaseAction{
    public function action(){
	    $user = UserAgent::getUser();
	    if(empty($user)){
            $result['ret'] = 0;
            $result['msg'] = "请先登录！";
            return $result;
        }
        
        $order_code=isset($_REQUEST['order_code'])?trim($_REQUEST['order_code']):"";
        if(empty($order_code)){
	        $result['ret'] = 0;
            $result['msg'] = "缺少参数";
            return $result;
        }
        
        FileUtil::requireService("OrderServ");
		$service=new OrderServ();
        $callback = $service->deleteOrder($order_code);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
				 
		$ret['ret'] = 1;
		$ret['msg'] = "删除成功"; 
		return $ret;
    }
}