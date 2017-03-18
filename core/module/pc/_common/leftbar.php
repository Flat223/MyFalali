<?php
	$substyle = isset($params['substyle'])?$params['substyle']:'';
	$path = $_SERVER['DOCUMENT_ROOT'].'/html/pc/_leftbar.html';
	
	$user = UserAgent::getUser();
	FileUtil::requireService("ShopServ");
    FileUtil::requireService("PersonalCountServ");
    $serv = new PersonalCountServ();
	if($user['type'] != 3){
		$is_have_shop = false;
	} else {
		$service=new ShopServ();
		$shop = $service->getShopByMid($user['mid']);
		if($shop === false){
			FileUtil::load404Html();
			exit(0);
		}
		if($shop == null){
			$is_have_shop = false;
		} else {
			$is_have_shop = true;
		}
	}
    $msg = $serv->getUserNoReadMessageNumById($user['mid']);
    $porder = $serv->getUserPersonalOrderNumById($user['mid']);
    $no = $serv->getUserNeedOrderNumById($user['mid']);
    $so = $serv->getUserShopOrderNumById($user['mid']);

	FileUtil::loadHtml2($path,array('no'=>$no,'substyle'=>$substyle,'so'=>$so,'is_have_shop'=>$is_have_shop,'msg'=>$msg,'po'=>$porder));