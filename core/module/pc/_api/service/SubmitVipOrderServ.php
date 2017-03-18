<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SubmitVipOrderServ extends BaseAction{
	
	public function action(){
		$ret=array();
		$user=UserAgent::getUser();
		if($user==null){
			$ret['ret']=-1;
			$ret['msg']="未登录";
			return $ret;
		}
		$mid=$user['mid'];
		$type=isset($_REQUEST['type'])?trim($_REQUEST['type']):0;//1月 2季度  3年
		$paytype=isset($_REQUEST['paytype'])?trim($_REQUEST['paytype']):0;//支付方式 1支付宝  2微信  3其他
		$money=0;
		if($type==1){
			$money=0.01;
		}else if($type==2){
			$money=57;
		}else if($type==3){
			$money=190;
		}
		$time=time();
		$rand=rand(100,999);
		$orderCode=$time.$rand;
		FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		$callback=$service->saveVipOrder($mid,$time,$orderCode,$money,$type,$paytype);
		if(!$callback){
			$ret['ret']=0;
			$ret['msg']="服务器错误，请稍后再试1";
			return $ret;
		}
		$ret['ret']=1;
		$ret['msg']="保存成功";
		$ret['code']=$orderCode;
		return $ret;			
	}
}
?>