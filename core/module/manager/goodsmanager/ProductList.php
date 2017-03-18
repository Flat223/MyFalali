<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ProductList extends BaseAction{

    public function action(){
        $page = isset($_GET['page'])?$_GET['page']:1;
        $info = isset($_GET['info'])?$_GET['info']:"";
        $ch = isset($_GET['ch'])?$_GET['ch']:1;
        FileUtil::requireService("ProductServ");
        $serv = new ProductServ();

        $product = array();
        $brand = array();
        $pagesize = 20;

        $count = $serv->getProductLists($ch,$info);
        $pageUtil = new PageUtil($pagesize,$count,$page);
        $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
        $product = $serv->getPageProductLists($ch,$info,$index,$pagesize);
        foreach ($product as $val){
            $brand[] = $serv->getBrandNameById($val['brand_id']);
        }
        $protype = $serv->getProductTypeLevel1();
        
        
        $params['substyle']='productList';
        $params['product'] = $product;
        $params['brand'] = $brand;
        $params['count'] = $count;
        $params['info'] = $info;
        $params['ch'] = $ch;
        $params['protype'] = $protype;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/goodsmanager/productList.html?ch='.$ch."&info=".$info;

        return $params;
    }

}