<?php
	$result = handle();
	echo(json_encode($result));
	
	function handle(){
		$ret = array();
		$user = UserAgent::getUser();
		if($user == null){
			$ret['ret'] = 0;
			$ret['msg'] = "未登录";
			return $ret;
		}
		$ordercode = isset($_REQUEST['ordercode'])?$_REQUEST['ordercode']:"";
		if($ordercode == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		FileUtil::requireService("OrderServ");
		$orderService = new OrderServ();
		$order = $orderService->getOrderDetailByCode($ordercode);
		if($order === false){
			$ret['ret'] = -1;
			$ret['msg'] = "服务器错误";
			return $ret;
		}
		if($order === null){
			$ret['ret'] = 0;
			$ret['msg'] = "无此订单";
			return $ret;
		}
		if($order['mid'] != $user['mid']){
			$ret['ret'] = 0;
			$ret['msg'] = "无权操作";
			return $ret;
		}
		if($order['order_status'] == 1){
			$ret['ret'] = 1;
			$ret['msg'] = "未支付";
			return $ret;
		}
		if($order['order_status'] == 2){
			$ret['ret'] = 2;
			$ret['msg'] = "已支付";
			$_SESSION['paysuc'] = 1;
			return $ret;
		}
		$ret['ret'] = 0;
		$ret['msg'] = "未知状态";
		return $ret;	
	}
	