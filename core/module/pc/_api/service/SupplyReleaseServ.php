<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/module/_baseClass/BaseAction.php';
class SupplyReleaseServ extends BaseAction{
	public function action(){
        $user = UserAgent::getUser();
        if(empty($user)){
            $ret['ret'] = -3;
            $ret['msg'] = "请先登录！";
            return $ret;
        }

        $type = isset($_REQUEST['type'])?trim($_REQUEST['type']):0;
        $trade = isset($_REQUEST['trade'])?trim($_REQUEST['trade']):"";
        $title = isset($_REQUEST['title'])?trim($_REQUEST['title']):"";
        $endDate = isset($_REQUEST['endDate'])?trim($_REQUEST['endDate']):0;
        $linkermen = isset($_REQUEST['linkermen'])?trim($_REQUEST['linkermen']):"";
        $phone = isset($_REQUEST['phone'])?trim($_REQUEST['phone']):0;
        $validateCode = isset($_REQUEST['validateCode'])?trim($_REQUEST['validateCode']):0;
        $mail = isset($_REQUEST['mail'])?trim($_REQUEST['mail']):"";
        $category = isset($_REQUEST['category'])?trim($_REQUEST['category']):0;
        $desc = isset($_REQUEST['desc'])?trim($_REQUEST['desc']):"";
        $usage='repasswd';
        if(empty($type) || empty($title) || empty($endDate) || empty($linkermen) ||empty($phone) || empty($validateCode)){
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
        $supply['type'] = (int)$type;
        $supply['trade'] = $trade;
        $supply['title'] = $title;
        $supply['endDate'] = (int)$endDate;
        $supply['linkman'] = $linkermen;
        $supply['phone'] = $phone;
        $supply['mail'] = $mail;
        $supply['category'] = (int)$category;
        $supply['description'] = $desc;

        FileUtil::requireService('SupplyBuyReleaseServ');
        $supplyServ = new SupplyBuyReleaseServ();
        $result = $supplyServ->insertSupplyInfo($supply);
      /*  echo $result."状态";
        exit(0);*/
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