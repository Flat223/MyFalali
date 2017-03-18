<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddRecruitServ extends BaseAction{
    public function action(){
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        $quarters = isset($_REQUEST['quarters'])?$_REQUEST['quarters']:"";
        $salary = isset($_REQUEST['salary'])?$_REQUEST['salary']:"";
        $type = isset($_REQUEST['type'])?$_REQUEST['type']:"";
        $place = isset($_REQUEST['place'])?$_REQUEST['place']:"";
        $content = isset($_REQUEST['content'])?$_REQUEST['content']:"";
        $require = isset($_REQUEST['req'])?$_REQUEST['req']:"";

        $data['id'] = $id;
        $data['quarters'] = $quarters;
        $data['salary'] = $salary;
        $data['type'] = $type;
        $data['place'] = $place;
        $data['content'] = $content;
        $data['require'] = $require;
        FileUtil::requireService('RecruitServ');
        $serv = new RecruitServ();
        $result = $serv->updateData($data);
        if($result === false){
            $ret['ret'] = -1;
            $ret['msg'] = '操作失败！';
            return $ret;
        }else{
            $ret['ret'] = 1;
            $ret['msg'] = '操作成功！';
            return $ret;
        }
    }
}

?>