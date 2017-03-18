<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Detail extends BaseAction{
	
	public function action(){
		$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
		$type=isset($_REQUEST['type'])?trim($_REQUEST['type']):0;
		$sort=isset($_REQUEST['sort'])?trim($_REQUEST['sort']):0;
		$page=isset($_REQUEST['page'])?trim($_REQUEST['page']):1;
		if(!Common::isInteger($id)||$id<=0){
			FileUtil::loadServerErrHtml();
			exit(0);
		}
/*
		if(!Common::isInteger($type)||$type<=0){
			FileUtil::loadServerErrHtml();
			exit(0);
		}
*/
		if(!Common::isInteger($sort)||$sort<=0){
			FileUtil::loadServerErrHtml();
			exit(0);	
		}
		FileUtil::requireService("BrandServ");
		$service=new BrandServ();
		$brand=$service->getBrandDetail($id);
		if($brand===false||$brand==null){
/*
			FileUtil::loadServerErrHtml();
			exit(0);
*/
		}
		FileUtil::requireService("ProductServ");
		$pagesize=12;
		$service1=new ProductServ();
		$count=$service1->getBrandProductListCount($id,$type);
/*
		echo(json_encode($count));
		exit(0);
*/
		$pageUtil = new PageUtil($pagesize,$count,$page);
        $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
        
		$productlist=$service1->getBrandProductList($id,$type,$sort,$index,$pagesize);
		if($productlist===false){
/*
			FileUtil::loadServerErrHtml();
			exit(0);
*/
		}
/*
		echo(json_encode($productlist));
		exit(0);
*/
		$params= array();
		$params['brand']=$brand;
		$params['productlist']=$productlist;
		$params['count'] = $count;
		$params['pager'] = $pageUtil;
		$params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/brand/detail.html?id='.$id.'&type='.$type.'&sort='.$sort;
		return $params;
	}
	
}