<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddMyProductServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = "请登录";
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
			$ret['msg'] = "请先申请店铺";
			return $ret;
		} 
		
		$name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
		$ptid = isset($_REQUEST['ptid'])?trim($_REQUEST['ptid']):"";
		$first_tid = isset($_REQUEST['first_tid'])?trim($_REQUEST['first_tid']):"0";
		$second_tid = isset($_REQUEST['second_tid'])?trim($_REQUEST['second_tid']):"0";
		$third_tid = isset($_REQUEST['third_tid'])?trim($_REQUEST['third_tid']):"0";
		$forth_tid = isset($_REQUEST['forth_tid'])?trim($_REQUEST['forth_tid']):"0";
		$fifth_tid = isset($_REQUEST['fifth_tid'])?trim($_REQUEST['fifth_tid']):"0";
		$fre_id = isset($_REQUEST['fre_id'])?trim($_REQUEST['fre_id']):"0";
		$address_id = isset($_REQUEST['address_id'])?trim($_REQUEST['address_id']):"";
		$bName = isset($_REQUEST['brand'])?trim($_REQUEST['brand']):"";//品牌名称
		
		$alias = isset($_REQUEST['proAlias'])?trim($_REQUEST['proAlias']):"";
		$EnglishName = isset($_REQUEST['EnglishName'])?trim($_REQUEST['EnglishName']):"";
		$CASnumber = isset($_REQUEST['CASnumber'])?trim($_REQUEST['CASnumber']):"";
		$goods_code = isset($_REQUEST['goods_code'])?trim($_REQUEST['goods_code']):"";
		$size = isset($_REQUEST['size'])?trim($_REQUEST['size']):"";
		$purity = isset($_REQUEST['purity'])?trim($_REQUEST['purity']):"";
		
		$packing = isset($_REQUEST['packing'])?trim($_REQUEST['packing']):"";
		$unit = isset($_REQUEST['unit'])?trim($_REQUEST['unit']):"";
		$is_harmful = isset($_REQUEST['is_harmful'])?trim($_REQUEST['is_harmful']):"0";
		$shelf_life = isset($_REQUEST['shelf_life'])?trim($_REQUEST['shelf_life']):"";
		$producer = isset($_REQUEST['producer'])?trim($_REQUEST['producer']):"";
		$store = isset($_REQUEST['store'])?trim($_REQUEST['store']):"";
		$traffic = isset($_REQUEST['traffic'])?trim($_REQUEST['traffic']):"";
		
		$video_url = isset($_REQUEST['video_url'])?trim($_REQUEST['video_url']):"";		
		$video_img = isset($_REQUEST['video_img'])?trim($_REQUEST['video_img']):"";
		$imageUrl = isset($_REQUEST['imageUrl'])?trim($_REQUEST['imageUrl']):"";		
		$intro = isset($_REQUEST['intro'])?trim($_REQUEST['intro']):"";		
		$intro_mobile = isset($_REQUEST['intro_mobile'])?trim($_REQUEST['intro_mobile']):"";
        $vipone = isset($_REQUEST['vipone'])?trim($_REQUEST['vipone']):1.0;
        $viptwo = isset($_REQUEST['viptwo'])?trim($_REQUEST['viptwo']):1.0;
        $vipthree = isset($_REQUEST['vipthree'])?trim($_REQUEST['vipthree']):1.0;
        $vipfour = isset($_REQUEST['vipfour'])?trim($_REQUEST['vipfour']):1.0;
        
        $can_testing = isset($_REQUEST['can_testing'])?trim($_REQUEST['can_testing']):0;
        $can_guarantee = isset($_REQUEST['can_guarantee'])?trim($_REQUEST['can_guarantee']):0;
		$quality_testing = isset($_REQUEST['quality_testing'])?trim($_REQUEST['quality_testing']):0.0;
		$guarantee_1 = isset($_REQUEST['guarantee_1'])?trim($_REQUEST['guarantee_1']):0.0;
		$guarantee_2 = isset($_REQUEST['guarantee_2'])?trim($_REQUEST['guarantee_2']):0.0;
		$guarantee_5 = isset($_REQUEST['guarantee_5'])?trim($_REQUEST['guarantee_5']):0.0;
		
		//运费相关
		$weight = isset($_REQUEST['weight'])?trim($_REQUEST['weight']):'';
		$volume = isset($_REQUEST['volume'])?trim($_REQUEST['volume']):'';
			
        if(empty($name)){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写产品名称";
			return $ret;
		}
		if(strlen($name) > 150){
			$ret['ret'] = 0;
			$ret['msg'] = "产品名称不超过150个字符";
			return $ret;
		}
		
		if(!Common::isInteger($ptid) || $ptid <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "请选择产品类型";
			return $ret;
		}
		if(!Common::isInteger($is_harmful) || $is_harmful < 0){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误2";
			return $ret;
		}
		
		if($imageUrl == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "请添加产品图片";
			return $ret;
		}
		
		if($can_testing == 1 && empty($quality_testing)){
			$ret['ret'] = 0;
			$ret['msg'] = "请设定质检价格";
			return $ret;
		}
		if($can_guarantee == 1 && (empty($guarantee_1)||empty($guarantee_2)||empty($guarantee_5))){
			$ret['ret'] = 0;
			$ret['msg'] = "请将保修价格设定完整";
			return $ret;
		}
		
		FileUtil::requireService("BrandServ");
        $serv = new BrandServ();
		$brand = $serv->getBrandByName($bName);
		if($brand === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
		if(empty($brand)){
			$ret['ret'] = 0;
			$ret['msg'] = "品牌不存在!";
			return $ret;
		}
		
		//运费相关
		if($fre_id == 0){
			if(empty($address_id)){
				$ret['ret'] = 0;
				$ret['msg'] = "请选择你的发货地址";
				return $ret;
			}
			$product['address_id'] = $address_id;
		} else {
			FileUtil::requireService("FreightServ");
	        $freightServ = new FreightServ();
			$freight = $freightServ->getFreightDetail($fre_id);
			if($freight === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
				return $ret;
			}
			if(empty($freight)){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉,未找到你选择的运费模板1";
				return $ret;
			}
			if($freight['sid'] != $shop['sid']){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉,未找到你选择的运费模板2";
				return $ret;
			}
			if($freight['valuation_unit'] == 2){
				if(empty($weight)){
					$ret['ret'] = 0;
					$ret['msg'] = "运费模板的计价方式为按重量计算,请填写产品重量";
					return $ret;
				}
				if(!Common::isInteger($weight) && !Common::isPostiveFloat($weight)){
					$ret['ret'] = 0;
					$ret['msg'] = "产品重量为数字形式";
					return $ret;
				}
				$product['weight'] = $weight;
			}
			if($freight['valuation_unit'] == 3){
				if(empty($volume)){
					$ret['ret'] = 0;
					$ret['msg'] = "运费模板的计价方式为按体积计算,请填写产品体积";
					return $ret;
				}
				if(!Common::isInteger($volume) && !Common::isPostiveFloat($volume)){
					$ret['ret'] = 0;
					$ret['msg'] = "产品体积为数字形式";
					return $ret;
				}
				$product['volume'] = $volume;
			}			
		}
		
		$product['sid'] = $shop['sid'];
		$product['name'] = $name;
		$product['pinyin'] = Pinyin::pinyin($name);
		$product['images'] = $imageUrl;
		$product['first_tid'] = $first_tid;
		$product['second_tid'] = $second_tid;
		$product['third_tid'] = $third_tid;
		$product['forth_tid'] = $forth_tid;
		$product['fifth_tid'] = $fifth_tid;
		$product['ptid'] = $ptid;
		$product['brand_id'] = $brand['brand_id'];
		$product['fre_id'] = $fre_id;
		$product['time'] = time();
		
		if(!empty($alias)){
			$product['alias'] = $alias;
		}
		if(!empty($EnglishName)){
			$product['EnglishName'] = $EnglishName;
		}
		if(!empty($CASnumber)){
			$product['CASnumber'] = $CASnumber;
		}
		if(!empty($goods_code)){
			$product['goods_code'] = $goods_code;
		}
		if(!empty($size)){
			$product['size'] = $size;	
		}
		if(!empty($purity)){
			$product['purity'] = $purity;
		}
		if(!empty($is_harmful)){
			$product['is_harmful'] = $is_harmful;
		}
		if(!empty($shelf_life)){
			$product['shelf_life'] = $shelf_life;
		}
		if(!empty($packing)){
			$product['packing'] = $packing;
		}
		if(!empty($unit)){
			$product['unit'] = $unit;
		}
		if(!empty($producer)){
			$product['producer'] = $producer;
		}
		if(!empty($store)){
			$product['store'] = $store;
		}
		if(!empty($traffic)){
			$product['traffic'] = $traffic;
		}
		
		if($vipone != 1.0 && $vipone != ""){
			$product['vipone'] = $vipone;	
		}
		if($viptwo != 1.0 && $viptwo != ""){
			$product['viptwo'] = $viptwo;	
		}
		if($vipthree != 1.0 && $vipthree != ""){
			$product['vipthree'] = $vipthree;	
		}
		if($vipfour != 1.0 && $vipfour != ""){
			$product['vipfour'] = $vipfour;	
		}
		if($video_url != ""){
			$product['video_url'] = $video_url;
		}
		if($video_img != ""){
			$product['video_img'] = $video_img;
		}
		if($intro != ""){
			$product['intro'] = $intro;
		}
		if($intro_mobile != ""){
			$product['intro_mobile'] = $intro_mobile;
		}
		if($can_testing == 1){
			$product['can_testing'] = 1;
			$product['quality_testing'] = $quality_testing;
		}
		if($can_guarantee == 1){
			$product['can_guarantee'] = 1;
			$product['guarantee_1'] = $guarantee_1;
			$product['guarantee_2'] = $guarantee_2;
			$product['guarantee_5'] = $guarantee_5;
		}
		
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
        
		$callback = $service->addMyProduct($product);	
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
			return $ret;
		}
		$addProduct = $service->getJustInsertProduct($name,$shop['sid'],$ptid,$product['time']);
        if($addProduct === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误4，请稍后再试";
			return $ret;
		}
		if(empty($addProduct)){
			$ret['ret'] = 0;
			$ret['msg'] = "添加失败,未知错误";
			return $ret;
		}	
		
		$pid = $addProduct['pid'];
		$pronumber = 'LR-'.$pid;
		$callback = $service->updateProductNumber($pid,$pronumber);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误5，请稍后再试";
			return $ret;
		}
		
		$ret['ret'] = 1;
		$ret['msg'] = "添加成功"; 
		$ret['pronumber'] = $pronumber;
		return $ret;
	}
}

?>