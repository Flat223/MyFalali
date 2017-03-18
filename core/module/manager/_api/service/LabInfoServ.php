<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabInfoServ extends BaseAction{
	
	public function action(){

        $labId = isset($_REQUEST['labid'])?trim($_REQUEST['labid']):0;
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
        $logo = isset($_REQUEST['logo'])?trim($_REQUEST['logo']):"";

        if(empty($name) || $labId == 0){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        FileUtil::requireService('LabShareReleaseServ');
        $supplyServ = new LabShareReleaseServ();
        $org = $supplyServ->getorgByName($org);
        if(empty($org)){
            $ret['ret'] = -2;
            $ret['msg'] = "无此机构！";
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
        $newlab['logo'] = $logo;

		FileUtil::requireService("LabDetailServ");
		$service = new LabDetailServ();
        $ret = array();
        $result = $service->updateLabInfo($labId,$newlab);
        if($result == false){
            $ret['ret'] = -1;
            $ret['msg'] = "修改失败！";
            return $ret;
        }else{
            $ret['ret'] = 1;
            $ret['msg'] = "修改成功！";
            return $ret;
        }
	}
}