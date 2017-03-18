<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetProductFreightServ extends BaseAction
{
    public function action(){
	    $cityid=isset($_REQUEST['cid'])?trim($_REQUEST['cid']):0;
	    $pid=isset($_REQUEST['pid'])?trim($_REQUEST['pid']):"";
	    $num=isset($_REQUEST['num'])?trim($_REQUEST['num']):1;
	    $ret=CommonFunc::getProductFreight($cityid,$pid,$num);
	    return $ret;
    }
    
}