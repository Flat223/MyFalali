<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UnBindCompanyServ extends BaseAction{
	public function action(){
		$ret = array();
		$user = UserAgent::getUser();
		
		$type=isset($_REQUEST['type'])?trim($_REQUEST['type']):"";
		$cid = isset($_REQUEST['cid'])?trim($_REQUEST['cid']):"";
		$title = $type == 1 ? "高校" : "企业";
		
		if($user['bind_status'] != 2){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉,您还未绑定".$title;
			return $ret;
		}
		
		
		FileUtil::requireService('CompanyServ');
		$service = new CompanyServ();
		$callback = $service->unBindCompany($user['mid'],$cid);
		$result = $service->updateUnBindingData($user['mid']);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		
		if($callback && $result){
			FileUtil::requireService('UserServ');
			$service = new UserServ();
			$user2 = $service->getMemberByMid($user['mid']);
			$_SESSION['user'] = $user2;
			
			$ret['ret'] = 1;
			$ret['msg'] = "成功解除绑定";
			return $ret;
		}
	}
}