<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetCouponListServ extends BaseAction{
	
	public function action(){
		$ret = array();
		$user = UserAgent::getUser();
		FileUtil::requireService("CouponServ");
		$service = new CouponServ();
		
// 		$user_type = isset($_REQUEST['user_type'])?trim($_REQUEST['user_type']):"";// 1:普通用户 2:企业用户
		$user_type = 1;
		$type = isset($_REQUEST['type'])?trim($_REQUEST['type']):"";
		$use_status = isset($_REQUEST['use_status'])?trim($_REQUEST['use_status']):"";
		
		$mid = $user_type == 1 ? $user['mid'] : $user['bind_company'];
		$couponArray=$service->getCouponList($user['mid'],$type,$use_status);
		if($couponArray === false){
			$ret['ret'] = -1;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		} 
		if($couponArray == null) {
			if($type == 1){
				$title = "优惠券";
			} else{
				$title = "代金券";
			}
			if($use_status == 1){
				$intro = "未使用的";	
			} else if ($use_status == 2){
				$intro = "已使用的";	
			} else {
				$intro = "已过期的";	
			}
			$ret['ret'] = 0;
			$ret['msg'] = "暂无".$intro.$title;
			return $ret;
		}
		for($i=0;$i<count($couponArray);$i++){
			$coupon = $couponArray[$i];
			$start_time = date("Y/m/d",$coupon['start_time']);
			$end_time = date("Y/m/d",$coupon['end_time']);
			$validity = $start_time."-".$end_time;
			$coupon['validity'] = $validity;
			$couponArray[$i] = $coupon;
		}
		$ret['ret'] = 1;
		$ret['msg'] = "获取成功"; 
		$ret['coupon'] = $couponArray;
		return $ret;
	}
}