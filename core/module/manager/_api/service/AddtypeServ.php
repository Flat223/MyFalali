<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddtypeServ extends BaseAction{
    public function action(){
        $admin = UserAgent::getAdmin();
        if(empty($admin)){
            $ret['ret'] = 0;
            $ret['msg'] = "尚未登录，登陆后重试";
            return $ret;
        }
        
        $name=isset($_REQUEST['name'])?$_REQUEST['name']:"";
        $parentid=isset($_REQUEST['parentid'])?$_REQUEST['parentid']:0;
        $level=isset($_REQUEST['level'])?$_REQUEST['level']:"";
        
        if(empty($name) || empty($level) || ($level > 1 && empty($parentid))){
	        $ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
        }
        
        if($level == 1){
	        $parentid = 0;
        }
        
        FileUtil::requireService("PropertyServ");
		$service = new PropertyServ();
        $proType = $service->getProTypeByName($name);	
        if($proType === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}
		if(!empty($proType)){
			$ret['ret'] = 0;
			$ret['msg'] = "分类名称已存在";
			return $ret;
		}
        
        $callback = $service->addPropertyType($name,$parentid,$level);	
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
			return $ret;
		}
        
        $ret['ret'] = 1;
		$ret['msg'] = "添加成功";
		return $ret;
    }
}