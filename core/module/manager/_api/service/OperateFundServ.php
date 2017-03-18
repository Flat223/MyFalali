<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class OperateFundServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if($admin == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		$mid = isset($_REQUEST['mid'])?trim($_REQUEST['mid']):"0";
		$collegeMid = isset($_REQUEST['collegeMid'])?trim($_REQUEST['collegeMid']):"0";
		$type = isset($_REQUEST['type'])?trim($_REQUEST['type']):"0";
		$info = isset($_REQUEST['info'])?trim($_REQUEST['info']):"";
		if($mid == "0" || $type == "0" || $collegeMid == '0'){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		if($info <= 0 && $type == 1){
			$ret['ret'] = 0;
			$ret['msg'] = "科研基金为0";
			return $ret;
		}
		if(empty($info) && $type == 2){
			$ret['ret'] = 0;
			$ret['msg'] = "拒绝理由为空";
			return $ret;
		}
		FileUtil::requireService("CollegeServ");
		$service=new CollegeServ();
		
		FileUtil::requireService("UserServ");
		$userservice=new UserServ();
		
		FileUtil::requireService("MessageServ");
		$msgservcie=new MessageServ();
		
		$user=$userservice->getMemberByMid($mid);
		if($user===false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
		if($user===null){
			$ret['ret'] = 0;
			$ret['msg'] = "会员信息不存在";
			return $ret;
		}
		$sb=$user['sub_type'];
		$callback=$service->operateResearchFund($mid,$collegeMid,$info,$type);	 
		if($callback === false){	
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}
		if($type == 1){
			$callback=$service->updateResearchFund($mid,$sb,$collegeMid,$info);	
			if($callback === false){	
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
				return $ret;
			} 	
		}
		
		$message = $type == 1 ? '您的科研基金申请已通过审核' : '抱歉,你的科研基金申请被拒绝;理由:'.$info;
		$callback=$msgservcie->sendMessage($mid,"科研基金审核通知",$message,0);
		if($callback===false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误4，请稍后再试";
			return $ret;
		}

		$ret['ret'] = 1;
		if($type == 1){
			$ret['msg'] = "已通过该审核"; 	
		} else {
			$ret['msg'] = "已拒绝该审核"; 	
		}
		
		return $ret;
	}
}