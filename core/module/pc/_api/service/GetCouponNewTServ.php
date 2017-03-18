<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/module/_baseClass/BaseAction.php';
class GetCouponNewTServ extends BaseAction{
	public function action(){
		$ret=array();
		$user=UserAgent::getUser();
		if($user==null){
			$ret['ret']=0;
			$ret['msg']="未登录";
			return $ret;
		}
		$cid=isset($_REQUEST['cid'])?trim($_REQUEST['cid']):0;
		$sid=isset($_REQUEST['sid'])?trim($_REQUEST['sid']):0;
		FileUtil::requireService("UserServ");
		$mid=$user['mid'];
		$mobile=$user['mobile'];
		if($user===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误，请稍后再试";
			return $ret;
		}else if($user==null){
			$ret['ret']=0;
			$ret['msg']="用户信息不存在";
			return $ret;
		}
		if($user['type']==1&&$user['sub_type']==0){
			$ret['ret']=0;
			$ret['msg']="高校无法领取优惠券";
			return $ret;
		}
		if($user['type']==1&&$user['sub_type']==0){
			$ret['ret']=0;
			$ret['msg']="公司无法领取优惠券";
			return $ret;
		}
		if($user['type']==3){
			$ret['ret']=0;
			$ret['msg']="供应商无法领取优惠券";
			return $ret;
		}
		FileUtil::requireService("CouponServ");
		$service=new CouponServ();
		$callback=$service->getCoupon($mid,$mobile,$cid,1,$sid);
		if($callback===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误，请稍后再试2";
			return $ret;
		}
		if($callback==-1&& gettype($callback) =="integer"){
			$ret['ret']=0;
			$ret['msg']="优惠券无法重复领取";
			return $ret;
		}
		$ret['ret']=1;
		$ret['msg']="领取成功";
		return $ret;
	}
}