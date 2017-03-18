<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class BuyProgress extends BaseAction{
	
	public function action(){
		$params = array();
		$user=UserAgent::getUser();
		$mid=$user['mid'];
		$utype=$user['type'];
		$sub_type=0;
		if($utype==2){
			$sub_type=$user['sub_type'];
		}
		$cid=0;
		FileUtil::requireService("UserServ");
		$userser=new UserServ();
		$addid=isset($_REQUEST['aid'])?trim($_REQUEST['aid']):0;
		if($addid<=0){
			$address=$userser->getDefaultAddress($mid);
			if($address===false||$address==null){
				FileUtil::load404Html();
				exit(0);
			}	
			$cid=$address['city'];
		}else{
			$address=$userser->getAddressDetail($addid);
			if($address===false||$address==null){
				FileUtil::load404Html();
				exit(0);
			}	
			$cid=$address['city'];
		}
		$left=UserAgent::getUserFund();
		FileUtil::requireService("UserServ");
		$service=new UserServ();
		FileUtil::requireService("ProductServ");
		$service1=new ProductServ();
		FileUtil::requireService('ShopServ');
		$service2=new ShopServ();
		FileUtil::requireService("InvoiceServ");
		$invoserv=new InvoiceServ();
		$invoice=$invoserv->getInvoice($mid);
		$params['invoices']=$invoice;		
		$addresslist=$service->getUserAddress($mid);
		$coupon=$service->getCoupon($mid);
		$cash=array();
		$discount=array();
		foreach($coupon as $key=>$single){
			if($single['type']==1){
				$discount[]=$single;
			}else if($single['type']==2){
				$cash[]=$single;
			}
		};
		//type=1  购物车  2  直接购买   3组合购买
		$type=isset($_REQUEST['type'])?trim($_REQUEST['type']):0;
		$totalmoney=0.00;
		if($type==1){
			$spid=isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
			$spids=explode(',', $spid);
			$list=array();
			for($a=0;$a<count($spids);$a++){
				$temp=$service->getCartDeatilById($spids[$a]);
				if($temp!=null&&$temp!==false){
					$list[]=$temp;
				}			
			}
			$sidlist=array();
			for($b=0;$b<count($list);$b++){
				$sidlist[]=$list[$b]['sid'];
			}
			$sidlistnew=array_unique($sidlist);
			sort($sidlistnew);
			$productlist=array();
			for($i=0;$i<count($sidlistnew);$i++){
				$shop=$service2->getShopDetail($sidlistnew[$i]);
				$productlist[]=$shop;
			}
			foreach($list as $key=>$single){
				$pid=md5($single['pid']);
				$skuid=$single['skuid'];
				$sid=$single['sid'];
				$product=$service1->getCartProduct($pid,$skuid);
				$product['intro']="";
				$product['intro_mobile']="";
				$property=$service1->getCartProperties($skuid);
				$product['property']=$property;
				$product['num']=$single['num'];
				$product['skuid']=$skuid;
				$product['testing']=$single['testing'];
				$product['guarantee']=$single['guarantee'];
				$price=$product['pprice'];
				if($single['testing']>0){
					$price+=$product['quality_testing'];
				}
				if($single['guarantee']==1){
					$price+=$product['guarantee_1'];
				}else if($single['guarantee']==2){
					$price+=$product['guarantee_2'];
				}else if($single['guarantee']==5){
					$price+=$product['guarantee_5'];
				}
				$product['pprice']=$price;
				foreach($productlist as $key=>$p){
					if($p['sid']==$sid){ 	
						$productlist[$key]['product'][]=$product;
					}
				}
				$totalmoney+=$product['pprice']*$product['num'];
			}
			$params['productlist']=$productlist;
			$params['totalmoney']=$totalmoney;
		}else if($type==2){
			$skuid=isset($_REQUEST['skuid'])?trim($_REQUEST['skuid']):0;
			$num=isset($_REQUEST['num'])?trim($_REQUEST['num']):0;
			$pid=isset($_REQUEST['pid'])?trim($_REQUEST['pid']):0;
			$testting=isset($_REQUEST['testing'])?trim($_REQUEST['testing']):0;
			$guarantee=isset($_REQUEST['guarantee'])?trim($_REQUEST['guarantee']):0;
			$productlist=array();
			$product=$service1->getCartProduct($pid,$skuid);
			$property=$service1->getCartProperties($skuid);
			$product['intro']="";
			$product['intro_mobile']="";
			$product['property']=$property;
			$product['num']=$num;
			$product['skuid']=$skuid;
			$product['testing']=$testting;
			$product['guarantee']=$guarantee;
			$price=$product['pprice'];
			if($testting>0){
				$price+=$product['quality_testing'];
			}
			if($guarantee==1){
				$price+=$product['guarantee_1'];
			}else if($guarantee==2){
				$price+=$product['guarantee_2'];
			}else if($guarantee==5){
				$price+=$product['guarantee_5'];
			}
			$sid=$product['sid'];
			$product['pprice']=$price;
			$shop=$service2->getShopDetail($sid);
			$productlist[]=$shop;
			foreach($productlist as $key=>$p){
				if($p['sid']==$sid){ 	
					$productlist[$key]['product'][]=$product;
				}
				$totalmoney+=$product['pprice']*$product['num'];
			}
			$params['productlist']=$productlist;
			$params['totalmoney']=$totalmoney;		
		}else if($type==3) {
			$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
			$groupdetail=$service1->getProGroupDetail($id);
			$skuids=$groupdetail['skuids'];
			$skuidlist=explode(',', $skuids);
			$pidlist=array();
/*
			echo(json_encode($groupdetail));
			exit(0);
*/
			$params['totalmoney']=$groupdetail['price'];
			for($a=0;$a<count($skuidlist);$a++){
				$tpid=$service1->getProductBySkuId($skuidlist[$a]);
				if($tpid!=null&&$tpid!==false){
					$pidlist[$a]['pid']=$tpid;
					$pidlist[$a]['skuid']=$skuidlist[$a];
				}else {
/*
					FileUtil::load404Html();
					exit(0);
*/
				}
			}
			$shopidlist=array();
			$productlist3=array();
			foreach($pidlist as $key=>$value){
				$product=$service1->getCartProduct(md5($value['pid']),$value['skuid']);
				$property=$service1->getCartProperties($value['skuid']);
				$product['intro']="";
				$product['intro_mobile']="";
				$product['property']=$property;
				$product['num']=1;
				$product['skuid']=$value['skuid'];
				$product['testing']=0;
				$product['guarantee']=0;

				$shopidlist[]=$product['sid'];
				$productlist3[]=$product;
			}
			$shopidl=array_unique($shopidlist);
			sort($shopidl);
			$productlist2=array();
			for($i=0;$i<count($shopidl);$i++){
				$shop=$service2->getShopDetail($shopidl[$i]);
				$productlist2[]=$shop;
			}
			foreach($productlist3 as $k=>$pro){
				foreach($productlist2 as $key=>$p){
					if($p['sid']==$pro['sid']){ 	
						$productlist2[$key]['product'][]=$pro;
					}
				} 
			}
			$params['productlist']=$productlist2;			
		}
		$newdiscount=array();
		//处理优惠券以及代金券是否可用
		foreach($params['productlist'] as $detail){
			$plist=$detail['product'];
			$price=0.00;
			foreach($plist as $value){
				$price+=$value['pprice'];
			}
			foreach($discount as $single){
				if($single['min_limit']<=$price&&$single['sid']==$detail['sid']){
					$newdiscount[]=$single;
				}
			}
		}
		$newcash=array();
		foreach($cash as $cash1){
			if($cash1['min_limit']<=$totalmoney){
				$newcash[]=$cash1;
			}
		}
		foreach($params['productlist'] as $k1=>$shop){
			$temp=array();
			foreach($shop['product'] as $k2=>$product){
				$has=false;
				foreach($temp as $tkey=>$te){
					if($te['fre_id']==$product['fre_id']&&$product['fre_id']!=0){
						$temp[$tkey]['num']=$temp[$tkey]['num']+$product['num'];
						$has=true;
					}
				}
				if($has==false){
					$tt=array();
					$tt['pid']=$product['pid'];
					$tt['num']=$product['num'];
					$tt['fre_id']=$product['fre_id'];
					$temp[]=$tt;
				}
			}
			$shopfre=array();
			$shopfre['expressp']=0;
			$shopfre['emsp']=0;
			$shopfre['mailp']=0;
			$exp=0;
			$ems=0;
			$mail=0;
			foreach($temp as $asdf){
				$pid=$asdf['pid'];
				$num=$asdf['num'];
				$result=CommonFunc::getProductFreight($cid,$pid,$num);
				if($result['ret']==1){
					if($result['expressp']==-1){
						$exp=1;
					} 
					if($result['emsp']==-1){
						$ems=1;
					}
					if($result['mailp']==-1){
						$mail=1;
					}
					if($exp==1&&$ems==1&&$mail==1){
						$normal=1;
						$params=array();
						$params['msg']="您选的产品，该地区无货";
						FileUtil::load0000Html($params);
						exit(0);
					}
					if($result['expressp']>=0){
						$shopfre['expressp']=$shopfre['expressp']+$result['expressp'];	
					}
					if($result['emsp']>=0){
						$shopfre['emsp']=$shopfre['emsp']+$result['emsp'];	
					}
					if($result['mailp']>=0){
						$shopfre['mailp']=$shopfre['mailp']+$result['mailp'];	
					}
				}
			}	
			$params['productlist'][$k1]['exp']=$exp;
			$params['productlist'][$k1]['ems']=$ems;
			$params['productlist'][$k1]['mail']=$mail;
			$params['productlist'][$k1]['freight']=$shopfre;	
		}
/*
		echo(json_encode($addresslist));
		exit(0);
*/
		$params['addresslist']=$addresslist;
		$params['discount']=$newdiscount;
		$params['cash']=$newcash;
		$params['left']=$left;
		$params['mobile']=$user['mobile'];
		$params['type']=$utype;
		$params['sub_type']=$sub_type;
		
		//$params['totalmoney']=$totalmoney;
		return $params;
	}
	
}