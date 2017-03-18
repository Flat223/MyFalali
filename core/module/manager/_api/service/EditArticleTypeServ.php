<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditArticleTypeServ extends BaseAction{
    public function action(){

        $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        $name = isset($_REQUEST['name'])?$_REQUEST['name']:"";
        $flag = isset($_REQUEST['flag'])?$_REQUEST['flag']:0;
        FileUtil::requireService('ArticleServ');
        $serv = new ArticleServ();
        $ret = array();
        if($flag == 1){
        $result = $serv->deleteArticleTypeById($id);
            if($result == false){
                $ret['ret'] = -1;
                $ret['msg'] = '删除失败！';
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = '删除成功！';
                return $ret;
            }
        }else if($flag == 2){
            $data['id'] = $id;
            $data['name'] = $name;
            $result = $serv->updateArticleTypeById($data);
            if($result == false){
                $ret['ret'] = -1;
                $ret['msg'] = '修改失败！';
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = '修改成功！';
                return $ret;
            }
        }else{
            $data['id'] = $id;
            $data['name'] = $name;
            $result = $serv->insertArticleType($data);
            if($result == false){
                $ret['ret'] = -1;
                $ret['msg'] = '添加失败！';
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = '添加成功！';
                return $ret;
            }
        }
    }
}

?>