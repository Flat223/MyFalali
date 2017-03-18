<?php
	$style = isset($params['style'])?$params['style']:'';
	$path = $_SERVER['DOCUMENT_ROOT'].'/html/pc/_topbar.html';
    $user = UserAgent::getUser();
    FileUtil::requireService("PersonalCountServ");
    $serv = new PersonalCountServ();
    $msg = $serv->getUserNoReadMessageNumById($user['mid']);
	FileUtil::loadHtml2($path,array('style'=>$style,'message'=>$msg));