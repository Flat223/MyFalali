<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdateProStatusServ extends BaseAction{

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
        
        $pids = isset($_POST['pids']) ? trim($_POST['pids']) : "";
        $status = isset($_POST['status']) ? trim($_POST['status']) : "";
        if(empty($pids) || empty($status)){
	        $ret['ret'] = 0;
            $ret['msg'] = "缺少参数";
            return $ret;
        }
        if($status != 1 && $status != 2){
	        $ret['ret'] = 0;
            $ret['msg'] = "参数错误";
            return $ret;
        }
        
        $callback = $service->updateProStatus($shop['sid'],$pids,$status);
        if($callback === false){
	        $ret['ret'] = 0;
	        $ret['msg'] = '抱歉,服务器错误1,请稍后再试';
	        return $ret;
        }
        
        $ret['ret'] = 1;
        $ret['msg'] = $status == 1 ? '已将产品下架' : '已将产品上架';
        return $ret;
    }
}