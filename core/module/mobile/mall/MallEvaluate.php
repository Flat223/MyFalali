<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class MallEvaluate extends BaseAction{

    public function action(){
		$pid=isset($_REQUEST['pid'])?trim($_REQUEST['pid']):"";
		FileUtil::requireService("GoodsServ");
		$servcie=new GoodsServ();
		$comment=$servcie->getProductComment($pid);
		if($comment===false){
			FileUtil::load404Html();
			exit(0);
		}
		foreach($comment as $key=>$sing){
			$comment[$key]['time']=date('Y-m-d H:i:s',$sing['time']);
		}
		$params=array();
		$params['comment']=$comment;
		$params['count']=count($comment);
        return $params;
    }

}