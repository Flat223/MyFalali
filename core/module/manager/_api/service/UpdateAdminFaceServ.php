<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdateAdminFaceServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if($admin == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请登录";
			return $ret;
		}
		
		$face = isset($_REQUEST['face'])?trim($_REQUEST['face']):"";
		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
		
        $callback= $service->updateFace($admin['aid'],$face);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		$admin = $service->getAdminByAid($admin['aid']);
		if($admin === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		UserAgent::addAdmin($admin);
		 
		$ret['ret'] = 1;
		$ret['msg'] = "头像更新成功"; 
		$ret['face'] = $admin['face'];
		return $ret;
	}
}