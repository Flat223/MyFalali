<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddCateServ extends BaseAction{
    public function action(){

        $id = isset($_REQUEST['id'])?$_REQUEST['id']:"";
        $cate = isset($_REQUEST['cate'])?$_REQUEST['cate']:"";
        FileUtil::requireService('ProductServ');
        $serv = new ProductServ();
        $type = $serv->geProductTypeByName($cate);
        $info = $serv->getDataByBId($id);
        if(empty($info)){
            $data['bid'] = $id;
            $data['ptid'] = $type['ptid'];
            $data['level'] = $type['level'];
            $result = $serv->addBrandTypeInfo($data);
            if($result == true){
                $ret['ret'] = 1;
                $ret['msg'] = "添加成功！";
                return $ret;
            }else{
                $ret['ret'] = -1;
                $ret['msg'] = "添加失败！";
                return $ret;
            }
        }else{
            $data['bid'] = $id;
            $data['ptid'] = $type['ptid'];
            $data['level'] = $type['level'];
            $data['info'] = $info;
            $result = $serv->updateBrandTypeInfo($data);
            if($result == true){
                $ret['ret'] = 1;
                $ret['msg'] = "添加成功！";
                return $ret;
            }else{
                $ret['ret'] = -1;
                $ret['msg'] = "添加失败！";
                return $ret;
            }
        }

        /*if($dbAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true)){
            $result['ret']='1';
            $result['msg']='成功';
            exit(json_encode($result));
        }else{
            $result['ret']='0';
            $result['msg']='失败';
            exit(json_encode($result));
        }*/
    }
}

?>