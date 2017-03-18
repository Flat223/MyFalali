<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UploadPro extends BaseAction{
	
	public function action(){
    	$id = isset($_GET['id'])?trim($_GET['id']):1;
		if(!Common::isInteger($id) || $id < 0){
			$id = 1;
		}
		
		$dbAgent = DBAgent::getInstance();
		
		$arr = array();
		$arr[] = $id;
		$sql = "select * from #__productxiyu1 where id = ? and status = 1 ";
		
		$preProduct = $dbAgent->querySingleRecord($sql,$arr);
		if($preProduct === false){
			echo '错误0';
			
			$id ++;
			header('Location: ../../test/uploadPro.html?id='.$id);
			exit(0);
		}
		if(empty($preProduct)){
			echo 'waiting...';
			$id ++;
			header('Location: ../../test/uploadPro.html?id='.$id);
			exit(0);
		}
		
		$pre_pid = $preProduct['id'];
		$spec = json_decode(trim($preProduct['spec']),true);
		if(empty($spec['CASNo.'])){						//非试剂不插入;
			$pre_pid ++;
			header('Location: ../../test/uploadPro.html?id='.$pre_pid);
			exit(0);
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
		
		$model = isset($preProduct['model'])?trim($preProduct['model']):'';
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
			echo '错误1';
			
			$sql = "update #__productxiyu1 set status = 3 where id = '$pre_pid' and status = 1 ";
			$dbAgent->query($sql,array());
			
			$pre_pid ++;
			header('Location: ../../test/uploadPro.html?id='.$pre_pid);
			exit(0);
		}
		
		$sql = "select pid from #__product_addTest where from_pid = '$pre_pid' ";
		$justInsertPro = $dbAgent->querySingleRecord($sql,array());
		if($justInsertPro === false){
			echo '错误2';
			exit(0);
		}
		
		$pid = $justInsertPro['pid'];
		$sql = "insert #__sku_temp (pid,price,inventory,inventory_warn,status) values ('$pid','$price',1,1,1) ";
		$callback = $dbAgent->query($sql,array());	
		if($callback === false){
			echo '错误3';
			exit(0);
		}			
		
		$sql = "update #__productxiyu1 set status = 2 where id = '$pre_pid' and status = 1 ";
		$callback = $dbAgent->query($sql,array());
		if($callback === false){
			echo '错误4';
			exit(0);
		}
		
		$params['id'] = $pre_pid;
		return $params;
    }
}