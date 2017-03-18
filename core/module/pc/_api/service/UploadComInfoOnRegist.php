<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UploadComInfoOnRegist extends BaseAction{
	
	public function action(){
		$mid=isset($_REQUEST['mid'])?trim($_REQUEST['mid']):"";
		$type=isset($_REQUEST['type'])?trim($_REQUEST['type']):"";
		$name=isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
		$image=isset($_REQUEST['image'])?trim($_REQUEST['image']):"";
		
		if(empty($mid) || empty($type)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}
		if($type != 2 && $type != 3){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误1";
			return $ret;
		}
		
		if(empty($name)){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写公司名称";
			return $ret;
		}
		if(empty($image)){
			$ret['ret'] = 0;
			$ret['msg'] = "请上传公司营业执照";
			return $ret;
		}
		
		FileUtil::requireService("UserServ");
		$service=new UserServ();
		$user = $service->getMemberByMid($mid);
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = '参数错误2';
			return $ret;
		}
		
		FileUtil::requireService("CompanyServ");
		$service=new CompanyServ();
        $callback = $service->uploadCompanyInfo($mid,$type,$name,$image);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "上传成功"; 
		return $ret;
	}
}

?>