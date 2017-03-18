<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

$__param1 = $_REQUEST['__param1'];
$__param2 = $_REQUEST['__param2'];
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {
	
	//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	$_REQUEST['__param1'] = $__param1;
	$_REQUEST['__param2'] = $__param2;
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号

// 	$out_trade_no = $_POST['out_trade_no'];

	//支付宝交易号

// 	$trade_no = $_POST['trade_no'];

	//交易状态
// 	$trade_status = $_POST['trade_status'];


    if($_POST['trade_status'] == 'TRADE_FINISHED') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//付款完成后，支付宝系统发送该交易状态通知

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        $out_trade_no1 = $_POST['out_trade_no'];
        FileUtil::requireService("OrderServ");
       	$user=UserAgent::getUser();
		$mid=$user['mid'];
		$codelist=explode(',', $out_trade_no1);
        $tcallback=false;
        $otype=0;
        $orderService = new OrderServ();
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
					echo "fail";
					exit(0);
				}
				if($order === null){
					echo "success";
					exit(0);
				}
				if($order['state'] != 1){
					echo "success";
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
					echo "fail";
					exit(0);
				}else{
					$tcallback=true;
				}
			}else if($or[1]==2){
				$otype=2;
				$order=$orderService->getVipOrderDetail($out_trade_no);
				if($order === false){
					echo "fail";
					exit(0);
				}
				if($order === null){
					echo "success";
					exit(0);
				}
				if($order['state'] != 1){
					echo "success";
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
					echo "fail";
					exit(0);
				}else{
					$tcallback=true;	
					$otype=2;
				}
			}
        }
        if(!$tcallback){
			echo "fail";
			exit(0);
		}		
		
/*
		$orderService = new OrderServ();
		$or=explode('-',$out_trade_no1);
		if(count($or)<2){
			echo "fail";
			exit(0);	
		}
		$out_trade_no=$or[0];
		if($or[1]==1){
			$order = $orderService->getOrderDetailByCode($out_trade_no);
			if($order === false){
				echo "fail";
				exit(0);
			}
			if($order === null){
				echo "success";
				exit(0);
			}
			if($order['state'] != 1){
				echo "success";
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
				echo "fail";
				exit(0);
			}
		}else if($or[1]==2){
			$order=$orderService->getVipOrderDetail($out_trade_no);
			if($order === false){
				echo "fail";
				exit(0);
			}
			if($order === null){
				echo "success";
				exit(0);
			}
			if($order['state'] != 1){
				echo "success";
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
				echo "fail";
				exit(0);
			}
		} 		
*/

    }

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
	echo "success";		//请不要修改或删除
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>