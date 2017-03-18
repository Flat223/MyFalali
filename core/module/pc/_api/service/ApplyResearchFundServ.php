<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ApplyResearchFundServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user==null){
			$ret['ret']=-1;
			$ret['msg']="未登录";
			return $ret;
		}
		if($user['type'] != 1 || $user['sub_type'] == 0 || $user['bind_status'] != 2){
			$ret['ret']=-1;
			$ret['msg']="你不能申请科研基金";
			return $ret;
		}
		FileUtil::requireService("CollegeServ");
		$service = new CollegeServ();
        $callback = $service->applyFund($user['mid'],$user['bind_company']);
       
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		$ret = array();
		$ret['ret'] = 1;
		$ret['msg'] = "申请提交成功,请等待管理员审核"; 
		return $ret;
	}
}

?>