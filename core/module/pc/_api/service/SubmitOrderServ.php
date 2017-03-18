<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SubmitOrderServ extends BaseAction{
	
	public function action(){
		$ret=array();
		$user=UserAgent::getUser();
		$mid=$user['mid'];
		$pros = isset($_REQUEST['skus'])?$_REQUEST['skus']:"";
		$addressid = isset($_REQUEST['addressid'])?$_REQUEST['addressid']:0;
		$remarks = isset($_REQUEST['remarks'])?$_REQUEST['remarks']:"";
		$coupontype=isset($_REQUEST['ctype'])?trim($_REQUEST['ctype']):0;//卷的类型  1优惠券 2代金券
		$couponid=isset($_REQUEST['cid'])?trim($_REQUEST['cid']):0;//卷的id
		$foundtype=isset($_REQUEST['fund'])?trim($_REQUEST['fund']):0;//科研基金type 0 不使用 1使用
		$invoiceid=isset($_REQUEST['code'])?trim($_REQUEST['code']):0;//发票的id
		$ordertype=isset($_REQUEST['ordertype'])?trim($_REQUEST['ordertype']):0;//订单类型  1:需求订单 2:采购订单  3高校订单  4个人订单
		$groupid=isset($_REQUEST['gid'])?trim($_REQUEST['gid']):0;//组合id
		$cartids=isset($_REQUEST['cartids'])?trim($_REQUEST['cartids']):"";
		if($couponid>0&&$foundtype>0){
			$ret['ret']=0;
			$ret['msg']="科研基金无法与优惠券或代金券同时使用";
			return $ret;
		}
		
		if($pros == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		if(!Common::isInteger($addressid) || $addressid <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		FileUtil::requireService("CouponServ");
		$couponserv=new CouponServ();
		$coupond=$couponserv->getCouponDetail($coupontype,$couponid,$mid);
		$found=UserAgent::getUserFund();//科研基金
		$productids = explode(",", $pros);
		$skuids = array();
		foreach($productids as $value){
			$values = explode("_", $value);
			if(count($values) != 4){
				$ret['ret'] = 0;
				$ret['msg'] = "参数错误";
				return $ret;
			}
			$skuid = array();
			$skuid['skuid'] = $values[0];
			$skuid['num'] = $values[1];
			$skuid['testing']=$values[2];
			$skuid['guarantee']=$values[3];
			array_push($skuids, $skuid);
		}
		$remarks = json_decode($remarks,true);
		$shops = array();
		FileUtil::requireService('ProductServ');
		$service=new ProductServ();
		FileUtil::requireService('CouponServ');
		$service2=new CouponServ();
		FileUtil::requireService("GoodsServ");
		$gservice=new GoodsServ();
		$gmoney=0;
		if($groupid>0){
			$group=$service->getProGroupDetail($groupid);
			if($group!=null&&$group!==false){
				$gmoney=$group['price'];
			}else{
				$ret['ret'] = 0;
				$ret['msg'] = "组合信息有错误";
				return $ret;
			}
		}
		$couponmoney=0.00;
		//优惠券的信息
		if($couponid>0){
			$coupond=$service2->getCouponDetail($coupontype,$couponid,$mid);
			if($coupond!==false&&$coupond!=null){
				$couponmoney=$coupond['money'];
			}
		}
		foreach($skuids as $skuid){
			if(!Common::isInteger($skuid['skuid']) || $skuid['skuid'] <= 0){
				$ret['ret'] = 0;
				$ret['msg'] = "参数错误";
				return $ret;
			}
			if(!Common::isInteger($skuid['num']) || $skuid['num'] <= 0){
				$ret['ret'] = 0;
				$ret['msg'] = "参数错误";
				return $ret;
			}
			$sku = $service->getBuyProductDetail($skuid['skuid']);
			if($sku === false){
				$ret['ret'] = 0;
				$ret['msg'] = "服务器错误";
				return $ret;
			}
			if($sku === null){
				$ret['ret'] = 0;
				$ret['msg'] = "未找到数据或已失效";
				return $ret;
			}
			if($sku['inventory'] < $skuid['num']){
				$ret['ret'] = -1;
				$ret['msg'] = "部分商品库存已不足,请重新下单";
				return $ret;
			}
			$sku['buyNum'] = $skuid['num'];
			$sku['testing']=$skuid['testing'];
			$sku['guarantee']=$skuid['guarantee'];
			
			if(!array_key_exists($sku['sid'], $shops)){
				$shop = array();
				$shop['sid'] = $sku['sid'];
				$shop['name'] = $sku['shop_name'];
				$shops[$sku['sid']] = $shop;
			}
			$shop = $shops[$sku['sid']];
			if(!array_key_exists("skus", $shop)){
				$shop['skus'] = array();
			}
			$skus = $shop['skus'];
			array_push($skus, $sku);
			$shop['skus'] = $skus;
			$shops[$sku['sid']] = $shop;
		}
		FileUtil::requireService('UserServ');
		$service1=new UserServ();
		FileUtil::requireService('OrderServ');
		$orderservice=new OrderServ();
		$address = $service1->getAddressDetail($addressid);
		$cid=$address['city'];
		if(!$address){
			$ret['ret'] = 0;
			$ret['msg'] = "服务器错误";
			return $ret;
		}
		$orders = array();
		$products = array();
		$ordercodes = array();
		$updateSkuids = array();
		$allmoney=0;
		
		foreach($shops as $shop){
			$pidarray=array();
			$order = array();
			$time=time();
			$rand=rand(100,999);
			$orderCode=$time.$rand;

			$order['ordercode'] = $orderCode;
			array_push($ordercodes, $order['ordercode']);
			if($order['ordercode'] === false){
				$ret['ret'] = 0;
				$ret['msg'] = "服务器错误";
				return $ret;
			}
			if($ordertype==1){
				$order['type']=1;
				$order['agree']=1;
				$order['mid']=$user['bind_company'];
				$order['payer_mid']=0;
				$order['order_from_mid']=$mid;
			
			}else if($ordertype==2){
				$order['type']=2;
				$order['agree']=0;
				$order['mid']=$user['bind_company'];
				$order['payer_mid']=$mid;
				$order['order_from_mid']=0;
				
			}else if($ordertype==3){
				$order['type']=3;
				$order['agree']=0;
				$order['mid']=$user['bind_company'];
				$order['payer_mid']=$mid;
				$order['order_from_mid']=0;
				
			}else if($ordertype==1){
				$order['type']=4;
				$order['agree']=0;
				$order['mid']=$mid;
				$order['payer_mid']=$mid;
				$order['order_from_mid']=0;
			}else {
				$order['type']=4;
				$order['agree']=0;
				$order['mid']=$mid;
				$order['payer_mid']=$mid;
				$order['order_from_mid']=0;
			}
			$ptype=0;
			if($foundtype>0){
				$ptype=2;	
			}
			$order['payment_type']=$ptype;
			$order['province'] = $address['province'];
			$order['city'] = $address['city'];
			$order['district'] = $address['country'];
			$order['address'] = $address['detail_address'];
			$order['consignee'] = $address['name'];
			$order['mobile'] = $address['mobile'];
			$order['zip'] = $address['zip'];
			$order['address_id']=$addressid;
			$order['freight'] = 0.00;
			$order['sid'] = $shop['sid'];
			$order['couponid']=0;
			if($coupond['type']==1){
				if($shop['sid']==$coupond['sid']){
					$order['couponid']=$coupond['id'];
				}
			}else if($coupond['type']==2){
				$order['couponid']=$coupond['id'];
			}
			$order['invoiceid']=$invoiceid;
			$totalFee = 0.00;
/*
			echo(json_encode($shop));
			exit(0);
*/
			foreach($shop['skus'] as $sku){
				array_push($updateSkuids, $sku['skuid']);
				$buynum = intval($sku['buyNum']);
				$price = floatval($sku['price']);
				$vip=$user['vip_level'];
				$dis=1;
				if($vip==1){
					$dis=$sku['v1_discount'];	
				}else if($vip==2){
					$dis=$sku['v2_discount'];	
				}else if($vip==3){
					$dis=$sku['v3_discount'];
				}else if($vip==4){
					$dis=$sku['v4_discount'];
				}
				if($sku['testing']>0&&$sku['can_testing']==0){
					$ret['ret']=0;
					$ret['msg']="有产品无法进行质量检测";
					return $ret;
				}
				
				if($sku['guarantee']>0&&$sku['can_guarantee']==0){
					$ret['ret']=0;
					$ret['msg']="有产品无法进行延长保修";
					return $ret;
				}
				if($sku['testing']>0){
					$price+=$sku['quality_testing'];
				}
				if($sku['guarantee']==1){
					$price+=$sku['guarantee_1'];	
				}else if($sku['guarantee']==2){
					$price+=$sku['guarantee_2'];
				}else if($sku['guarantee']==5){
					$price+=$sku['guarantee_5'];
				}
				$totalFee += round($price*$buynum*$dis,2);			
				$product = array();
				$product['ordercode'] = $order['ordercode'];
				$product['pid'] = $sku['pid'];
				$product['buynum'] = $buynum;
				$product['price'] = $price;
				$product['name'] = $sku['pname'];
				$product['intro'] = $sku['intro'];
				$product['images'] = $sku['images'];
				$product['skuid'] = $sku['skuid'];
				$temp=array();
				$temp['pid']=$sku['pid'];
				$temp['num']=$buynum;
				$pidarray[]=$temp;
				$propKeyVals = array();
				$propKeyVals=$service->getPropertyDetail($sku['skuid']);
				if($propKeyVals === false){
					$propKeyVals = array();
				}
				$product['props'] = json_encode($propKeyVals,JSON_UNESCAPED_UNICODE);
				array_push($products, $product);
			}
			$orderremarks = "";
			if(is_array($remarks)){
				foreach($remarks as $remark){
					if(isset($remark['sid']) && $remark['sid'] === $shop['sid']){
						if(isset($remark['remarks']) && strlen($remark['remarks']) >= 0 && strlen($remark['remarks']) <= 50){
							$orderremarks = $remark['remarks'];
							$type=$remark['etype'];
							$producttemp=array();
							foreach($pidarray as $e){
								$product=$gservice->getProductDetail($e['pid']);
								$product['buynum']=$e['num'];
								$producttemp[]=$product;
							}
							$lasttemp=array();
							foreach($producttemp as $k2=>$pro){
								$has=false;
								foreach($lasttemp as $tkey=>$te){
									if($te['fre_id']==$pro['fre_id']&&$pro['fre_id']!=0){
										$lasttemp[$tkey]['num']=$lasttemp[$tkey]['num']+$pro['buynum'];
										$has=true;
									}
								}
								if($has==false){
									$tt=array();
									$tt['pid']=$pro['pid'];
									$tt['num']=$pro['buynum'];
									$tt['fre_id']=$pro['fre_id'];
									$lasttemp[]=$tt;
								}
							}
							$shopfre=array();
							$shopfre['expressp']=0;
							$shopfre['emsp']=0;
							$shopfre['mailp']=0;
							foreach($lasttemp as $asdf){
								$pid=$asdf['pid'];
								$num=$asdf['num'];
								$result=CommonFunc::getProductFreight($cid,$pid,$num);
								if($result['ret']==1){
									if($result['expressp']==-1&&$result['emsp']==-1&&$result['mailp']==-1){
										$ret['ret']=0;
										$ret['msg']="您选择的某些产品，该地区无货";
										return $ret;
									}
									if($result['expressp']>0){
										$shopfre['expressp']=$shopfre['expressp']+$result['expressp'];	
									}
									if($result['emsp']>0){
										$shopfre['emsp']=$shopfre['emsp']+$result['emsp'];	
									}
									if($result['mailp']>0){
										$shopfre['mailp']=$shopfre['mailp']+$result['mailp'];	
									}
								}
							}
							$ret['fre']=$shopfre;
							if($type==1){
								$order['freight']=$shopfre['expressp'];
							}else if($type==2){
								$order['freight']=$shopfre['emsp'];
							}else if($type==3){
								$order['freight']=$shopfre['mailp'];
							}	
						}
					}
				}
			}
			$allmoney+=$totalFee+$order['freight'];
			$order['totalprice'] = $allmoney;
			$order['disprice'] = $allmoney;
			if($coupond['type']==1){
				$order['disprice']=$allmoney-$coupond['money'];
			}
			if($gmoney>0&&$groupid>0){
				$order['totalprice'] = $gmoney;
				$order['disprice']=$gmoney;
				$allmoney=$gmoney;
			}
			$order['remarks'] = $orderremarks;
			array_push($orders, $order);
		}
		if($couponid>0){
			$ordersmoney=0.00;
			if($coupond['type']==2){
				foreach($orders as $sinor){
					$ordersmoney+=$sinor['totalprice'];
				}
				for($o=0;$o<count($orders);$o++){
					$dis_fee=$orders[$o]['totalprice']-($orders[$o]['totalprice']/$ordersmoney)*$couponmoney;
					if($dis_fee<=0){
						$dis_fee=0.01;
					}
					$orders[$o]['disprice']=$dis_fee;
				}
			}	
		}
		if($couponid>0){
			$callb=$service2->updateUseStatus($mid,$couponid,4);
			if(!$callb){
				$ret['ret']=0;
				$ret['msg']="服务器错误";
				return $ret;
			}
		}
		if(!empty($cartids)){
			$callc=$service->updateCart($cartids,$mid);
			if(!$callc){
				$ret['ret']=0;
				$ret['msg']="服务器错误";
				return $ret;
			}
		}
		$callback = $service->saveOrders($orders,$products);
		if($found<$allmoney&&$foundtype==1){
			$ret['ret']=0;
			$ret['msg']="科研基金不足";
			return $ret;
		}
		if($callback){
			if($foundtype==1){
				foreach($ordercodes as $ordercode){
					$callback1=$orderservice->updatefoundorder($ordercode);
				}
				$callback2=$orderservice->updatememberfound($mid,$allmoney);
				$ret['ret']=2;
				$ret['msg']="购买成功";
				$ret['price']=$allmoney;
				$ret['code']=$ordercodes;
				$ret['addressid']=$addressid;
				return $ret;
			}else{
				//正常返回购买
				$ret['ret'] = 1;
				$ret['msg'] = "操作成功"; 
				$ret['ordercodes'] = $ordercodes;
				$ret['addressid']=$addressid;
				$ret['type']=$ordertype;
				return $ret;
			}			
		}	
		$ret['ret'] = 0;
		$ret['msg'] = "服务器错误";
		$ret['back']=$callback;
		$ret['mid']=$orders;
		return $ret;		
	}
}
?>