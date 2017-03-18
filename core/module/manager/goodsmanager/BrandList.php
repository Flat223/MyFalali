<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class BrandList extends BaseAction{

    public function action(){
        $page = isset($_GET['page'])?$_GET['page']:1;
        $info = isset($_GET['info'])?$_GET['info']:"";

        FileUtil::requireService("BrandServ");
        $serv = new BrandServ();
        $brand = array();
        $pagesize = 10;        
        $brand = array();
        $pagesize = 10;
        $count = $serv->getBrandCount($info);
        $pageUtil = new PageUtil($pagesize,$count,$page);
        $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
        $brand = $serv->getPageBrandList($index,$pagesize,$info);
        $params['substyle']='brandList';
        $params['brand'] = $brand;
        $params['count'] = $count;
        $params['info'] = $info;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/goodsmanager/brandList.html?pj=1&info='.$info;
        return $params;
    }

}