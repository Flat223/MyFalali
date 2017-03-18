<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SetVIPServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if(empty($admin)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		$mid = isset($_REQUEST['mid'])?trim($_REQUEST['mid']):"";
		$vipLevel = isset($_REQUEST['level'])?trim($_REQUEST['level']):"";
		if(empty($mid) || $vipLevel == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}
		if(!Common::isInteger($vipLevel) || $vipLevel < 0 || $vipLevel > 4){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}

		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
		$callback = $service->setUserVip($mid,$vipLevel);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误,请稍后再试";
			return $ret;
		}
		
		$ret['ret'] = 1;
		$ret['msg'] = "修改成功"; 
		return $ret;
	}
}