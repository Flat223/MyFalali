<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Shop extends BaseAction{

    public function action(){
        $page = isset($_REQUEST['page'])?trim($_REQUEST['page']):1;
        $data = isset($_REQUEST['data'])?trim($_REQUEST['data']):"";
        FileUtil::requireService('ShopServ');
        $serv = new ShopServ();
        $shops = array();
        $member = array();
        $type = array();
        $pagesize = 10;
        $count = 0;
        if(empty($data)){
            $shops = $serv->getShops();
            $count = sizeof($shops);
            $shops = array();
            $pageUtil = new PageUtil($pagesize,$count,$page);
            $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $shops = $serv->getPageShops($index,$pagesize);
            for($i = 0;$i <count($shops);$i++){
                $member [] = $serv->getShopBossById($shops[$i]['mid']);
            }
            for($i = 0;$i <count($shops);$i++){
                $type [] = $serv->getShopTypeById($shops[$i]['stid']);
            }
        }else{
            $shops = $serv->getShopsByInfo($data);
            $count = sizeof($shops);
            $shops = array();
            $pageUtil = new PageUtil($pagesize,$count,$page);
            $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $shops = $serv->getPageShopsByInfo($data,$index,$pagesize);
            for($i = 0;$i <count($shops);$i++){
                $member [] = $serv->getShopBossById($shops[$i]['mid']);
            }
            for($i = 0;$i <count($shops);$i++){
                $type [] = $serv->getShopTypeById($shops[$i]['stid']);
            }
        }

		$params['substyle'] = 'shop';
        $params['shop'] = $shops;
        $params['member'] = $member;
        $params['type'] = $type;
        $params['count'] = $count;
        $params['pager'] = $pageUtil;
        $params['data'] = $data;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/goodsmanager/shop.html?data='.$data;
        return $params;
    }

}