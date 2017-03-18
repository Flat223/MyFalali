<?php
class AddressServ {
	public function __construct(){
		
	}
	
	//获取我的收货地址列表
	public function getMyAddressList($mid){
		$arr = array();
		$arr[] = $mid;
		$sql = "select 	case when b.id is null then '未知' else b.name end as province_name,
						case when c.id is null then '' else c.name end as city_name,
						case when d.id is null then '' else d.name end as country_name,a.* from #__address a 
						left join #__area b on a.province = b.id 
						left join #__area c on a.city = c.id   
						left join #__area d on a.country = d.id   
						where a.mid = ? and a.status = 1 ";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	
	//根据id收货地址
	public function getAddressById($mid,$aid){
		$arr = array();
		$arr[] = $mid;
		$arr[] = $aid;
		$sql = "select 	case when b.id is null then '未知' else b.name end as province_name,
						case when c.id is null then '' else c.name end as city_name,
						case when d.id is null then '' else d.name end as country_name,a.* from #__address a 
						left join #__area b on a.province = b.id 
						left join #__area c on a.city = c.id   
						left join #__area d on a.country = d.id   
						where a.mid = ? and a.id = ? and a.status = 1 ";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
	
	//添加收货地址
	public function addAddress($mid,$address){
		$table = "address";	
		$insertColumns = array("mid","status");
		$insertVals = array($mid,'1');
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
	
	//修改收货地址
	public function updateAddress($mid,$address){
		$table = "address";
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
		
		$conditionColumns = array('mid','id','status');
		$conditionVals = array($mid,$address['id'],'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//删除收货地址
	public function deleteAddress($mid,$aid){
		$table = "address";
		$updateColumns = array('status','is_default');
		$updateVals = array('0','0');
		$conditionColumns = array('mid','id','status');
		$conditionVals = array($mid,$aid,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//取消默认地址
	public function unsetAddressDefault($mid){
		$arr = array();
		$arr[] = $mid;
		$sql = "update #__address set is_default = 0 where mid = ? and is_default = 1 and status = 1 ";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
	}
	
	//设置默认地址
	public function setAddressDefault($mid,$aid){
		$arr = array();
		$arr[] = $mid;
		$arr[] = $aid;
		$sql = "update #__address set is_default = 1 where mid = ? and id = ? and is_default = 0 and status = 1 ";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
	}
}
?>