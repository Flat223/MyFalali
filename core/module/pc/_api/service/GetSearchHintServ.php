<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetSearchHintServ extends BaseAction{
	
	public function action(){
		$ret=array();
		$key=isset($_REQUEST['key'])?trim($_REQUEST['key']):"";
		$type=isset($_REQUEST['type'])?trim($_REQUEST['type']):1;
		if(empty($key)){		
		}
		FileUtil::requireService("GoodsServ");
		$service=new GoodsServ();
		if(preg_match('/[a-zA-Z]/', $key)){
			$names=$service->getSearchHintPin($key,$type);
			$ret['1']=1;
		}else{
			$names=$service->getSearchHint($key,$type);
			$ret['1']=2;
		}
	
		if($names===false){
			$ret['ret']=0;
			$ret['msg']="服务器错误，请稍后再试";
			return $ret;
		}
		if($type==1){
			foreach($names as $key=>$single){
				$names[$key]['pid']=md5($single['pid']);
			}
		}
		$ret['ret']=1;
		$ret['data']=$names;
		$ret['msg']="获取成功";
		return $ret;
	}
}