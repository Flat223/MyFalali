<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ReviewProductServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		
		$pid = isset($_REQUEST['pid'])?trim($_REQUEST['pid']):"";
		$order_code = isset($_REQUEST['order_code'])?trim($_REQUEST['order_code']):"";
		$comment = isset($_REQUEST['comment'])?trim($_REQUEST['comment']):"";
		$star = isset($_REQUEST['star'])?trim($_REQUEST['star']):"";
		$commentImg = isset($_REQUEST['commentImg'])?trim($_REQUEST['commentImg']):"";
		
		if(empty($pid) || empty($order_code)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}
		
		if($comment == "" || $star == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "评论信息不完善";
			return $ret;
		}
		
		FileUtil::requireService("ProductServ");	
		$proService=new ProductServ();
		$product = $proService->getSingleProduct($pid);
		if($product === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
		if($product == null){
			$ret['ret'] = 0;
			$ret['msg'] = "该产品不存在";
			return $ret;
		}
		
		FileUtil::requireService("OrderServ");	
		$service=new OrderServ();		
		$callback = $service->reviewProduct($user['mid'],$pid,$product['sid'],$star,$comment,$commentImg);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}
		
		$callback = $proService->updateProCommentNum($pid);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
			return $ret;
		}
		
		$callback = $service->updateOrderState($order_code,5);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误4，请稍后再试";
			return $ret;
		}

		$ret = array();
		$ret['ret'] = 1;
		$ret['msg'] = "评论成功"; 
		return $ret;
	}
}

?>