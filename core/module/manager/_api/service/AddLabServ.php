<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddLabServ extends BaseAction{
	
	public function action(){
        $user = UserAgent::getAdmin();
        if($user == null || $user == false){
            $ret['ret'] = -2;
            $ret['msg'] = "尚未登录，登陆后重试";
            $ret['user'] = $user;
            return $ret;
        }

        $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
        $manager = isset($_REQUEST['manager'])?trim($_REQUEST['manager']):"";
        $phone = isset($_REQUEST['phone'])?trim($_REQUEST['phone']):"";
        $zyx = isset($_REQUEST['zyx'])?trim($_REQUEST['zyx']):0;
        $kyhj = isset($_REQUEST['kyhj'])?trim($_REQUEST['kyhj']):0;
        $jlx = isset($_REQUEST['jlx'])?trim($_REQUEST['jlx']):0;
        $kyry = isset($_REQUEST['kyry'])?trim($_REQUEST['kyry']):0;
        $view_num = isset($_REQUEST['view_num'])?trim($_REQUEST['view_num']):0;
        $service_area = isset($_REQUEST['service_area'])?trim($_REQUEST['service_area']):"";
        $type = isset($_REQUEST['type'])?trim($_REQUEST['type']):0;
        $org = isset($_REQUEST['org'])?trim($_REQUEST['org']):"";
        $address = isset($_REQUEST['address'])?trim($_REQUEST['address']):"";
        $rules = isset($_REQUEST['rules'])?trim($_REQUEST['rules']):"";
        $intro = isset($_REQUEST['intro'])?trim($_REQUEST['intro']):"";
        $lat = isset($_REQUEST['lat'])?trim($_REQUEST['lat']):0;
        $lon = isset($_REQUEST['lon'])?trim($_REQUEST['lon']):0;
        $total = isset($_REQUEST['total'])?trim($_REQUEST['total']):0;
        $registerDate = isset($_REQUEST['registerDate'])?trim($_REQUEST['registerDate']):0;
        $logo = isset($_REQUEST['logo'])?trim($_REQUEST['logo']):0;

        FileUtil::requireService('ShopServ');
        $serv = new ShopServ();
        $org = $serv->getOrg($org);
        if(empty($org)){
            $ret['ret'] = -3;
            $ret['msg'] = "无此公司！";
            return $ret;
        }
        $newlab = array();
        $newlab['name'] = $name;
        $newlab['manager'] = $manager;
        $newlab['phone'] = $phone;
        $newlab['zyx'] = $zyx;
        $newlab['kyhj'] = $kyhj;
        $newlab['jlx'] = $jlx;
        $newlab['kyry'] = $kyry;
        $newlab['view_num'] = $view_num;
        $newlab['service_area'] = $service_area;
        $newlab['type'] = $type;
        $newlab['org'] = $org['id'];
        $newlab['address'] = $address;
        $newlab['rules'] = $rules;
        $newlab['intro'] = $intro;
        $newlab['lat'] = $lat;
        $newlab['lon'] = $lon;
        $newlab['stars'] = $total;
        $newlab['time'] = $registerDate;
        $newlab['mid'] = $user['aid'];
        $newlab['logo'] = $logo;
		FileUtil::requireService("LabListServ");
		$service = new LabListServ();
        $ret = array();
        $result = $service->addLab($newlab);
        if($result == false){
            $ret['ret'] = -1;
            $ret['msg'] = "添加失败！";
            return $ret;
        }else{
            $ret['ret'] = 1;
            $ret['msg'] = "添加成功！";
            return $ret;
        }
	}
}