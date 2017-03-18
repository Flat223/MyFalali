<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddJournalServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getAdmin();
        if(empty($user)){
            $ret['ret'] = 0;
            $ret['msg'] = "请先登录！";
            return $ret;
        }
        $content = isset($_REQUEST['content'])?trim($_REQUEST['content']):"";
        $id = isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
        FileUtil::requireService("UserSignServ");
		$serv = new UserSignServ();
        if($id == 0){
            $data['aid'] = $user['aid'];
            $data['name'] = $user['name'];
            $data['content'] = $content;
            $result = $serv->addJournal($data);
            if($result === false){
                $ret['ret'] = 0;
                $ret['msg'] = "添加失败！稍后重试！";
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = "发布成功！";
                return $ret;
            }
        }else{
            $result = $serv->updateJournal($id,$content);
            if($result === false){
                $ret['ret'] = 0;
                $ret['msg'] = "更新失败！稍后重试！";
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = "更新成功！";
                return $ret;
            }
        }
	}
}
?>