<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RemindMerchantServ extends BaseAction{
	public function action(){
		$ret=array();
		$user=UserAgent::getUser();
		if($user==null){
			$ret['ret']=0;
			$ret['msg']="未登录";
			return $ret;
		}
		$code=isset($_REQUEST['code'])?trim($_REQUEST['code']):"";
		$mid=$user['mid'];
		FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		$order=$service->getOrderDetailByCode($code);
		if($order === false){
			$ret['ret']=0;
			$ret['msg']="服务器错误1,请稍后再试";
			return $ret;
		}
		if($order === null){
			$ret['ret']=0;
			$ret['msg']="无此订单信息";
			return $ret;
		}
		if($order['state'] != 2){
			$ret['ret']=0;
			$ret['msg']="此订单不能提醒发货";
			return $ret;
		}
		$sid=$order['sid'];
		$callback=$service->remindMerchan($code,$mid,$sid);
		if($callback===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误2,请稍后再试";
			return $ret;
		}
		$ret['ret']=1;
		$ret['msg']="提醒成功";
		return $ret;

	}
}