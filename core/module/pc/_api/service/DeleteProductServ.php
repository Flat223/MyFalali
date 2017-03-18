<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteProductServ extends BaseAction{
    public function action(){
        $user = UserAgent::getUser();
        if(empty($user)){
	    	$ret['ret'] = 0;
            $ret['msg'] = "请先登录";
            return $ret;
        } 
        if($user['type'] != 3){
			$ret['ret'] = 0;
			$ret['msg'] = "你的身份不是供应商";
			return $ret;
		}

		FileUtil::requireService("ShopServ");
		$service = new ShopServ();
		$shop = $service->getShopByMid($user['mid']);
		if($shop === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误0，请稍后再试";
			return $ret;
		}
		if(empty($shop)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先申请店铺";
			return $ret;
		}
		
        $pids = isset($_POST['pids'])?trim($_POST['pids']):"";
        if(empty($pids)){
	    	$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
        }
        
        FileUtil::requireService("ShopServ");
        $service = new ShopServ();
        $callback = $service->deleteProduct($pids,$shop['sid']);
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