<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteApplyFundRecordServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if($admin == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		$mid = isset($_REQUEST['mid'])?trim($_REQUEST['mid']):"0";
		$collegeMid = isset($_REQUEST['collegeMid'])?trim($_REQUEST['collegeMid']):"0";
		
		if($mid == "0" || $collegeMid == '0'){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		FileUtil::requireService("CollegeServ");
		$service=new CollegeServ();
		$callback=$service->deleteApplyFundRecord($mid,$collegeMid);	 
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