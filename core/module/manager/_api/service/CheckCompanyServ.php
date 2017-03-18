<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CheckCompanyServ extends BaseAction{
    public function action(){
	    $admin = UserAgent::getAdmin();
		if($admin == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
        $mid=isset($_REQUEST['mid'])?trim($_REQUEST['mid']):"";
        $type=isset($_REQUEST['type'])?trim($_REQUEST['type']):"";
        $company_type=isset($_REQUEST['company_type'])?trim($_REQUEST['company_type']):"";
        
        if(empty($mid) || empty($company_type) || empty($type)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误1";
			return $ret;
		}
		
		if(($company_type != 2 && $company_type != 3) || ($type != 1 && $type != 2)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误2";
			return $ret;
		}
        
        FileUtil::requireService("CompanyServ");
		$service=new CompanyServ();
		$company = $service->getCompanyInfoOnUpdate($mid,$company_type);
		
/*
		$ret['ret'] = 0;
		$ret['msg'] = $company;
		return $ret;
*/
		
		if($company === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误0，请稍后再试";
			return $ret;
		}
		if(empty($company)){
			$ret['ret'] = 0;
			$ret['msg'] = "未找到该公司";
			return $ret;
		}
		
        $callback = $service->operateCheckCompany($mid,$type,$company_type);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
		if($type == 1){//同意
			$callback = $service->updateCompanyIdent($company);
			if($callback === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
				return $ret;
			}
		}
		
		FileUtil::requireService("MessageServ");
		$service=new MessageServ();
		
		$companyType = $company_type == 2 ? "公司" : "供应商";
		$message = $type == 1 ? '您的'.$companyType.'注册申请已通过审核' : '抱歉,您的'.$companyType.'注册申请被拒绝;';
		$callback=$service->sendMessage($mid,$companyType."注册审核通知",$message,0);
		if($callback===false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
			return $ret;
		}
				 
		$ret['ret'] = 1;
		$ret['msg'] = $type == 1 ? "已通过该审核" : "已拒绝该审核";
		return $ret;
    }
}