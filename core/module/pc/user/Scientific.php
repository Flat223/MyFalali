<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Scientific extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user['type'] == 1 && $user['sub_type'] != 0 && $user['bind_status'] == 2){//判断是否属于并绑定了高校
			FileUtil::requireService("CollegeServ");
			$service=new CollegeServ();
			
			$applyRecord=$service->getApplyFundRecord($user['mid'],$user['bind_company']);//获取最近一次申请记录
			if($applyRecord === false){
				FileUtil::load404Html();
				exit(0);
			}
			
			if($applyRecord == null){
				$params['apply_state'] = 0;//没申请过科研基金
			} else {
				$apply_state = $applyRecord['state'];
				$user_fund=$service->getUserFund($user['mid'],$user['bind_company']);
				if($user_fund === false){
					FileUtil::load404Html();
					exit(0);
				}
				
				$is_have_fund = false;
				$research_fund = 0;
				$orderRecord = array();
				if($user_fund != null){
					$is_have_fund = true;
					
					$research_fund = $user_fund['total_fund']-$user_fund['used_money'];
					$research_fund = ($research_fund > 0) ? $research_fund : 0;
					
					$orderRecord=$service->getFundOrderRecord($user['mid'],$user['bind_company']);
					if($orderRecord === false){
						FileUtil::load404Html();
						exit(0);
					}
				}
				
				$params['is_have_fund'] = $is_have_fund;	
				$params['apply_state'] = $apply_state;	
				$params['research_fund'] = $research_fund;	
				$params['orderRecord'] = $orderRecord;
			}	
		}
		
		FileUtil::requireService("CouponServ");
		$service=new CouponServ();
		$couponArray=$service->getCouponList($user['mid'],1,1);
		if($couponArray === false){
			FileUtil::load404Html();
			exit(0);
		}
		$newCouponArray = $this->setNewArray($couponArray);
		$crashArray=$service->getCouponList($user['mid'],2,1);
		if($crashArray === false){
			FileUtil::load404Html();
			exit(0);
		}
		$newCrashArray = $this->setNewArray($crashArray);
		
		$params['style'] = 'user';
		$params['substyle'] = 'scientific';
		$params['coupon'] = $newCouponArray;
		$params['crash'] = $newCrashArray;
		return $params;
	}
	
	function setNewArray($couponArray){
		for($i=0;$i<count($couponArray);$i++){
			$coupon = $couponArray[$i];
			$start_time = date("Y/m/d",$coupon['start_time']);
			$end_time = date("Y/m/d",$coupon['end_time']);
			$validity = $start_time."-".$end_time;
			$coupon['validity'] = $validity;
			$couponArray[$i] = $coupon;
		}
		return $couponArray;
	}
}