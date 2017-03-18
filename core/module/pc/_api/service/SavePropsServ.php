<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SavePropsServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
// 		$maxPropsSize = 3;
		$pid = isset($_REQUEST['pid'])?trim($_REQUEST['pid']):'';
		$props = isset($_REQUEST['props'])?trim($_REQUEST['props']):"";
		
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		if($user['type'] != 3){
			$ret['ret'] = -1;
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
			$ret['msg'] = "请先申请店铺";
			return $ret;
		} 
		
		if(empty($pid)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}
		if(!Common::isInteger($pid) || $pid < 0){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误1";
			return $ret;
		}
		$props = json_decode($props,true);
		if(empty($props) || !is_array($props)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误2";
			return $ret;
		}
/*
		if(count($props) > $maxPropsSize){
			$ret['ret'] = 0;
			$ret['msg'] = "最多只能有".$maxPropsSize."种产品属性";
			return $ret;
		}
*/
		FileUtil::requireService("ProductServ");
		$service = new ProductServ();
		
		$pro_ids = "";
/*
		foreach($props as $prop){
			$propertyid = isset($prop['propertyid'])?trim($prop['propertyid']):"";
			if($pro_ids == ""){
				$pro_ids = $propertyid;
			} else {
				$pro_ids = $pro_ids.",".$propertyid;
			}
		}
		$callback = $service->clearSameProValuesByProids($pid,$pro_ids);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
*/

		foreach($props as $prop){
			$propertyid = isset($prop['propertyid'])?trim($prop['propertyid']):"";
			$propvals = isset($prop['propvals'])?trim($prop['propvals']):"";
			$propvalArray = explode(",", $propvals);
			
			if($pro_ids == ""){
				$pro_ids = $propertyid;
			} else {
				$pro_ids = $pro_ids.",".$propertyid;
			}
			
			foreach($propvalArray as $propval){
				if(strlen($propval) > 100){
					$ret['ret'] = 0;
					$ret['msg'] = "属性值不超过100个字符";
					return $ret;
				}
				$count = $service->getProPropertyValByCon($pid,$propertyid,$propval);
				if($count === false){
					$ret['ret'] = 0;
					$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
					return $ret;
				}
				if($count < 1){
					$callback = $service->insertProPropertyVal($pid,$propertyid,$propval);
					if($callback === false){
						$ret['ret'] = 0;
						$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
						return $ret;
					}
				}
			}
			
			$callback = $service->clearSamePropertyValues($pid,$propertyid,$propvals);
			if(!$callback){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误4，请稍后再试";
				return $ret;
			}
		}
		
		if(empty($pro_ids)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误3";
			return $ret;
		}
		$propertyArray = $service->getPropertyByIds($pro_ids);
		if($propertyArray === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误5，请稍后再试";
			return $ret;
		}
		foreach($propertyArray as &$poperty){
			$poperty['vals'] = $service->getPropertyValByProId($poperty['id'],$pid);
			if($poperty['vals'] === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误6，请稍后再试";
				return $ret;
			}
		}
		
		$ret['ret'] = 1;
		$ret['msg'] = "操作成功";
		$ret['props'] = $propertyArray;
		return $ret;
	}
}

?>