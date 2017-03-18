<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddCollectionServ extends BaseAction{
	
	public function action(){
		$ret=array();
		$user = UserAgent::getUser();
			if($user == null)
			{
				$ret['ret'] = -1;
				$ret['msg'] = "尚未登录，登陆后重试";
				return $ret;
			}
		$mid = $user['mid'];
		$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
		FileUtil::requireService("UserServ");
		$service=new UserServ();
		$collection=$service->getCollection($id,$mid,1);
		//return $collection;
		if($collection!=null){
			$ret['ret']=0;
			$ret['msg']="收藏已存在";
			return $ret;
		}
		$callback=$service->insertCollection($id,$mid,1);
		if($callback===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误，请稍后重试";
			return $ret;
		}
		$ret['ret']=1;
		$ret['msg']="添加成功";
		return $ret;
	}
}