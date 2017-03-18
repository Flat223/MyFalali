<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';

class TestUploadProServ extends BaseAction {
	
	public function action(){
		
		$id = isset($_POST['id'])?trim($_POST['id']):1;
		if(!Common::isInteger($id) || $id < 0){
			$id = 1;
		}
		
		$dbAgent = DBAgent::getInstance();
		
		$arr = array();
		$arr[] = $id;
		$sql = "select * from #__productxiyu1 where id = ? and status = 1 ";
		
		$preProduct = $dbAgent->querySingleRecord($sql,$arr);
		
		if(empty($preProduct)){
			$ret['ret'] = 0;
			$ret['id'] = $id;
			return $ret;
		}
		
		$spec = json_decode(trim($preProduct['spec']),true);
		if(empty($spec['CASNo.'])){						//非试剂不插入;
			$ret['ret'] = 0;
			$ret['id'] = $id;
			return $ret;
		} 	
		$CASnumber = trim($spec['CASNo.']);
			
		$brand = trim($preProduct['brand']);
		$sql = "select brand_id from #__brand where name = '$brand' and status = 1 ";
		$brandInfo = $dbAgent->querySingleRecord($sql,array());
		$brand_id = !empty($brandInfo) ? $brandInfo['brand_id'] : 0;
		
		$EnglishName = "";
		$purity = "";
		$goods_code = "";
		$size = "";
		
		$preEnglishName = isset($spec['英文名称'])?trim($spec['英文名称']):'';
		if(!empty($preEnglishName)){
			$EnglishName = $preEnglishName;
		}
		
		$preSize = isset($spec['规格'])?trim($spec['规格']):'';
		if(!empty($preSize)){
			$purity = $preSize;	//含量
		}
		
		$model = $preProduct['model'];
		if(!empty($model)){
			$goods_code = $model;			//货号
			if(strstr($model,'-')){
				$modelArray = explode('-',$model);		//规格
				$size = trim($modelArray[1]);
			}
		}
		
		$other_info = trim($preProduct['spec']);
		$packing_info = trim($preProduct['packaging']);
       	$name = $preProduct['name'];
        $alias = $preProduct['name'];
        
        $ptid = 120;
       	$first_tid = 93;
        $second_tid = 115;
        $third_tid = 120;
        $forth_tid = 0;
        $fifth_tid = 0;
        
        $images = "/images/temp_pc/goods.jpg";
        $packing = "独立包装";
        $unit = "瓶";
        $producer = "国产";
        $shelf_life = "三个月以上";
        $store = "常温保存";
        $traffic = "常温运输";
        $time = time();
		$pre_pid = $preProduct['id'];
		$price = $preProduct['price'];
		
    	$sql = "insert into #__product_addTest(
    			ptid,first_tid,second_tid,third_tid,forth_tid,fifth_tid,brand,brand_id,price
				,name,alias,EnglishName,images,CASnumber,purity,size,goods_code,other_info
				,packing_info,packing,unit,producer,shelf_life,store,traffic,time,from_pid,status
			) values (
			'$ptid','$first_tid','$second_tid','$third_tid','$forth_tid','$fifth_tid','$brand','$brand_id','$price'
			,'$name','$alias','$EnglishName','$images','$CASnumber','$purity','$size','$goods_code','$other_info'
			,'$packing_info','$packing','$unit','$producer','$shelf_life','$store','$traffic','$time','$pre_pid',2
			) ";
			
		$callback = $dbAgent->query($sql,array());
		if($callback === false){
			
			$sql = "update #__productxiyu1 set status = 3 where id = '$pre_pid' and status = 1 ";
			$dbAgent->query($sql,array());
			
			$ret['ret'] = 0;
			$ret['msg'] = '错误1';
			$ret['id'] = $pre_pid;
			return $ret;
		}
		
		$sql = "select pid from #__product_addTest where from_pid = '$pre_pid' ";
		$justInsertPro = $dbAgent->querySingleRecord($sql,array());
		if($justInsertPro === false){
			$ret['ret'] = -1;
			$ret['msg'] = '错误2';
			return $ret;
		}
		
		$pid = $justInsertPro['pid'];
		$sql = "insert #__sku_temp (pid,price,inventory,inventory_warn,status) values ('$pid','$price',1,1,1) ";
		$callback = $dbAgent->query($sql,array());	
		if($callback === false){
			$ret['ret'] = -1;
			$ret['msg'] = '错误3';
			return $ret;
		}			
		
		$id = $preProduct['id'];
		$arr = array();
		$arr[] = $id;
		$sql = "update #__productxiyu1 set status = 2 where id = ? and status = 1 ";
		$callback = $dbAgent->query($sql,$arr);
		if($callback === false){
			$ret['ret'] = -1;
			$ret['msg'] = '错误4';
			return $ret;
		}
		
		$ret['ret'] = 1;
		$ret['id'] = $id;
		return $ret;
	}
}
	
?>