<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SetAdminIdentServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if($admin == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		
		if($admin['rid'] != 1){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉,您还没有设置管理员身份的权限";
			return $ret;
		}
		
		$aid = isset($_REQUEST['aid'])?trim($_REQUEST['aid']):"";
		$rid = isset($_REQUEST['rid'])?trim($_REQUEST['rid']):"";

		if(empty($aid) || empty($rid)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}
		
		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
		$role = $service->getRoreByRid($rid);
		if($role === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
		if($role == null){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，未找到该身份";
			return $ret;
		}		
		$callback = $service->setAdminIdent($aid,$rid);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}
		
		if($aid == $admin['aid']){
			$admin2 = $service->getAdminByAid($aid);
			if($admin2 === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
				return $ret;
			}
			if(empty($admin2)){
				$ret['ret'] = 0;
				$ret['msg'] = "服务器繁忙,请稍后再试";
				return $ret;
			}
			UserAgent::addAdmin($admin2);
		}
		
		$ret = array();
		$ret['ret'] = 1;
		$ret['msg'] = "修改成功"; 
		return $ret;
	}
}