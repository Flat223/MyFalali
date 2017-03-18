<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class BindCompanyServ extends BaseAction{
	public function action(){
		$ret = array();
		$user = UserAgent::getUser();
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		if($user['type'] != 1 && $user['type'] != 2){
			$ret['ret'] = 0;
			$ret['msg'] = "你不能绑定企业或高校";
			return $ret;
		}
		
		$cid = isset($_REQUEST['cid'])?trim($_REQUEST['cid']):"";
		if(empty($cid)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		$title = $user['type'] == 1 ? "高校" : "企业";
		
		if($user['bind_status'] == 1){
			$ret['ret'] = 0;
			$ret['msg'] = "您绑定的".$title."正在审核中,请勿重复申请";
			return $ret;
		}
		if($user['bind_status'] == 2){	
			$ret['ret'] = 0;
			$ret['msg'] = "您已绑定".$title.",请勿重复绑定";
			return $ret;
		}
		
		FileUtil::requireService('CompanyServ');
		$service = new CompanyServ();
		$callback = $service->bindCompany($user['mid'],$cid,$user['type']);	
		
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		
		FileUtil::requireService('UserServ');
		$service = new UserServ();
		$user2 = $service->getMemberByMid($user['mid']);
		$_SESSION['user'] = $user2;
		
		$ret['ret'] = 1;
		$ret['msg'] = "绑定成功";
		return $ret;
	}
}
