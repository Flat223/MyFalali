<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddAdminServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if(empty($admin)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		
		if($admin['rid'] != 1){
			$ret['ret'] = 0;
			$ret['msg'] = "只要超级管理员才有添加管理员的权限哦";
			return $ret;
		}
		
		$rid = isset($_REQUEST['rid'])?trim($_REQUEST['rid']):'';
		$nickname = isset($_REQUEST['nickname'])?trim($_REQUEST['nickname']):"";
		$realname = isset($_REQUEST['realname'])?trim($_REQUEST['realname']):"";
		$mobile = isset($_REQUEST['mobile'])?trim($_REQUEST['mobile']):"";
		$password = isset($_REQUEST['password'])?trim($_REQUEST['password']):"";
		
		if(empty($rid)){
			$ret['ret'] = 0;
			$ret['msg'] = '缺少参数';
			return $ret;
		}
		if($realname == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写真实姓名";
			return $ret;
		}
		if($mobile == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写手机号码";
			return $ret;
		}
		if(!Common::isMobile($mobile)){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写正确的手机号码";
			return $ret;
		}
		
		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
		$admin2 = $service->getAdminByMobile($mobile);
		if($admin2 === false){
			$ret['ret'] = 0;
			$ret['msg'] = '抱歉,服务器错误1,请稍后再试';
			return $ret;
		}
		if(!empty($admin2)){
			$ret['ret'] = 0;
			$ret['msg'] = '该手机号已注册管理员';
			return $ret;
		}
		if($password == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写密码";
			return $ret;
		}
		if(strlen($password) < 6){
			$ret['ret'] = 0;
			$ret['msg'] = "密码长度不低于6位";
			return $ret;
		}
		
		$admin1['rid'] = $rid;
		$admin1['nickname'] = $nickname;
		$admin1['realname'] = $realname;
		$admin1['mobile'] = $mobile;
		$admin1['password'] = $password;

		$callback = $service->addAdmin($admin1);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}
		
		$ret['ret'] = 1;
		$ret['msg'] = "添加成功"; 
		return $ret;
	}
}