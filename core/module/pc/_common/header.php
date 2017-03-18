<?php
	$path = $_SERVER['DOCUMENT_ROOT'].'/html/pc/_header.html';
    FileUtil::requireService('ProductServ');
    $serv = new ProductServ();
    $user = UserAgent::getUser();
    if(!empty($user)){
        $pn = $serv->getCartProducts($user['mid']);
    }else{
        $pn = 0;
    }

	FileUtil::loadHtml2($path,array('pn'=>$pn));
