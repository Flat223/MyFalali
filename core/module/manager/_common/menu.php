<?php
	$style = isset($params['style'])?$params['style']:'';
	$substyle = isset($params['substyle'])?$params['substyle']:'';
	$path = $_SERVER['DOCUMENT_ROOT'].'/html/manager/_menu.html';
	FileUtil::loadHtml2($path,array('style'=>$style,'substyle'=>$substyle));
?>



