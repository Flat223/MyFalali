<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "lib/WxPay.Api.php";
require_once "unit/WxPay.NativePay.php";
require_once 'unit/log.php';

$userAgent = new UserAgent();
if(!$userAgent->isLogin()){
	$uri = $_SERVER['REQUEST_URI'];
	$post_params = file_get_contents("php://input");
	if($post_params !== ''){
		if(strpos($uri, '?') !== false){
			$uri .= '&'.$post_params;
		}else{
			$uri .= '?'.$post_params;
		}
	}
	$location = "http://".$_SERVER['HTTP_HOST']."/member/login.html?uri=http://".$_SERVER['HTTP_HOST'].urlencode($uri);
	header("location:".$location);
	exit(0);
}
$ordercodes = isset($_REQUEST['ordercodes'])?$_REQUEST['ordercodes']:"";
$fileUtil = new FileUtil();
if($ordercodes == ""){
	$fileUtil->loadHtml("order_err.html");
	exit(0);
}
$orderids = explode(",", $ordercodes);
$userinfo = $userAgent->getUserInfo();
$totFee = 0;
$temaiAgent = new TemaiUtilAgent();
foreach($orderids as $orderid){
	$order = $temaiAgent->getTemaiOrderByCode($orderid,$userinfo['uid']);
	if(!$order){
		$fileUtil->loadHtml("order_err.html");
		exit(0);
	}
	$totFee += floatval($order['total_price']);
}

//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$notify = new NativePay();
$input = new WxPayUnifiedOrder();
$input->SetBody("订单号：".$ordercodes);
$input->SetAttach($ordercodes);
$input->SetOut_trade_no($ordercodes);
$input->SetTotal_fee("".($totFee*100));
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 60));
$input->SetGoods_tag("ordercode");
$input->SetNotify_url('http://'.$_SERVER['HTTP_HOST'].'/wechatpay/notify.html');
$input->SetTrade_type("NATIVE");
$input->SetProduct_id($ordercodes);
$result = $notify->GetPayUrl($input);
if(!isset($result["code_url"])){
	$fileUtil->loadHtml("order_err.html");
	exit(0);
}
$url2 = $result["code_url"];

$params = array();
$params['ordercodes'] = $ordercodes;
$params['totfee'] = $totFee;
$params['url'] = $url2;
$fileUtil->loadHtml("weixin.html",$params);
