<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Search extends BaseAction
{

    public function action()
    {	
        $info=isset($_REQUEST['info'])?trim($_REQUEST['info']):"";
        $page=isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $ob = isset($_REQUEST['ob'])?$_REQUEST['ob']:1;
        $brandid=isset($_REQUEST['brand'])?trim($_REQUEST['brand']):0;
		$info=Common::UNESCAPE($info);
        FileUtil::requireService("GoodsServ");
        FileUtil::requireService('PageUtil');
        FileUtil::requireService("ProductServ");
		$service1=new ProductServ();
        $service=new GoodsServ();
        $pagesize=15;
		$typelist=$service->getSearchType($info);
		$brandlist=$service->getBrandList();
		
/*
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
		
		$propertyf=$service->getPropertyList($ptid);
		
		foreach($propertyf as $key=>$single){
			foreach($propertylist as $temp){
				if($single['id']==$temp['propertyid']){
					$propertyf[$key]['property'][]=$temp;
				}
			}
		}
*/
/*
		echo(json_encode($propertyf));
		exit(0);
*/
// 		$params['property']=$propertyf;
        $params['info']=$service->getProductByinfo($info,$page,$pagesize,$ob,$brandid);
        $pageUtil = new PageUtil($pagesize,$params['info']['count'],$page);
        $params['data'] = $info;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/goods/search.html?type=goods&info='.$info."&ob=".$ob;
        $params['pager'] = $pageUtil;
        $params['typelist']=$typelist;
        $params['brandlist']=$brandlist;
        return $params;
    }
}
