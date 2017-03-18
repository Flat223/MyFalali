<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteGroupServ extends BaseAction{
    public function action(){
	    $ret=array();
		$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
		$user=UserAgent::getUser();
		if($user==null){
			$ret['ret']=0;
			$ret['msg']="未登录";
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
		$callback=$service->deleteGroup($sid,$id);
		if($callback===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误，请稍后再试";
			return $ret;
		}
/*
		return $callback;
		exit(0);
*/
		$ret['ret']=1;
		$ret['msg']="删除成功";
		return $ret;
    }
}
