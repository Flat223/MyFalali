<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RepasswordBycodeServ extends BaseAction{

    public function action(){
        $mobile=isset($_POST['mobile'])?$_POST['mobile']:'';
        $messagecode=isset($_POST['messagecode'])?$_POST['messagecode']:'';
        $newpassword=isset($_POST['newpassword'])?$_POST['newpassword']:'';
        $usage='repasswd';
        $DBAgent = DBAgent::getInstance();
        FileUtil::requireService("CheckVerifycodeServ");
        $check=new CheckVerifycodeServ();
        $result=$check->checkverify($mobile,$messagecode,$usage);
        if($result['ret']=='1'){
            if($DBAgent->updateRecords("member",array('password'),array(MD5($newpassword)),array('mobile'),array($mobile),$hasPrefix=true)){
	            
	            FileUtil::requireService("UserServ");
				$service = new UserServ();
	            $user2 = $service->getMember($mobile,$newpassword);
                $_SESSION['user'] = $user2;
	            
                $ret['ret']='1';
                $ret['msg']='修改成功';
            }else{
                $ret['ret']='2';
                $ret['msg']='修改失败';
            }
        }else{
            $ret['ret']='0';
            $ret['msg']='验证码错误';
        }

        exit(json_encode($ret));
    }


}