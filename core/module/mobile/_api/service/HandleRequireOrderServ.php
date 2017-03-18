<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class HandleRequireOrderServ extends BaseAction{
    public function action(){
	    $user = UserAgent::getUser();
	    if($user == null){
		    $ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
	    }
        $type=isset($_REQUEST['type'])?$_REQUEST['type']:"";//1:同意 2:拒绝
        $order_code=isset($_REQUEST['order_code'])?trim($_REQUEST['order_code']):"";
        
        FileUtil::requireService("MobileOrderServ");
		$service=new MobileOrderServ();
        $callback = $service->handleOrder($user['mid'],$order_code,$type);
        
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
				 
		$ret['ret'] = 1;
		$ret['msg'] = "操作成功"; 
		return $ret;
    }
}