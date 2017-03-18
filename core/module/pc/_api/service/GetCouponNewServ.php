<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/module/_baseClass/BaseAction.php';
class GetCouponNewServ extends BaseAction{
	public function action(){
		$ret=array();
		$admin=UserAgent::getAdmin();
		if($admin==null){
			$ret['ret']=0;
			$ret['msg']="未登录";
			return $ret;
		}
		$mobile=isset($_REQUEST['mobile'])?trim($_REQUEST['mobile']):0;
		$cid=isset($_REQUEST['cid'])?trim($_REQUEST['cid']):0;
		if($mobile==0){
			$ret['ret']=0;
			$ret['msg']="参数错误";
			return $ret;
		}
		FileUtil::requireService("UserServ");
		$userservice=new UserServ();
		$user=$userservice->getMemberByMobile($mobile);
		$mid=$user['mid'];
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
			$ret['msg']="高校无法领取代金券";
			return $ret;
		}
		if($user['type']==1&&$user['sub_type']==0){
			$ret['ret']=0;
			$ret['msg']="公司无法领取代金券";
			return $ret;
		}
		if($user['type']==3){
			$ret['ret']=0;
			$ret['msg']="供应商无法领取代金券";
			return $ret;
		}
		FileUtil::requireService("CouponServ");
		$service=new CouponServ();
		$callback=$service->getCoupon($mid,$mobile,$cid,2,0);
		if($callback===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误，请稍后再试";
			return $ret;
		}
		if($callback==-1&& gettype($callback) =="integer"){
			$ret['ret']=0;
			$ret['msg']="代金券无法重复领取";
			return $ret;
		}
		$ret['ret']=1;
		$ret['msg']="领取成功";
		return $ret;
	}
}