<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/module/_baseClass/BaseAction.php';
class GetGroupProServ extends BaseAction{
	public function action(){
		$ret=array();
		$skuid=isset($_REQUEST['skuid'])?trim($_REQUEST['skuid']):0;
		if($skuid<=0){
			$ret['ret']=0;
			$ret['msg']="参数错误";
			return $ret;
		}
		FileUtil::requireService("GroupServ");
		$service=new GroupServ();
		$detail=$service->getBuyProductDetail($skuid);
		if($detail===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误，请稍后再试";
			return $ret;
		}
		if($detail===null){
			$ret['ret']=0;
			$ret['msg']="数据无效";
			return $ret;
		}
		$ret['ret']=1;
		$ret['msg']="获取成功";
		$ret['data']=$detail;
		return $ret;
	}
}