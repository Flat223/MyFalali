<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ReUploadVoucherServ extends BaseAction{
	public function action(){
		$user = UserAgent::getUser();
		if($user == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		
		$order_code = isset($_REQUEST['order_code'])?trim($_REQUEST['order_code']):"";
		$voucherImg = isset($_REQUEST['voucherImg'])?trim($_REQUEST['voucherImg']):"";
				
		if(empty($order_code)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		
		if(empty($voucherImg)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先上传凭证";
			return $ret;
		}
		
		FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		$callback = $service->uploadVoucher($user['mid'],$order_code,$voucherImg);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		
		$ret['ret'] = 1;
		$ret['msg'] = "提交成功";
		return $ret;
	}
}