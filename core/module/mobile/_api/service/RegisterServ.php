<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RegisterServ extends BaseAction{
	public function action(){

        $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
        $phone = isset($_REQUEST['phone'])?trim($_REQUEST['phone']):"";
        $code = isset($_REQUEST['code'])?trim($_REQUEST['code']):"";
        $pw = isset($_REQUEST['pw'])?$_REQUEST['pw']:"";
        $usage = "repasswd";
        $ret = array();
        FileUtil::requireService("CheckVerifycodeServ");
        $check=new CheckVerifycodeServ();
        $result=$check->checkverify($phone,$code,$usage);
        if($result['ret'] != '1'){
            $ret['ret'] = -2;
            $ret['msg'] = "验证码错误！";
            return $ret;
        }

        $nu['name'] = $name;
        $nu['mobile'] = $phone;
        $nu['password'] = MD5($pw);

        FileUtil::requireService("RegistServ");
        $serv = new RegistServ();
        $user = $serv->getUserByTel($phone);
        if(!empty($user)){
            $ret['ret'] = -3;
            $ret['msg'] = "该手机号已注册！";
            return $ret;
        }
        $result = $serv->insertUser($nu);
        if($result != false){
            $ret['ret'] = 1;
            $ret['msg'] = "注册成功！";
            return $ret;
        }else{
            $ret['ret'] = -1;
            $ret['msg'] = "注册失败！";
            return $ret;
        }
	}
}