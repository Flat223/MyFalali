<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddCartServ extends BaseAction{
	public function action(){
		$ret=array();
		$user=UserAgent::getUser();
		if($user==null){
			$ret['ret']=-1;
			$ret['msg']="未登录";

			return $ret;
//			$url="/member/login.html";

		}
		$mid=$user['mid'];
		$pid=isset($_REQUEST['pid'])?trim($_REQUEST['pid']):0;
		$skuid=isset($_REQUEST['skuid'])?trim($_REQUEST['skuid']):0;
		$num=isset($_REQUEST['num'])?trim($_REQUEST['num']):0;
		$testing=isset($_REQUEST['testing'])?trim($_REQUEST['testing']):0;
		$guarantee=isset($_REQUEST['guarantee'])?trim($_REQUEST['guarantee']):0;
		if(!Common::isInteger($pid)||$pid<=0){
			$ret['ret']=0;
			$ret['msg']="参数错误1";
			return $ret;
		}
		if(!Common::isInteger($skuid)||$skuid<=0){
			$ret['ret']=0;
			$ret['msg']="参数错误2";
			return $ret;
		}
		if(!Common::isInteger($num)||$num<=0){
			$ret['ret']=0;
			$ret['msg']="参数错误3";
			return $ret;
		}
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
		$product=$service->getSingleProduct($pid);
		if($product===false||$product==null){
			$ret['ret']=0;
			$ret['msg']="参数错误4";
			return $ret;
		}
		$sid=$product['sid'];
		$callback=$service->AddCart($mid,$pid,$sid,$num,$skuid,$testing,$guarantee);
// 		return $callback;
		if(!$callback){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试22";
			return $ret;
		}
		 $ret['ret']=1;
		 $ret['msg']="添加成功";
		 return $ret;
		
	}
}
