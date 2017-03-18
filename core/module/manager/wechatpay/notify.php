<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "lib/WxPay.Api.php";
require_once 'lib/WxPay.Notify.php';
require_once 'unit/log.php';

//初始化日志
$logHandler= new CLogFileHandler(dirname(__FILE__)."/logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
// 		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
// 		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		} 
		$out_trade_no = $data['out_trade_no'];
		FileUtil::requireService("OrderServ");
		$orderService = new OrderServ();
		$order = $orderService->getOrderByOrderCode($out_trade_no);
		if($order === false){
			return false;
		}
		if($order === null){
			return true;
		}
		if($order['order_status'] != 1){
			return true;
		}
		$callback = false;
		if($order['type'] == 1 || $order['type'] == 2){
			$callback = $orderService->recharge($order,2);
		}else if($order['type'] == 3 || $order['type'] == 4){
			$callback = $orderService->finishTuoguan($order,2);
		}else{
			$callback = true;
		}
		if(!$callback){
			return false;
		}
		return true;
	}
}

$notify = new PayNotifyCallBack();
// Log::DEBUG("begin notify2".json_encode($_REQUEST));
$notify->Handle(false);
