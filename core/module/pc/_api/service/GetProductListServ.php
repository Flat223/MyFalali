<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetProductListServ extends BaseAction{
	
	public function action(){
		$brandid=isset($_REQUEST['brandid'])?trim($_REQUEST['brandid']]):0;
		$ptid=isset($_REQUEST['ptid'])?trim($_REQUEST['ptid']):0;
		$leftprice=isset($_REQUEST['leftprice'])?trim($_REQUEST['leftprice']):0;
		$rightprice=isset($_REQUEST['rightprice'])?trim($_REQUEST['rightprice']):999999;
		$sort=isset($_REQUEST['sort'])?trim($_REQUEST['sort']):0;
		
	}
}