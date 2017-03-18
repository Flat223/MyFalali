<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetLocationFreServ extends BaseAction
{

    public function action()
    {
		$cname=isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
		$pid=isset($_REQUEST['pid'])?trim($_REQUEST['pid']):0;
		FileUtil::requireService("GoodsServ");
		$service=new GoodsServ();
		$area=$service->getCityId($cname);
		$cid=$area['id'];
		$ret=CommonFunc::getProductFreight($cid,$pid);
		$ret['cid']=$cid;
		return $ret;
    }
}
?>