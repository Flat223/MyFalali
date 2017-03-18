<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SaveShopAddressServ extends BaseAction{
	public function action(){
		$user = UserAgent::getUser();
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		if($user['type'] != 3){
			$ret['ret'] = 0;
			$ret['msg'] = "你的身份不是供应商";
			return $ret;
		}
		
		FileUtil::requireService("ShopServ");
		$service=new ShopServ();
		$shop = $service->getShopByMid($user['mid']);
		if($shop === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误0，请稍后再试";
			return $ret;
		}
		if(empty($shop)){
			$ret['ret'] = 0;
			$ret['msg'] = "未找到你的店铺";
			return $ret;
		} 	
		
		$type = isset($_REQUEST['type'])?trim($_REQUEST['type']):"";	
		$aid = isset($_REQUEST['aid'])?trim($_REQUEST['aid']):"";	
		$name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
		$mobile = isset($_REQUEST['mobile'])?trim($_REQUEST['mobile']):"";
		$detail_address = isset($_REQUEST['detail_address'])?trim($_REQUEST['detail_address']):"";
		$is_default = isset($_REQUEST['is_default'])?trim($_REQUEST['is_default']):0;
		$province = isset($_REQUEST['province'])?trim($_REQUEST['province']):"";
		$city = isset($_REQUEST['city'])?trim($_REQUEST['city']):"";
		$country = isset($_REQUEST['country'])?trim($_REQUEST['country']):"";
		
		if(empty($type)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数1";
			return $ret;
		}
		if($type != 1 && $type != 2){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误1";
			return $ret;
		}
		if($type == 2 && (empty($aid) || !Common::isInteger($aid) || $aid <= 0)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数2";
			return $ret;
		}
		
		FileUtil::requireService('ShopAddressServ');
		$service = new ShopAddressServ();
		if($type == 2){
			$pre_address = $service->getSimpleShopAddressById($shop['sid'],$aid);
			if($pre_address === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
				return $ret;
			}
			if(empty($pre_address)){
				$ret['ret'] = 0;
				$ret['msg'] = "未找到该地址";
				return $ret;
			}
		}
		
		if(empty($name)){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写联系人姓名";
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
			$ret['msg'] = "请填写所在街道地址";
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
			if($type == 1 || ($type == 2 && $pre_address['is_default'] == 0)){
				$callback = $service->unsetShopAddressDefault($shop['sid']);	
				if($callback === false){
					$ret['ret'] = 0;
					$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
					return $ret;
				}	
			}
		}
	
		$address['aid'] = $aid;
		$address['name'] = $name;
		$address['mobile'] = $mobile;
		$address['detail_address'] = $detail_address;
		$address['province'] = $province;
		$address['city'] = $city;	
		$address['country'] = $country;	
		$address['is_default'] = $is_default;
		
		if($type == 1){
			$callback = $service->addShopAddress($shop['sid'],$address);
			if($callback === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
				return $ret;
			}
		} else {
			$callback = $service->updateShopAddress($shop['sid'],$aid,$address);
			if($callback === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误4，请稍后再试";
				return $ret;
			}
		}
		
		
		$ret['ret'] = 1;
		$ret['msg'] = "保存成功";
		return $ret;
	}
}