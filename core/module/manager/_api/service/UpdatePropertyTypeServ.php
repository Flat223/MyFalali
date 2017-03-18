<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdatePropertyTypeServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if($admin == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		
		$ptid = isset($_REQUEST['ptid'])?trim($_REQUEST['ptid']):"0";
		$name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
		
		if(empty($ptid) || empty($name)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		
		FileUtil::requireService("PropertyServ");
		$service = new PropertyServ();
		
		$propertyType = $service->getProTypeByPtid($ptid);
		if($propertyType === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if($propertyType == null){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，该分类不存在";
			return $ret;
		}
		
        $callback= $service->updatePropertyType($ptid,$name);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "修改成功"; 
		return $ret;
	}
}