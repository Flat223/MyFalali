<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditThemeServ extends BaseAction{
    public function action(){

        $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        $img = isset($_REQUEST['img'])?$_REQUEST['img']:"";
        $name = isset($_REQUEST['name'])?$_REQUEST['name']:"";
        FileUtil::requireService('BannerServ');
        $serv = new BannerServ();

        $data['name'] = $name;
        $data['img'] = $img;
        $data['id'] = $id;

        $result = $serv->updateThemeImg($data);
        if($result == false){
            $ret['ret'] = -1;
            $ret['msg'] = "更新失败！";
            return $ret;
        }else{
            $ret['ret'] = 1;
            $ret['msg'] = "更新成功！";
            return $ret;
        }
    }
}

?>