<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddMemberServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if(empty($admin)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		
		$nickname = isset($_REQUEST['nickname'])?trim($_REQUEST['nickname']):"";
		$realname = isset($_REQUEST['realname'])?trim($_REQUEST['realname']):"";
		$user_type = isset($_REQUEST['user_type'])?trim($_REQUEST['user_type']):"";
		$sub_type = isset($_REQUEST['sub_type'])?trim($_REQUEST['sub_type']):"";
// 		$cid = isset($_REQUEST['cid'])?trim($_REQUEST['cid']):"";
		$mobile = isset($_REQUEST['mobile'])?trim($_REQUEST['mobile']):"";
		$password = isset($_REQUEST['password'])?trim($_REQUEST['password']):"";

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
		$user = array();
		$user['user_type'] = $user_type;
		$user['sub_type'] = $sub_type;
		$user['nickname'] = $nickname;
		$user['realname'] = $realname;
		$user['mobile'] = $mobile;
		$user['password'] = $password;
/*
		if($cid != ""){
			$user['cid'] = $cid;	
		}
*/

		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
		$ur = $service->getUserByMobile($mobile);
        if(!empty($ur)){
            $ret['ret'] = 0;
            $ret['msg'] = "该手机已注册！";
            return $ret;
        }
		$callback = $service->addUser($user);
		$ret = array();
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}

		$ret['ret'] = 1;
		$ret['msg'] = "添加成功"; 
		$ret['data'] = $callback;
		return $ret;
	}
}