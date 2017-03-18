<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdateshopinfoServ extends BaseAction{

    public function action(){
        $sid=isset($_POST['sid'])?$_POST['sid']:"";
        $address=isset($_POST['address'])?$_POST['address']:"";
        $city_id=isset($_POST['district'])?$_POST['city_id']:0;
        $district=isset($_POST['district'])?$_POST['district']:0;
        $province_id=isset($_POST['province_id'])?$_POST['province_id']:0;
        $zip=isset($_POST['zip'])?$_POST['zip']:"";
        $shop_name=isset($_POST['shop_name'])?$_POST['shop_name']:"";
        $logo=isset($_POST['logo'])?$_POST['logo']:"";
        $mobile=isset($_POST['mobile'])?$_POST['mobile']:"";
//        exit(json_encode($_POST));
        FileUtil::requireService("ShopServ");
        $shop=new ShopServ();
        $res=$shop->updateshop($sid,$address,$city_id,$district,$province_id,$zip,$shop_name,$logo,$mobile);
         if($res['ret']=='1') {
            $result['ret']=1;
            $result['msg']="成功";
        }else{
            $result['ret']=0;
            $result['msg']='失败';
        }
        exit(json_encode($result));
    }
}

?>