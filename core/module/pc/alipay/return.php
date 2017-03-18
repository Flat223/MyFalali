<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
$__param1 = $_REQUEST['__param1'];
$__param2 = $_REQUEST['__param2'];
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

	//商户订单号
	$_REQUEST['__param1'] = $__param1;
	$_REQUEST['__param2'] = $__param2;
// 	$out_trade_no = $_GET['out_trade_no'];

	//支付宝交易号

// 	$trade_no = $_GET['trade_no'];

	//交易状态
// 	$trade_status = $_GET['trade_status'];


    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//如果有做过处理，不执行商户的业务程序
		$out_trade_no1 = $_GET['out_trade_no'];
		$user=UserAgent::getUser();
		$mid=$user['mid'];
        FileUtil::requireService("OrderServ");
        $orderService = new OrderServ();
        $codelist=explode(',', $out_trade_no1);
        $codelist1=array();
        for($a=0;$a<count($codelist);$a++){
			$o=explode('-',$codelist[$a]);
			$codelist1[$a]=$o[0];
        }
        $codel=implode(',', $codelist1);
        $tcallback=false;
        $otype=0;
        foreach($codelist as $code){
	       	$or=explode('-',$code);
		   	if(count($or)<2){
		   		echo "fail";
		   		exit(0);	
			}
			$out_trade_no=$or[0];
			if($or[1]==1){
				$otype=1;
				$order = $orderService->getOrderDetailByCode($out_trade_no);
				if($order === false){
					FileUtil::loadServerErrHtml();
					exit(0);
				}
				if($order === null){
					$params = array();
					$params['msg'] = "未找到订单信息";
					$params['type'] = 'user';
					$params['sidebar'] = 'myinfo';
					FileUtil::loadHtml("order_err.html",$params);
					exit(0);
				}
				$id=$order['couponid'];
				$money=0;
				$money=$order['tot_fee'];
				if($order['couponid']>0){
					$money=$order['dis_fee'];
				}
				$point=0;
				if($money>1){
					$point=(int)$money;
				}
				$callback = false;
				$callback=$orderService->recharge($out_trade_no,1,$mid,$point,$id);
				if(!$callback){
					FileUtil::loadServerErrHtml();
					exit(0);
				}
				if($order['state'] != 1){
					$tcallback=true;
				}else {
					FileUtil::loadServerErrHtml();
					exit(0);
				}
				
			}else if($or[1]==2){
				$otype=2;
				$order = $orderService->getVipOrderDetail($out_trade_no);
				if($order === false){
					FileUtil::loadServerErrHtml();
					exit(0);
				}
				if($order === null){
					$params = array();
					$params['msg'] = "未找到订单信息";
					$params['type'] = 'user';
					$params['sidebar'] = 'myinfo';
					FileUtil::loadHtml("order_err.html",$params);
					exit(0);
				}
				$point=0;
				$money=$order['pay_price'];
				if($money>1){
					$point=(int)$money;
				}
				$callback = false;
				$rtype=$order['viptype'];//会员类型 1 月 2季度 3年
				$isvip=$user['is_vip'];//是否是会员 1是 2不是
				$callback=$orderService->Viprecharge($out_trade_no,1,$mid,$point,$rtype,$isvip);
				if(!$callback){
					FileUtil::loadServerErrHtml();
					exit(0);
				}else {
					$tcallback=true;
					$otype=2;
				}
			}	
        }
        if($tcallback==true){
	        $_SESSION['paysuc'] = 1;
			header('Location: '.'http://'.$_SERVER['HTTP_HOST'].'/pay/success.html?ordercode='.$codel."&type=".$otype);
			exit(0);
		}
/*
		$_SESSION['paysuc'] = 1;
		header('Location: '.'http://'.$_SERVER['HTTP_HOST'].'/pay/success.html?ordercode='.$codel."&type=".$otype);
		exit(0);	
*/

        
		
/*
        $or=explode('-',$out_trade_no1);
        if(count($or)<2){
			echo "failx";
			exit(0);	
		}
		$out_trade_no=$or[0];
		if($or[1]==1){
			$order = $orderService->getOrderDetailByCode($out_trade_no);
			if($order === false){
				FileUtil::loadServerErrHtml();
				exit(0);
			}
			if($order === null){
				$params = array();
				$params['msg'] = "未找到订单信息";
				$params['type'] = 'user';
				$params['sidebar'] = 'myinfo';
				FileUtil::loadHtml("order_err.html",$params);
				exit(0);
			}
			$id=$order['couponid'];
			$money=0;
			$money=$order['tot_fee'];
			if($order['couponid']>0){
				$money=$order['dis_fee'];
			}
			$point=0;
			if($money>1){
				$point=(int)$money;
			}
			$callback = false;
			$callback=$orderService->recharge($out_trade_no,1,$mid,$point,$id);
			if(!$callback){
				FileUtil::loadServerErrHtml();
				exit(0);
			}
			if($order['state'] != 1){
				$_SESSION['paysuc'] = 1;
				header('Location: '.'http://'.$_SERVER['HTTP_HOST'].'/pay/success.html?ordercode='.$out_trade_no."&type=1");
				exit(0);
			}
			$_SESSION['paysuc'] = 1;
			header('Location: '.'http://'.$_SERVER['HTTP_HOST'].'/pay/success.html?ordercode='.$out_trade_no."&type=1");
			exit(0);	
		}else if($or[1]==2){
			$order = $orderService->getVipOrderDetail($out_trade_no);
			if($order === false){
				FileUtil::loadServerErrHtml();
				exit(0);
			}
			if($order === null){
				$params = array();
				$params['msg'] = "未找到订单信息";
				$params['type'] = 'user';
				$params['sidebar'] = 'myinfo';
				FileUtil::loadHtml("order_err.html",$params);
				exit(0);
			}
			$point=0;
			$money=$order['pay_price'];
			if($money>1){
				$point=(int)$money;
			}
			$callback = false;
			$rtype=$order['viptype'];//会员类型 1 月 2季度 3年
			$isvip=$user['is_vip'];//是否是会员 1是 2不是
			$callback=$orderService->Viprecharge($out_trade_no,1,$mid,$point,$rtype,$isvip);
			if(!$callback){
				echo "faila";
				exit(0);
			}
			if($order['state'] != 1){
				$_SESSION['paysuc'] = 1;
				header('Location: '.'http://'.$_SERVER['HTTP_HOST'].'/pay/success.html?ordercode='.$out_trade_no."&type=2");
				exit(0);
			}
			$_SESSION['paysuc'] = 1;
			header('Location: '.'http://'.$_SERVER['HTTP_HOST'].'/pay/success.html?ordercode='.$out_trade_no."&type=2");
			exit(0);	
		} 
*/
    }
    else {
      echo "trade_status=".$_GET['trade_status'];
    }
		
	

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
    echo "验证失败";
    echo(json_encode($_REQUEST));
}
?>
        <title>支付宝即时到账交易接口</title>
	</head>
    <body>
    </body>
</html>