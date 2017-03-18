<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddAddressServ extends BaseAction{
	public function action(){
		$user = UserAgent::getUser();
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		
		$name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
		$mobile = isset($_REQUEST['mobile'])?trim($_REQUEST['mobile']):"";
		$detail_address = isset($_REQUEST['detail_address'])?trim($_REQUEST['detail_address']):"";
		$is_default = isset($_REQUEST['is_default'])?trim($_REQUEST['is_default']):0;
		$province = isset($_REQUEST['province'])?trim($_REQUEST['province']):"";
		$city = isset($_REQUEST['city'])?trim($_REQUEST['city']):"";
		$country = isset($_REQUEST['country'])?trim($_REQUEST['country']):"";
		
		FileUtil::requireService('AddressServ');
		$service = new AddressServ();
		
		if(empty($name)){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写收货人姓名";
			return $ret;
		}
		if(empty($mobile)){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写手机号码";
			return $ret;
		}
		$mobile_reg ='/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/';
		if(!preg_match($mobile_reg,$mobile)) {
		 	$ret['ret'] = 0;
			$ret['msg'] = "手机号格式不正确";
			return $ret;
		}
		if(empty($detail_address)){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写详细地址";
			return $ret;
		}
		if(empty($province)){
			$ret['ret'] = 0;
			$ret['msg'] = "请选择所在城市";
			return $ret;
		}
		if(empty($city)){
			$ret['ret'] = 0;
			$ret['msg'] = "请选择地级市";
			return $ret;
		}
		if(empty($country)){
			$ret['ret'] = 0;
			$ret['msg'] = "请选择市、县级市";
			return $ret;
		}
		if($is_default == 1){
			$address['is_default'] = $is_default;
			
			$callback = $service->unsetAddressDefault($user['mid']);	
			if($callback === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
				return $ret;
			}
		}
	
		$address['name'] = $name;
		$address['mobile'] = $mobile;
		$address['detail_address'] = $detail_address;
		$address['province'] = $province;
		$address['city'] = $city;	
		$address['country'] = $country;	
					
		$callback = $service->addAddress($user['mid'],$address);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}
		
		if($callback){
			$ret['ret'] = 1;
			$ret['msg'] = "添加成功";
			return $ret;
		}
	}
}