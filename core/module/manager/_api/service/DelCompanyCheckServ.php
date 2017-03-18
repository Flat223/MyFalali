<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DelCompanyCheckServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if($admin == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		$mid = isset($_REQUEST['mid'])?trim($_REQUEST['mid']):"0";
		$company_type = isset($_REQUEST['company_type'])?trim($_REQUEST['company_type']):"0";
		
		if($mid == "0" || $company_type == '0'){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		FileUtil::requireService("CompanyServ");
		$service=new CompanyServ();
		$callback=$service->delCompanycheck($mid,$company_type);	 
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