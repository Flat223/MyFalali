<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/module/_baseClass/BaseAction.php';
class InsertGroupServ extends BaseAction{
	public function action(){
		$ret=array();
		$skuids=isset($_REQUEST['skuids'])?trim($_REQUEST['skuids']):"";
		$price=isset($_REQUEST['price'])?trim($_REQUEST['price']):0;
		$name=isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
		$user=UserAgent::getUser();
		if($user==null){
			$ret['ret']=0;
			$ret['msg']="未登录";
			return $ret;
		}
		if(empty($skuids)||$price<=0){
			$ret['ret']=0;
			$ret['msg']="参数错误";
			return $ret;
		}
		$mid=$user['mid'];
		FileUtil::requireService("ShopServ");
		$shopservice=new ShopServ();
		$shop=$shopservice->getShopById($mid);
		if($shop===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误，请稍后再试";
			return $ret;
		}
		if($shop===null){
			$ret['ret']=0;
			$ret['msg']="您无权限操作";
			return $ret;
		}
		$sid=$shop['sid'];
		FileUtil::requireService("GroupServ");
		$service=new GroupServ();
		$callback=$service->insertGroup($skuids,$name,$price,$sid);
		if($callback===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误，请稍后再试";
			return $ret; 
		}
		$ret['ret']=1;
		$ret['msg']="添加成功";
		return $ret;
	}
}