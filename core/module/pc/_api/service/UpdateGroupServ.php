<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdateGroupServ extends BaseAction{
	
	public function action(){
		$ret=array();
		$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
		$name=isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
		$price=isset($_REQUEST['price'])?trim($_REQUEST['price']):0;
		$skuids=isset($_REQUEST['skuids'])?trim($_REQUEST['skuids']):"";
		if($id<=0||!Common::isInteger($id)){
			$ret['ret']=0;
			$ret['msg']="参数错误";
			return $ret;
		}
		if($price<=0){
			$ret['ret']=0;
			$ret['msg']="参数错误";
			return $ret;
		}
		FileUtil::requireService("GroupServ");
		$service=new GroupServ();
		$callback=$service->updateGroup($name,$price,$skuids,$id);
		if($callback===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误,请稍后再试";
			return $ret;
		}		
		$ret['ret']=1;
		$ret['msg']="更新成功";
		return $ret;
	}
}
?>