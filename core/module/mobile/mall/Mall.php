<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Mall extends BaseAction{

    public function action(){
		FileUtil::requireService('ProductServ');
		$service = new ProductServ();
		$tlist=$service->getProType();
		$typelist=array();
		$user=UserAgent::getUser();
		
		foreach($tlist as $type){
			if($type['level']==1&&$type['parentid']==0){
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
				unset($typelist[$key]);
			}
		}
/*
		echo(json_encode($typelist));
		exit(0);
*/
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
		$recommendproducts=$service->getRecommendProduct();
		if($recommendproducts===false){
			FileUtil::loadServerErrHtml();
			exit(0);
		}
		$type=$service->getProductType();
		if($type===false){
			FileUtil::loadServerErrHtml();
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
			FileUtil::loadServerErrHtml();
			exit(0);
		}
		FileUtil::requireService("BrandServ");
		$service2=new BrandServ();
		$brandlist=$service2->getBrandRecommend2();
		if($brandlist===false){
			echo(json_encode($brandlist));
			exit(0);		
		}
        FileUtil::requireService("AdvertServ");
        $Advert=new AdvertServ();
        $login=0;
        if($user!=null){
	         $login=1;
	    }
	    foreach($recommendall as $key=>$sing){
		    if(!isset($sing['products'])){
			    $recommendall[$key]['products']=array();
		    }
	    }
/*
	    echo(json_encode($recommendall));
	    exit(0);
*/
        $params = array();
        $params['advert']=$Advert->getAdvert(7,4);
        $params['style'] = 'mall';
		$params['typelist']=$typelist;
		$params['recommend']=$recommendall;
		$params['picture']=$piclist;
		$params['brandlist']=$brandlist;
		return $params;
    }

}