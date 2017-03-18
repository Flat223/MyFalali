<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UploadidentityServ extends BaseAction{
    public function action(){
        $userid=isset($_POST['userid'])?$_POST['userid']:"";
        $cardnum=isset($_POST['cardnum'])?$_POST['cardnum']:"";
        $cardstyle=isset($_POST['cardstyle'])?$_POST['cardstyle']:"";
        $code=isset($_POST['code'])?$_POST['code']:"";
        $mobile=isset($_POST['mobile'])?$_POST['mobile']:"";
        $name=isset($_POST['name'])?$_POST['name']:"";
        $image=isset($_POST['cardimg'])?$_POST['cardimg']:"";
        $usage='identity';
        $table="identity";
        $insertColumns=array('mid','name','cardstyle','cardphoto','cardnum','mobile');
        $insertVals=array($userid,$name,$cardstyle,$image,$cardnum,$mobile);
        $DBAgent = DBAgent::getInstance();
        FileUtil::requireService("CheckVerifycodeServ");
        $check=new CheckVerifycodeServ();
        $re=$check->checkverify($mobile,$code,$usage);
        if($re['ret']=='1'){
            if($DBAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true)){
                $result['ret']='1';
                $result['msg']='提交成功';
            }else{
                $result['ret']='2';
                $result['ret']='提交失败';
            }
        }else{
            $result['ret']='0';
            $result['msg']='验证码错误';
        }
        exit(json_encode($result));
    }

}