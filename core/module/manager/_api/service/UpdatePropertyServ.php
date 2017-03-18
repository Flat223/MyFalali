<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdatePropertyServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if(empty($admin)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		
		$ptid = isset($_REQUEST['ptid'])?trim($_REQUEST['ptid']):"";
		$proid = isset($_REQUEST['proid'])?trim($_REQUEST['proid']):"";
		$name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
		
		if(empty($proid) || empty($name) || empty($ptid)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}
		
		FileUtil::requireService("PropertyServ");
		$service = new PropertyServ();
		
		$proType = $service->getProTypeByPtid($ptid);
		if($proType === false){
			$ret['ret'] = 0;
            $ret['msg'] = "抱歉，服务器错误0，请稍后再试";
            return $ret;
		}
		if(empty($proType)){
			$ret['ret'] = 0;
            $ret['msg'] = "未找到该产品分类";
            return $ret;
		}
		
		$property= $service->getPropertyById($proid);
		if($property === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
		if(empty($property)){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，该属性不存在";
			return $ret;
		}
		
		$property = $service->getPropertyByNames($ptid,$name);
		if($property === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}
		if(!empty($property)){
			$ret['ret'] = 0;
			$ret['msg'] = "该分类已存在此属性名称";
			return $ret;
		}
		
        $callback= $service->updateProperty($proid,$name);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "修改成功"; 
		return $ret;
	}
}