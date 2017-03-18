<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditAdvertServ extends BaseAction{
	
	public function action(){

        $id = isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
        $desc = isset($_REQUEST['kw'])?trim($_REQUEST['kw']):"";
        $url = isset($_REQUEST['url'])?trim($_REQUEST['url']):"";

        $data['desc'] = $desc;
        $data['url'] = $url;
        $ret = array();
        FileUtil::requireService("NewsServ");
        $serv = new NewsServ();
        if($id != 0){
            $result = $serv ->updateAdvert($id,$data);
            if($result == false){
                $ret['ret'] = -1;
                $ret['msg'] = "修改失败！";
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = "修改成功！";
                return $ret;
            }
        }else{
            $result = $serv ->insertAdvert($data);
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