<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditShopServ extends BaseAction{
	
	public function action(){
        $user = UserAgent::getAdmin();
        if(empty($user)){
            $ret['ret'] = -2;
            $ret['msg'] = "尚未登录！";
            return $ret;
        }
        $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
        $address = isset($_REQUEST['address'])?trim($_REQUEST['address']):"";
        $member = isset($_REQUEST['member'])?trim($_REQUEST['member']):"";
        $phone = isset($_REQUEST['phone'])?trim($_REQUEST['phone']):"";
        FileUtil::requireService("ShopServ");
        $serv = new ShopServ();
        $member = $serv->getMemberByName($member);
        if($member == null || $member == false){
            $ret['ret'] = -4;
            $ret['msg'] = "该用户不存在！";
            return $ret;
        }
        $data['name'] = $name;
        $data['address'] = $address;
        $data['mid'] = $member['mid'];
        $data['phone'] = $phone;
        $ret = array();

        $shop = $serv->getShopById($member['mid']);
        if(empty($shop)){
            $result = $serv ->insertShop($data);
            if($result == false){
                $ret['ret'] = -1;
                $ret['msg'] = "修改失败！";
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = "修改成功！";
                return $ret;
            }
        }else{
            $ret['ret'] = -3;
            $ret['msg'] = "该用户已有店铺！";
            return $ret;
        }
    }
}