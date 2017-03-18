<?php
class ShopAddressServ{
	
	public function __construct(){
		
	}
	
	//店铺根据aid获取简单发货地址
    function getSimpleShopAddressById($sid,$aid){
		$table = "address_shop";
		$columns = "*";
		$conditionColumns = array('sid',"id",'status');
		$conditionVals = array($sid,$aid,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals);
    }
    
    //店铺获取简单发货地址
    function getSimpleShopAddress($sid){
		$table = "address_shop";
		$columns = "*";
		$conditionColumns = array('sid','status');
		$conditionVals = array($sid,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getRecordsFromTable($table,$columns,$conditionColumns,$conditionVals);
    }
	
	//店铺获取发货地址
    function getShopAddress($sid){
		$arr = array();
		$arr[] = $sid;
		$sql = "select 	case when b.id is null then '未知' else b.name end as province_name,
						case when c.id is null then '' else c.name end as city_name,
						case when d.id is null then '' else d.name end as country_name,a.* from #__address_shop a 
						left join #__area b on a.province = b.id 
						left join #__area c on a.city = c.id   
						left join #__area d on a.country = d.id   
						where a.sid = ? and a.status = 1 ";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
    }
    
    //店铺根据aid获取发货地址
    function getShopAddressById($sid,$aid){
		$arr = array();
		$arr[] = $sid;
		$arr[] = $aid;
		$sql = "select 	case when b.id is null then '未知' else b.name end as province_name,
						case when c.id is null then '' else c.name end as city_name,
						case when d.id is null then '' else d.name end as country_name,a.* from #__address_shop a 
						left join #__area b on a.province = b.id 
						left join #__area c on a.city = c.id   
						left join #__area d on a.country = d.id   
						where a.sid = ? and a.id = ? and a.status = 1 ";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
    }
    
    //取消默认发货
    function unsetShopAddressDefault($sid){
        $table="address_shop";
        $updateColumns=array('is_default');
        $updateVals=array(0);
        $conditionColumns=array('sid','is_default','status');
        $conditionVals=array($sid,1,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //删除发货地址
	public function deleteShopAddress($sid,$aid){
		$table = "address_shop";
		$updateColumns = array('status','is_default');
		$updateVals = array(0,0);
		$conditionColumns = array('sid','id','status');
		$conditionVals = array($sid,$aid,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//设置默认发货地址
	public function setShopAddressDefault($sid,$aid){
		$table = "address_shop";
		$updateColumns = array('is_default');
		$updateVals = array(1);
		$conditionColumns = array('sid','id','is_default');
		$conditionVals = array($sid,$aid,'0');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
    
    //添加发货地址
    function addShopAddress($sid,$address){
        $table="address_shop";
		$insertColumns = array("sid","status");
		$insertVals = array($sid,'1');
		if(isset($address['name'])){
			$insertColumns[] = 'name';
			$insertVals[] = $address['name'];
		}
		if(isset($address['mobile'])){
			$insertColumns[] = 'mobile';
			$insertVals[] = $address['mobile'];
		}
		if(isset($address['detail_address'])){
			$insertColumns[] = 'detail_address';
			$insertVals[] = $address['detail_address'];
		}
		if(isset($address['province'])){
			$insertColumns[] = 'province';
			$insertVals[] = $address['province'];
		}
		if(isset($address['country'])){
			$insertColumns[] = 'country';
			$insertVals[] = $address['country'];
		}
		if(isset($address['city'])){
			$insertColumns[] = 'city';
			$insertVals[] = $address['city'];
		}
		if(isset($address['is_default'])){
			$insertColumns[] = 'is_default';
			$insertVals[] = $address['is_default'];
		}
		
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }
    
    //修改发货地址
    function updateShopAddress($sid,$aid,$address){
        $table="address_shop";
        if(isset($address['name'])){
			$updateColumns[] = 'name';
			$updateVals[] = $address['name'];
		}
		if(isset($address['mobile'])){
			$updateColumns[] = 'mobile';
			$updateVals[] = $address['mobile'];
		}
		if(isset($address['detail_address'])){
			$updateColumns[] = 'detail_address';
			$updateVals[] = $address['detail_address'];
		}
		if(isset($address['province'])){
			$updateColumns[] = 'province';
			$updateVals[] = $address['province'];
		}
		if(isset($address['country'])){
			$updateColumns[] = 'country';
			$updateVals[] = $address['country'];
		}
		if(isset($address['city'])){
			$updateColumns[] = 'city';
			$updateVals[] = $address['city'];
		}
		if(isset($address['is_default'])){
			$updateColumns[] = 'is_default';
			$updateVals[] = $address['is_default'];
		}
		
		$conditionColumns = array('sid','id','status');
		$conditionVals = array($sid,$address['aid'],1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
}
?>