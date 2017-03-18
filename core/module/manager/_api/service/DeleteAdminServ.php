<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteAdminServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if($admin == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		if($admin['rid'] != 1){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉,您还没有删除管理员的权限";
			return $ret;
		}
		$aid = isset($_REQUEST['aid'])?trim($_REQUEST['aid']):"";
		if($aid == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
		
		$callback = $service->deleteAdminByAid($aid);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}

		$ret = array();
		$ret['ret'] = 1;
		$ret['msg'] = "删除成功"; 
		return $ret;
	}
}