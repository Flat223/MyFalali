<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Couponlist extends BaseAction{

    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        $user = UserAgent::getUser();
        if($user['type'] != 3){
            FileUtil::load404Html();
            exit(0);
        }
        FileUtil::requireService("ShopServ");
        $service=new ShopServ();
        $shop = $service->getShopByMid($user['mid']);
        if($shop === false){
            FileUtil::load404Html();
            exit(0);
        }
        $params = array();
        if($shop == null){
            FileUtil::load404Html();
            exit(0);
        }

        FileUtil::requireService("CouponServ");
        FileUtil::requireService('PageUtil');
        $Coupon=new CouponServ();
        $Coupinlist=$Coupon->getCouponBysid($shop['sid'],10,$page);
        $params['count']=$Coupon->getCouponCount($shop['sid']);
        
        $params['style'] = 'myshop';
		$params['substyle'] = 'couponlist';
        $params['coupin']=$Coupinlist;
        $params['user'] = $user;
        $params['shop'] = $shop;
        $pageUtil = new PageUtil(10,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/myshop/couponList.html?';
        $params['pager'] = $pageUtil;
        return $params;
    }

}