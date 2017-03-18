<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Shop extends BaseAction{
	
	public function action(){
		FileUtil::requireService('ProductServ');
		$service = new ProductServ();
		$tlist=$service->getProType();
		$typelist=array();
		$user=UserAgent::getUser();
		foreach($tlist as $type){
			if($type['level']==1){
				$typelist[]=$type;
			}
		}
		foreach($tlist as $type1){
			for($i=0;$i<count($typelist);$i++){
				if($typelist[$i]['ptid']==$type1['parentid']){
					$typelist[$i]['second'][]=$type1;
				}
			}
		}
		foreach($typelist as $key=>$ppp){
			if(!isset($ppp['second'])){
				$typelist[$key]['second']=array();
			}
		}

		foreach($tlist as $type2){
			for($z=0;$z<count($typelist);$z++){
				for($x=0;$x<count($typelist[$z]['second']); $x++){
					if($typelist[$z]['second'][$x]['ptid']==$type2['parentid']){
						$typelist[$z]['second'][$x]['third'][]=$type2;
					}else{
						$typelist[$z]['second'][$x]['third'][]="";
					}
				}
			}
		}
/*
		echo(json_encode($typelist));
	    exit(0);
*/
		$recommendproducts=$service->getRecommendProduct();
		if($recommendproducts===false){
			FileUtil::load404Html();
			exit(0);
		}
		$type=$service->getProductType();
		if($type===false){
			FileUtil::load404Html();
			exit(0);
		}
		$recommendall=array();
		foreach($recommendproducts as $key=>$product){
			$ptid=$service->getLevle1ByL3($product['ptid']);
			$recommendproducts[$key]['fptid']=$ptid;
		}
		for($a=0;$a<count($type);$a++){
			$recommendall[$a]['typename']=$type[$a]['name'];
			$recommendall[$a]['typeid']=$type[$a]['ptid'];
			$temppro=array();
			foreach($recommendproducts as $key=>$product){
				if($type[$a]['ptid']==$product['fptid']){
					$temppro[]=$product;
				}
			$recommendall[$a]['products']=$temppro;
			}
		}
		FileUtil::requireService("BannerServ");
		$service1=new BannerServ();
		$piclist=$service1->getBannerlist(3);
		if($piclist===false){
			FileUtil::load404Html();
			exit(0);
		}
		FileUtil::requireService("BrandServ");
		$service2=new BrandServ();
		$brandlist=$service2->getBrandRecommend();
		if($brandlist===false){
			FileUtil::load404Html();
			exit(0);		
		}
        FileUtil::requireService("AdvertServ");
        $Advert=new AdvertServ();
        $login=0;
        if($user!=null){
	         $login=1;
	    }
	    FileUtil::requireService("RecommendServ");
	    $recomd=new RecommendServ();
	    $ft=$recomd->getLevel1(); 
	    $sdetail=$recomd->getRecommendLevel2();
	    foreach($sdetail as $key=>$a){
		    $sdetail[$key]['pid']=md5($a['pid']);
	    }

	    foreach($ft as $key=>$first){
		    foreach($sdetail as $key2=>$pro){
			    if($pro['level1']==$first['ptid']){
				    if(array_key_exists("second",$ft[$key])){
					    $has = false;
				    	foreach($ft[$key]['second'] as $la ){
							if($la['ptid']==$pro['level2']){
								$has = true;
							}
						}
						if(!$has){
							$pro1=array();
							$pro1['ptid']=$pro['level2'];
							$pro1['name']=$pro['ptname'];
							$ft[$key]['second'][]=$pro1;
						}    
				    }else{
					    $pro1=array();
						$pro1['ptid']=$pro['level2'];
						$pro1['name']=$pro['ptname'];
				    	$ft[$key]['second'][]=$pro1;
				    }
			    }
		    }
	    }
	    foreach($ft as $key=>$first){
		    if(isset($first['second'])){
			   	foreach($first['second'] as $key2=>$second ){
			    	foreach($sdetail as $sing){
				    	if($sing['level2']==$second['ptid']){
					    	$ft[$key]['second'][$key2]['products'][]=$sing;
				    	}
			    	}
		    	}
		    }
	    }



        $params = array();
        $params['advertbanner']=$Advert->getAdvert(5,3);
        $params['style'] = 'shop';
		$params['typelist']=$typelist;
		$params['recommend']=$recommendall;
		$params['recommendnew']=$ft;
		$params['picture']=$piclist;
		$params['brandlist']=$brandlist;
		return $params;
	}
	
}