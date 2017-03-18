<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RepasswordBypasswordServ extends BaseAction
{

    public function action()
    {
        $mobile=isset($_POST['mobile'])?$_POST['mobile']:"";
        $oldpassword=isset($_POST['oldpassword'])?$_POST['oldpassword']:"";
        $newpassword=isset($_POST['newpassword'])?$_POST['newpassword']:"";
        $DBAgent = DBAgent::getInstance();

        $user=$_SESSION['user'];
        if(MD5($oldpassword)!=$user['password']){
            $ret['ret']='0';
            $ret['msg']='原始密码错误';
        }else {
            if ($DBAgent->updateRecords("member", array('password'), array(MD5($newpassword)), array('mobile'), array($mobile), $hasPrefix = true)) {
	            
	            FileUtil::requireService("UserServ");
				$service = new UserServ();
	            $user2 = $service->getMember($mobile,$newpassword);
                $_SESSION['user'] = $user2;
	            
                $ret['ret'] = '1';
                $ret['msg'] = '修改成功';
            } else {
                $ret['ret'] = '2';
                $ret['msg'] = '修改失败';
            }
        }
        exit(json_encode($ret));
    }

}