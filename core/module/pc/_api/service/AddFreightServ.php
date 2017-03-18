<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddFreightServ extends BaseAction{
	
	private $deliveryTimes = array(4,8,12,16,20,24,48,72);	//发货时间段
	private $units = array(1,2,3);	//计价方式，1按件数，2按重量，3按体积
	private $unitName = array('件','重','体积');
	
    public function action(){
        $ret = array();
        $user = UserAgent::getUser();
		if($user == null){
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
		$shopService = new ShopServ();
		$shop = $shopService->getShopById($user['mid']);
		if($shop === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，发生错误";
			return $ret;
		}
		if(!$shop){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，未找到您的店铺";
			return $ret;
		}
		$freight = array();
		$freight['sid'] = $shop['sid'];
		$name = isset($_POST['name'])?trim($_POST['name']):'';
		$countyId = isset($_POST['countyId'])?$_POST['countyId']:0;
		$deliveryTime = isset($_POST['deliveryTime'])?$_POST['deliveryTime']:0;
		$type = isset($_POST['type'])?$_POST['type']:0;
		$unit = isset($_POST['unit'])?$_POST['unit']:0;
		$areaLimit = isset($_POST['areaLimit'])?$_POST['areaLimit']:0;
		$express = isset($_POST['express'])?$_POST['express']:'';
		$ems = isset($_POST['ems'])?$_POST['ems']:'';
		$mail = isset($_POST['mail'])?$_POST['mail']:'';
		if($name == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写模板名称";
			return $ret;
		}
		if(strlen($name) > 50){
			$ret['ret'] = 0;
			$ret['msg'] = "模板名称不超过50个字符";
			return $ret;
		}
		FileUtil::requireService("FreightServ");
		$freightService = new FreightServ();
		$freight2 = $freightService->getFreightByNameAndSid($name,$shop['sid']);
		if($freight2 === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，发生错误";
			return $ret;
		}
		if($freight2){
			$ret['ret'] = 0;
			$ret['msg'] = "模板名称已存在";
			return $ret;
		}
		$freight['name'] = $name;
		if(!Common::isInteger($countyId) || $countyId <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "发货地址不正确";
			return $ret;
		}
		$countyId = intval($countyId);
		FileUtil::requireService("AreaServ");
		$areaService = new AreaServ();
		$county = $areaService->getAreaById($countyId);
		if($county === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，发生错误";
			return $ret;
		}
		if(!$county){
			$ret['ret'] = 0;
			$ret['msg'] = "发货地址不存在";
			return $ret;
		}
		if($county['category'] != 3){
			$ret['ret'] = 0;
			$ret['msg'] = "请选择正确的发货地址";
			return $ret;
		}
		$city = $areaService->getAreaById($county['parent_id']);
		if($city === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，发生错误";
			return $ret;
		}
		if(!$city){
			$ret['ret'] = 0;
			$ret['msg'] = "无效的发货地址";
			return $ret;
		}
		$province = $areaService->getAreaById($city['parent_id']);
		if($province === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，发生错误";
			return $ret;
		}
		if(!$province){
			$ret['ret'] = 0;
			$ret['msg'] = "无效的发货地址";
			return $ret;
		}
		$freight['province'] = $province['name'];
		$freight['city'] = $city['name'];
		$freight['county'] = $county['name'];
		$freight['countyid'] = $countyId;
		if(!Common::isInteger($deliveryTime) || $deliveryTime <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "请选择发货时间";
			return $ret;
		}
		$deliveryTime = intval($deliveryTime);
		if(!in_array($deliveryTime, $this->deliveryTimes)){
			$ret['ret'] = 0;
			$ret['msg'] = "请选择正确的发货时间";
			return $ret;
		}
		$freight['delivery_time_section'] = $deliveryTime*3600;
		$freight['time'] = time();
		$freight['status'] = 1;
		if($type == '1'){
			$freight['type'] = 1;
			$freight['areaLimit'] = 0;
			$freight['unit'] = 1;
			$freight['express_flag'] = 0;
			$freight['express'] = '';
			$freight['ems_flag'] = 0;
			$freight['ems'] = '';
			$freight['mail_flag'] = 0;
			$freight['mail'] = '';
		}else if($type == '2'){
			$freight['type'] = 2;
			if(!Common::isInteger($unit) || $unit <= 0){
				$ret['ret'] = 0;
				$ret['msg'] = "请选择计价方式";
				return $ret;
			}
			$unit = intval($unit);
			if(!in_array($unit, $this->units)){
				$ret['ret'] = 0;
				$ret['msg'] = "计价方式不正确";
				return $ret;
			}
			$freight['unit'] = $unit;
			if(!Common::isInteger($areaLimit) || ($areaLimit != 0 && $areaLimit != 1)){
				$ret['ret'] = 0;
				$ret['msg'] = "请选择是否支持区域限售";
				return $ret;
			}
			$freight['areaLimit'] = $areaLimit;
			$cities = $areaService->getCities();
			if($cities === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，发生错误";
				return $ret;
			}
			$cityIds = array();
			foreach($cities as $val){
				$cityIds[] = $val['id'];
			}
			$deliveryTypeNum = 0;
			if($express == ""){
				$ret['ret'] = 0;
				$ret['msg'] = "运送方式数据有错误";
				return $ret;
			}
			$expressData = json_decode($express,true);
			$result = $this->validateDeliveryOption($expressData,"快递",$unit,$cityIds);
			if($result['ret'] == 0){
				$ret['ret'] = 0;
				$ret['msg'] = $result['msg'];
				return $ret;
			}
			if($result['flag'] == 1){
				$freight['express_flag'] = 1;
				$freight['express'] = $result['data'];
				$deliveryTypeNum++;
			}else{
				$freight['express_flag'] = 0;
				$freight['express'] = "";
			}
			if($ems == ""){
				$ret['ret'] = 0;
				$ret['msg'] = "运送方式数据有错误";
				return $ret;
			}
			$emsData = json_decode($ems,true);
			$result = $this->validateDeliveryOption($emsData,"EMS",$unit,$cityIds);
			if($result['ret'] == 0){
				$ret['ret'] = 0;
				$ret['msg'] = $result['msg'];
				return $ret;
			}
			if($result['flag'] == 1){
				$freight['ems_flag'] = 1;
				$freight['ems'] = $result['data'];
				$deliveryTypeNum++;
			}else{
				$freight['ems_flag'] = 0;
				$freight['ems'] = "";
			}
			if($mail == ""){
				$ret['ret'] = 0;
				$ret['msg'] = "运送方式数据有错误";
				return $ret;
			}
			$mailData = json_decode($mail,true);
			$result = $this->validateDeliveryOption($mailData,"平邮",$unit,$cityIds);
			if($result['ret'] == 0){
				$ret['ret'] = 0;
				$ret['msg'] = $result['msg'];
				return $ret;
			}
			if($result['flag'] == 1){
				$freight['mail_flag'] = 1;
				$freight['mail'] = $result['data'];
				$deliveryTypeNum++;
			}else{
				$freight['mail_flag'] = 0;
				$freight['mail'] = "";
			}
			if($deliveryTypeNum <= 0){
				$ret['ret'] = 0;
				$ret['msg'] = "请选择至少一种运送方式";
				return $ret;
			}
		}else{
			$ret['ret'] = 0;
			$ret['msg'] = "无效的运费类型";
			return $ret;
		}
		$result = $freightService->saveFreight($freight);
        if($result){
	       $ret['ret'] = 1;
		   $ret['msg'] = "保存成功";
		   return $ret;
        }
        $ret['ret'] = 0;
        $ret['msg'] = "抱歉，发生错误";
        return $ret; 
    }
    
    private function validateDeliveryOption($deliveryData,$deliveryName,$unit,$cityIds){
	    $ret = array();
	    if(!$deliveryData){
			$ret['ret'] = 0;
			$ret['msg'] = "运送方式数据有错误";
			return $ret;
		}
		if(!isset($deliveryData['selected']) || ($deliveryData['selected'] != 0 && $deliveryData['selected'] != 1)){
			$ret['ret'] = 0;
			$ret['msg'] = "运送方式数据有错误";
			return $ret;
		}
		if($deliveryData['selected'] == 0){
			$ret['ret'] = 1;
			$ret['flag'] = 0;
			return $ret;
		}else{
			$ret['flag'] = 1;
			if(!isset($deliveryData['defaultVals'])){
				$ret['ret'] = 0;
				$ret['msg'] = "请设置".$deliveryName."默认运费";
				return $ret;
			}
			$saveDelivery = array();
			$defaultVals = $deliveryData['defaultVals'];
			$val1 = isset($defaultVals['val1'])?trim($defaultVals['val1']):'';
			$val2 = isset($defaultVals['val2'])?trim($defaultVals['val2']):'';
			$val3 = isset($defaultVals['val3'])?trim($defaultVals['val3']):'';
			$val4 = isset($defaultVals['val4'])?trim($defaultVals['val4']):'';
			$result = $this->validateFreight($val1,$val2,$val3,$val4,true,$deliveryName,$unit);
			if($result['ret'] == 0){
				$ret['ret'] = 0;
				$ret['msg'] = $result['msg'];
				return $ret;
			}
			$saveDelivery['defaultVals'] = array();
			$saveDelivery['defaultVals']['val1'] = round(floatval($val1),3);
			$saveDelivery['defaultVals']['val2'] = round(floatval($val2),2);
			$saveDelivery['defaultVals']['val3'] = round(floatval($val3),3);
			$saveDelivery['defaultVals']['val4'] = round(floatval($val4),2);
			if(isset($deliveryData['options'])){
				if(!is_array($deliveryData['options'])){
					$ret['ret'] = 0;
					$ret['msg'] = $deliveryName."设置运费的参数设置错误";
					return $ret;
				}
				$saveDelivery['options'] = array();
				$addCityIds = array();
				foreach($deliveryData['options'] as $val){
					$optVal1 = isset($val['val1'])?trim($val['val1']):'';
					$optVal2 = isset($val['val2'])?trim($val['val2']):'';
					$optVal3 = isset($val['val3'])?trim($val['val3']):'';
					$optVal4 = isset($val['val4'])?trim($val['val4']):'';
					$optCities = isset($val['cities'])?trim($val['cities']):'';
					if($optCities == ""){
						$ret['ret'] = 0;
						$ret['msg'] = $deliveryName."有设置项未添加地区";
						return $ret;
					}
					$optCityIds = explode(",", $optCities);
					foreach($optCityIds as $optCityId){
						if(!in_array($optCityId, $cityIds)){
							$ret['ret'] = 0;
							$ret['msg'] = $deliveryName."有设置项的地区添加中有不存在的城市地区";
							return $ret;
						}
						if(in_array($optCityId, $addCityIds)){
							$ret['ret'] = 0;
							$ret['msg'] = "有设置项的地区添加中有重复添加的城市";
							return $ret;
						}
						$addCityIds[] = $optCityId;	
					}
					$option = array();
					$option['cities'] = $optCities;
					$result = $this->validateFreight($optVal1,$optVal2,$optVal3,$optVal4,false,$deliveryName,$unit);
					if($result['ret'] == 0){
						$ret['ret'] = 0;
						$ret['msg'] = $result['msg'];
						return $ret;
					}
					$option['val1'] = round(floatval($optVal1),3);
					$option['val2'] = round(floatval($optVal2),2);
					$option['val3'] = round(floatval($optVal3),3);
					$option['val4'] = round(floatval($optVal4),2);
					$saveDelivery['options'][] = $option;
				}	
			}
			$ret['ret'] = 1;
			$ret['data'] = json_encode($saveDelivery);
			return $ret;
		} 
    }
    
    
    
    
    private function validateFreight($val1,$val2,$val3,$val4,$isDefault,$deliveryName,$unit){
	    $ret = array();
	    if($val1 == ""){
			if($isDefault){
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的默认运费的首".$this->unitName[$unit-1]."不可为空！";
			}else{
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的指定地区的首".$this->unitName[$unit-1]."不可为空！";
			}
			return $ret;
		}
		if($unit == 1){
			if(!Common::isPositiveInt($val1)){
				if($isDefault){
					$ret['ret'] = 0;
					$ret['msg'] = $deliveryName."的默认运费的首".$this->unitName[$unit-1]."必须是大于0的整数！";
				}else{
					$ret['ret'] = 0;
					$ret['msg'] = $deliveryName."的指定地区的首".$this->unitName[$unit-1]."必须是大于0的整数！";
				}
				return $ret;
			}
		}else{
			if(!Common::isPostiveFloat($val1)){
				if($isDefault){
					$ret['ret'] = 0;
					$ret['msg'] = $deliveryName."的默认运费的首".$this->unitName[$unit-1]."必须是大于0的数字！";
				}else{
					$ret['ret'] = 0;
					$ret['msg'] = $deliveryName."的指定地区的首".$this->unitName[$unit-1]."必须是大于0的数字！";
				}
				return $ret;
			}
		}
		if($val2 == ""){
			if($isDefault){
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的默认运费的首费不可为空！";
			}else{
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的指定地区的首费不可为空！";
			}
			return $ret;
		}
		if(!Common::isNonnegativeFloat($val2)){
			if($isDefault){
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的默认运费的首费必须是大于或等于0的数字！";
			}else{
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的指定地区的首费必须是大于或等于0的数字！";
			}
			return $ret;
		}
		if($val3 == ""){
			if($isDefault){
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的默认运费的续".$this->unitName[$unit-1]."不可为空！";
			}else{
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的指定地区的续".$this->unitName[$unit-1]."不可为空！";
			}
			return $ret;
		}
		if($unit == 1){
			if(!Common::isPositiveInt($val3)){
				if($isDefault){
					$ret['ret'] = 0;
					$ret['msg'] = $deliveryName."的默认运费的续".$this->unitName[$unit-1]."必须是大于0的整数！";
				}else{
					$ret['ret'] = 0;
					$ret['msg'] = $deliveryName."的指定地区的续".$this->unitName[$unit-1]."必须是大于0的整数！";
				}
				return $ret;
			}
		}else{
			if(!Common::isPostiveFloat($val3)){
				if($isDefault){
					$ret['ret'] = 0;
					$ret['msg'] = $deliveryName."的默认运费的续".$this->unitName[$unit-1]."必须是大于0的数字！";
				}else{
					$ret['ret'] = 0;
					$ret['msg'] = $deliveryName."的指定地区的续".$this->unitName[$unit-1]."必须是大于0的数字！";
				}
				return $ret;
			}
		}
		if($val4 == ""){
			if($isDefault){
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的默认运费的续费不可为空！";
			}else{
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的指定地区的续费不可为空！";
			}
			return $ret;
		}
		if(!Common::isNonnegativeFloat($val4)){
			if($isDefault){
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的默认运费的续费必须是大于或等于0的数字！";
			}else{
				$ret['ret'] = 0;
				$ret['msg'] = $deliveryName."的指定地区的续费必须是大于或等于0的数字！";
			}
			return $ret;
		}
		$ret['ret'] = 1;
		return $ret;
    }
    
    
    
    
    
    
    
    
}