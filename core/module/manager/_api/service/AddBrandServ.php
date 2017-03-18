<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddBrandServ extends BaseAction{
    public function action(){
        $id = isset($_POST['id'])?trim($_POST['id']):0;
        $logo = isset($_POST['logo'])?trim($_POST['logo']):"";
        $name = isset($_POST['name'])?trim($_POST['name']):"";
        $sort = isset($_POST['sort'])?trim($_POST['sort']):"";
        $intro = isset($_POST['intro'])?trim($_POST['intro']):"";

        $brand['image'] = $logo;
        $brand['name'] = $name;
        $brand['sort'] = $sort;
        $brand['intro'] = $intro;
        FileUtil::requireService('BrandServ');
        $serv = new BrandServ();
        $ret = array();
        if($id == 0){
	        $num=$serv->getBrandCountE($name);
	        if($num>0){
		        $ret['ret']=-1;
		        $ret['msg']="该品牌已存在";
		        return $ret;
	        }
            $result = $serv->addBrand($brand);
            if($result == false){
                $ret['ret'] = -1;
                $ret['msg'] = "添加失败！";
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = "添加成功！";
                return $ret;
            }
        }else{
            $result = $serv->updateBrand($id,$brand);
            if($result == false){
                $ret['ret'] = -1;
                $ret['msg'] = "修改失败！";
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = "修改成功！";
                return $ret;
            }
        }
    }
}
?>