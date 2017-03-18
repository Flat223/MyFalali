<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditLabServiceServ extends BaseAction{
	
	public function action(){

        $id = isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
        $type = isset($_REQUEST['type'])?trim($_REQUEST['type']):0;
        $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
        $cycle = isset($_REQUEST['cycle'])?trim($_REQUEST['cycle']):"";
        $price = isset($_REQUEST['price'])?trim($_REQUEST['price']):0;
        $lab = isset($_REQUEST['lab'])?trim($_REQUEST['lab']):"";

        FileUtil::requireService('LabServiceServ');
        $infoServ = new LabServiceServ();
        $lb = $infoServ->getLabByN($lab);
        if(empty($lb)){
            $ret['ret'] = -2;
            $ret['msg'] = "无此实验室！";
            return $ret;
        }
        $data['name'] = $name;
        $data['cycle'] = $cycle;
        $data['price'] = $price;
        $data['labId'] = $lb['lab_id'];
        $ret = array();
        if($type == 0){
            $result = $infoServ->updateService($id,$data);
            if($result == false){
                $ret['ret'] = -1;
                $ret['msg'] = "修改失败！";
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = "修改成功！";
                return $ret;
            }
        }else if($type == 1){
            $result = $infoServ->insertServiceRange($data);
            if($result == false){
                $ret['ret'] = -1;
                $ret['msg'] = "添加失败！";
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = "添加成功！";
                return $ret;
            }
        }else if($type == 2){
            $result = $infoServ->insertInstrument($data);
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
}