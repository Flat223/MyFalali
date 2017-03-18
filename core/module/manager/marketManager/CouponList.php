<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CouponList extends BaseAction{

    public function action(){
	    $ret['ret']=array();
		$admin=UserAgent::getAdmin();
		if($admin==null){
			$ret['ret']=0;
			$ret['msg']="未登录";
			return $ret;
		}
		$page=isset($_REQUEST['page'])?trim($_REQUEST['page']):1;
		$key=isset($_REQUEST['key'])?trim($_REQUEST['key']):"";
		FileUtil::requireService("CouponServ");
		$service=new CouponServ();
		$count=$service->getCashListCount($key);
		$cash=$service->getCashList($key,0,2,$page,10);
		$pages=ceil($count/10)-1;
		$params=array();
		foreach($cash as $key=>$s){
			$cash[$key]['start_time']=date("Y-m-d H:i:s", $s['start_time']);
			$cash[$key]['end_time']=date("Y-m-d H:i:s",$s['end_time']);
		}
		$params['count']=$count;
		$params['cash']=$cash;
		$params['pages']=$pages;
		$params['substyle'] = 'couponList';
        return $params;
    }
}