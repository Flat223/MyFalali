<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Seller extends BaseAction{

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

        if(!Common::isInteger($sort)||$sort<=0){
            FileUtil::loadServerErrHtml();
            exit(0);
        }*/


        FileUtil::requireService("ShopServ");
        FileUtil::requireService("ProductServ");
        $Product=new ProductServ();
        $pagesize=12;
        $service1=new ShopServ();
        $count=$service1->userProductCount($id,1);
        $brand=$service1->getShopBySid($id,1);
        if($brand===false||$brand==null){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        /*
                echo(json_encode($count));
                exit(0);
        */
        $pageUtil = new PageUtil($pagesize,$count,$page);
        $index = ($pageUtil->getCurrentPage()-1)*$pagesize;

        $productlist=$Product->getBrandProductList(0,$type,$sort,$index,$pagesize,$id);
        if($productlist===false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        FileUtil::requireService("CouponServ");
        $Coupon=new CouponServ();
        $params= array();
        $params['couponlist']=$Coupon->getCouponBysid($id,20,1);
        $params['brand']=$brand;
        $params['productlist']=$productlist;
        $params['count'] = $count;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/brand/seller.html?id='.$id.'&type='.$type.'&sort='.$sort;;
        return $params;
    }

}