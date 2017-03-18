<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/module/_baseClass/BaseAction.php';
class InsertCouponTServ extends BaseAction{
	public function action(){
		//$num,$starttime,$endtime,$money,$type,$name,$intro
		$ret=array();
		$user=UserAgent::getUser();
		if($user==null){
			$ret['ret']=0;
			$ret['msg']="未登录";
			return $ret;
		}
		$num=isset($_REQUEST['num'])?trim($_REQUEST['num']):0;
		$stime=isset($_REQUEST['stime'])?trim($_REQUEST['stime']):"";
		$etime=isset($_REQUEST['etime'])?trim($_REQUEST['etime']):"";
		$starttime=strtotime($stime);
		$endtime=strtotime($etime);
		$money=isset($_REQUEST['money'])?trim($_REQUEST['money']):0;
		$type=isset($_REQUEST['type'])?trim($_REQUEST['type']):0;
		$name=isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
		$intro=isset($_REQUEST['intro'])?trim($_REQUEST['intro']):"";
		$sid=isset($_REQUEST['sid'])?trim($_REQUEST['sid']):0;
		$limit=isset($_REQUEST['uselimit'])?trim($_REQUEST['uselimit']):0;
/*
		if($num<=0||$type<=0||$money<=0||){
			
		}
*/		
		FileUtil::requireService("CouponServ");
		$service=new CouponServ();
		$callback=$service->insertCoupon($num,$starttime,$endtime,$money,$type,$name,$intro,$sid,$limit);
		if($callback===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误，请稍后再试1";
			return $ret;
		}
		$ret['ret']=1;
		$ret['msg']="添加成功";
		return $ret;
		
	}
}