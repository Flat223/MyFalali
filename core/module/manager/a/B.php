<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class B extends BaseAction{
	
	public function action(){
			echo('haha');
			
/*
		$sql = "select * from #__product_test ";
		$dbAgent = DBAgent::getInstance();
		$result = $dbAgent->queryRecords($sql);
		$products = array();
		foreach($result as $val){
			$product = array();
			$product['name'] = $val['name'];
			$spec = json_decode($val['spec'],true);
			$product['brand'] = $val['brand'];
			$product['en_name'] = empty($spec['英文名称'])?'':$spec['英文名称'];
			$product['model'] = $val['model'];
			$product['packaging'] = $val['packaging'];
			$product['cunchu'] = empty($spec['保存条件(℃)'])?'':$spec['保存条件(℃)'];
			$product['price'] = $val['price'];
			$product['code'] = empty($spec['CASNo.'])?'':$spec['CASNo.'];
			$products[] = $product;
		}
		foreach($products as $val){
			$sql = "insert into #__product_test2(mingcheng,yinwen,xinghao,baozhuang,cunchu,paijia,bianhao)values(?,?,?,?,?,?,?) ";
			$dbAgent->query($sql,array($val['name'],$val['en_name'],$val['model'],$val['packaging'],$val['cunchu'],$val['price'],$val['code']));
		}
*/
		
		
		exit(0);
	}
	
	
	function aaa(){
		
		
		
	}
	
	
}