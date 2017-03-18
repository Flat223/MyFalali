<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SaveIndustryOnRegistServ extends BaseAction{
	
	public function action(){
		$mid = isset($_REQUEST['mid'])?trim($_REQUEST['mid']):"";
		$sids= isset($_REQUEST['sids'])?trim($_REQUEST['sids']):"";
		
		if(empty($mid)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}
		if(empty($sids)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先选择行业";
			return $ret;
		}
		
		FileUtil::requireService("UserServ");
		$service=new UserServ();
		$user = $service->getMemberByMid($mid);
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = '参数错误';
			return $ret;
		}
		
 	 	$result = $service->saveUserIndustry($mid,$sids);
		$callback = $result;	
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		
		$ret['ret'] = 1;
		$ret['msg'] = "保存成功";
		return $ret;
	}
}
?>