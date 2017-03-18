<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/module/_baseClass/BaseAction.php';
class LabReleaseServ extends BaseAction{
	public function action(){
        $user = UserAgent::getUser();
        $ret = array();
        if($user == null || $user == false){
            $ret['ret'] = -3;
            $ret['msg'] = "尚未登录，登陆后重试";
            $ret['user'] = $user;
            return $ret;
        }

        $labName = isset($_REQUEST['labName'])?trim($_REQUEST['labName']):"";
        $manage = isset($_REQUEST['manage'])?trim($_REQUEST['manage']):"";
        $phone = isset($_REQUEST['phone'])?trim($_REQUEST['phone']):"";
        $validateCode = isset($_REQUEST['validate'])?trim($_REQUEST['validate']):"";
        $serviceArea = isset($_REQUEST['serviceArea'])?trim($_REQUEST['serviceArea']):"";
        $org = isset($_REQUEST['belong'])?trim($_REQUEST['belong']):"";
        $registerDate = isset($_REQUEST['registerDate'])?trim($_REQUEST['registerDate']):0;
        $labDesc = isset($_REQUEST['labDesc'])?trim($_REQUEST['labDesc']):"";
        $serviceRange = isset($_REQUEST['serviceRange'])?trim($_REQUEST['serviceRange']):"";
        $rules = isset($_REQUEST['rules'])?trim($_REQUEST['rules']):"";
        $research = isset($_REQUEST['research'])?trim($_REQUEST['research']):"";
        $city0 = isset($_REQUEST['city0'])?trim($_REQUEST['city0']):"";
        $city1 = isset($_REQUEST['city1'])?trim($_REQUEST['city1']):"";
        $city2 = isset($_REQUEST['city2'])?trim($_REQUEST['city2']):"";
        $address = isset($_REQUEST['address'])?trim($_REQUEST['address']):"";
        $instrument = isset($_REQUEST['instrument'])?trim($_REQUEST['instrument']):"";
        $usage='repasswd';
        if(empty($labName) || empty($manage) || empty($phone) || empty($validateCode)){
           FileUtil::loadServerErrHtml();
            exit(0);
        }

        FileUtil::requireService("CheckVerifycodeServ");
        $check=new CheckVerifycodeServ();
        $result=$check->checkverify($phone,$validateCode,$usage);
        if($result['ret'] != '1'){
            $ret['ret'] = -2;
            $ret['msg'] = "验证码错误！";
            return $ret;
        }

        FileUtil::requireService('LabShareReleaseServ');
        $supplyServ = new LabShareReleaseServ();
        $org = $supplyServ->getorgByName($org);
        if(empty($org)){
            $ret['ret'] = -4;
            $ret['msg'] = "无此机构！";
            return $ret;
        }
        $lab['name'] = $labName;
        $lab['manager'] = $manage;
        $lab['manager_phone'] = $phone;
        $lab['intro'] = $labDesc;
        $lab['service_area'] = $serviceArea;
        $lab['rules'] = $rules;
        $lab['mid'] = $user['mid'];
        $lab['time'] = $registerDate;
        $lab['org'] = $org['id'];
        $lab['address'] = $city0.$city1.$city2.$address;

        $result = $supplyServ->insertReleaseLab($lab);
        /*echo $result;
        exit(0);*/
        if($result != false){
            $reslab = $supplyServ->getReleaseLab($serviceArea);
            if($reslab != false){
                $supplyServ->insertServiceRange($serviceRange,$reslab['lab_id']);
                $supplyServ->insertInstrument($instrument,$reslab['lab_id']);
            }
        }
        if($result == false){
            $ret['ret'] = -1;
            $ret['msg'] = "发布失败！请稍后重试！";
            return $ret;
        }else{
            $ret['ret'] = 1;
            $ret['msg'] = "发布成功！请等待审核！";
            return $ret;
        }
	}
}