<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetCityTownServ extends BaseAction{
	
	public function action(){
		$id=isset($_REQUEST['aid'])?trim($_REQUEST['aid']):0;
		FileUtil::requireService("GoodsServ");
		$service=new GoodsServ();
		$city=$service->getCityTown($id);
		
		$ret=array();
		$ret['ret']=1;
		$ret['msg']="获取成功";
		$ret['data']=$city;
		return $ret;
	}
}

?>