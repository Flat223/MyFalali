<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddCompanyMemberServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		
		if($user['type'] !=2 || $user['sub_type'] != 0){
			$ret['ret'] = 0;
			$ret['msg'] = "您不能添加公司成员";
			return $ret;
		} 
		
		$nickname = isset($_REQUEST['nickname'])?trim($_REQUEST['nickname']):"";
		$realname = isset($_REQUEST['realname'])?trim($_REQUEST['realname']):"";
		$sub_type = isset($_REQUEST['sub_type'])?trim($_REQUEST['sub_type']):"";
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
		
		$member['sub_type'] = $sub_type;
		$member['nickname'] = $nickname;
		$member['realname'] = $realname;
		$member['mobile'] = $mobile;
		$member['password'] = $password;

		FileUtil::requireService("UserServ");
		$service = new UserServ();
		$user2 = $service->getMemberByMobile($mobile);
		if($user2 === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
        if(!empty($user2)){
            $ret['ret'] = 0;
            $ret['msg'] = "该手机号已注册!";
            return $ret;
        }
        
        FileUtil::requireService("CompanyServ");
		$service = new CompanyServ();
		$callback = $service->addCompanyMember($user['mid'],$member);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}

		$ret['ret'] = 1;
		$ret['msg'] = "添加成功"; 
		$ret['data'] = $callback;
		return $ret;
	}
}