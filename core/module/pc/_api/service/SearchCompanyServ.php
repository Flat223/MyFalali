<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SearchCompanyServ extends	BaseAction{
	public function action(){
		$ret = array();

		$key=isset($_REQUEST['key'])?trim($_REQUEST['key']):"";
		$type=isset($_REQUEST['type'])?trim($_REQUEST['type']):"";
		
		if(empty($key)) {
			$ret['ret'] = 0;
			$ret['msg'] = $type == 1 ? "请输入高校名称" : "请输入企业名称";
			return $ret;
		}
		
		FileUtil::requireService("CompanyServ");
		$service = new CompanyServ();
		$count = $service->searchCompanyCount($key,$type);
        if($count === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
		if($count == 0){
			$ret['ret']	= 0;
			$ret['msg'] = "未搜索到相关信息！";
			return $ret;
		}
		$pageUtil = new PageUtil($pagesize,$count,$page); 
		$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
		$companyArray = $service->searchCompanyList($key,$type,$index,$pagesize);
		if($companyArray === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		} 
		
		$ret['ret'] = 1;
		$ret['msg'] = "搜索成功";
		$ret['company'] = $companyArray;
		return $ret;
	}
}

?>