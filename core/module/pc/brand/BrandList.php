<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class BrandList extends BaseAction{
	
	public function action(){
		FileUtil::requireService("BrandServ");
		$service=new BrandServ();
		$brandlist=$service->getBrandList();
		$arabic=array();
		for($i=0;$i<count($brandlist);$i++){
			if(in_array(strtoupper($brandlist[$i]['sort_letter']), $arabic)===false){
				$arabic[]=strtoupper($brandlist[$i]['sort_letter']);
			}
		}
		sort($arabic);
		$brandlast=array();
		for($a=0;$a<count($arabic);$a++){
			$temparr=array();
			for($b=0;$b<count($brandlist);$b++){
				if($arabic[$a]==strtoupper($brandlist[$b]['sort_letter'])){
					$temparr[]=$brandlist[$b];
				}
			}
			$brandlast[$a]['arabic']=$arabic[$a];
			$brandlast[$a]['brandlist']=$temparr;
		}
		$params=array();
		$params['brandlist']=$brandlast;
		return $params;
	}
	
}