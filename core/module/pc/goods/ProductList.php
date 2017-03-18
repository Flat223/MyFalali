<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ProductList extends BaseAction{
	
	public function action(){
		$params=array();
		//获取参数
		$ptid=isset($_REQUEST['ptid'])?trim($_REQUEST['ptid']):0;
		$keyword=isset($_REQUEST['key'])?trim($_REQUEST['key']):"";
		$brandid=isset($_REQUEST['brandid'])?trim($_REQUEST['brandid']):0;
		$lprice=isset($_REQUEST['m'])?trim($_REQUEST['m']):0;
		$rprice=isset($_REQUEST['l'])?trim($_REQUEST['l']):999999;
		$page=isset($_REQUEST['page'])?trim($_REQUEST['page']):1;
		$sort=isset($_REQUEST['sort'])?trim($_REQUEST['sort']):1;
        $property=isset($_REQUEST['property'])?trim($_REQUEST['property']):"";
/*
		if(!Common::isInteger($ptid)||$ptid<=0){
			FileUtil::load404Html();
			exit(0);
		}
		if(!Common::isInteger($brandid)||$brandid<0){
			FileUtil::load404Html();
			exit(0);
		}
*/
		
		//请求service
		FileUtil::requireService("BrandServ");
		$service=new BrandServ();
		FileUtil::requireService("ProductServ");
		$service1=new ProductServ();
		FileUtil::requireService("GoodsServ");
		$service2=new GoodsServ();
		$ty=$service2->getProType($ptid);
		$ty=array_reverse($ty);
		$brandlist=$service->getBrandListByPtid($ptid);
		if($brandlist===false){
			FileUtil::loadServerErrHtml();
			exit(0);
		}
		if(count($brandlist)<=0){
			$brandid=0;
		}
		$hotsell=$service1->getHotSellProduct();
		if($hotsell===false){
			FileUtil::loadServerErrHtml();
			exit(0);
		}
		$type=$service1->getSingleType($ptid);
		if($type===false){
			FileUtil::loadServerErrHtml();
			exit(0);
		}
		$level=$type['level'];
		$typelist=array();
		$typelist=$service1->getChildType($ptid);
/*
		if($level==2){
			$typelist=$service1->getChildType($type['ptid']);
		}else if($level==3){
			$typelist=$service1->getChildType($type['parentid']);
		}else if($level==1){
			$typeparentlist=$service1->getChildType($type['ptid']);
			foreach($typeparentlist as $value){
				$typeparent=$service1->getChildType($value['ptid']);
				foreach($typeparent as $value1){
					array_push($typelist, $value1);
				}	
			}	
		}
*/
		if($typelist===false){
			FileUtil::loadServerErrHtml();
			exit(0);
		}
		$temp="";
		if(!empty($property)){
			$propertytemp1=explode(',', $property);
			foreach($propertytemp1 as $temp1){
				$temp2=explode(':', $temp1);
				$temp.=$temp2[1].",";
			}
			$temp=substr($temp,0,strlen($temp)-1);
		}
		$pagesize=15;
        $type=$service1->GetTypeName($ptid);
		$count=$service2->getProductListCount($brandid,$ptid,$lprice,$rprice,$temp,$level,$keyword);
		$pageUtil = new PageUtil($pagesize,$count,$page);
        $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
		$productlist=$service2->getProductListO($brandid,$ptid,$lprice,$rprice,$sort,$index,$pagesize,$temp,$level,$keyword);
		if($productlist===false){
			FileUtil::loadServerErrHtml();
			exit(0);
		}
		$params['productlist']=$productlist;
		$params['count'] = $count;
		$params['pager'] = $pageUtil;
		
		$propertyt=array();
		$propertylist=array();//最终list
		$propertyt=$service1->GetProperty($ptid);//临时有重复的list
		foreach($propertyt as $single){
			$has=false;
			foreach($propertylist as $single2){
				if($single2['name']==$single['name']){
					$has=true;
				}
			}
			if($has==false){
				array_push($propertylist, $single);
			}
		}
		$propertyf=$service2->getPropertyList($ptid);
		
		foreach($propertyf as $key=>$single){
			foreach($propertylist as $temp){
				if($single['id']==$temp['propertyid']){
					$propertyf[$key]['property'][]=$temp;
				}
			}
		}
		$params['key']=$keyword;
		$params['style'] = 'shop';
        $params['property']=$propertyf;
        $params['brandlist']=$brandlist;
		$params['hotsell']=$hotsell;
		$params['typelist']=$typelist;
		$params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/goods/productList.html?ptid='.$ptid.'&brandid='.$brandid.'&sort='.$sort.'&m='.$lprice.'&l='.$rprice;
        $params['type']=$ty;
		return $params;
	}
	
}