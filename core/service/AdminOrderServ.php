<?php
class AdminOrderServ{
	
	//后台查看所有订单数量;
	function getAllOrderCount($orderType,$condition){//subtype:1,科研人员 2,采购人员 0,公司本身
		$arr = array();
	    $arr[] = $orderType;
	    $sql = "select count(*) as count from #__order a left join #__member b on a.order_from_mid = b.mid left join #__member c on a.payer_mid = c.mid where a.type = ? and a.status = 1 ";
	    return $this->searchByCondition($condition,$arr,$sql,1,0,0);   
	}
	
	//后台查看所有订单
	function getAllOrder($orderType,$condition,$index,$size){
		$arr = array();
		$arr[] = $orderType;
		$sql = "select b.nickname as applier,c.nickname as payer,a.* from #__order a left join #__member b on a.order_from_mid = b.mid left join #__member c on a.payer_mid = c.mid where a.type = ? and a.status = 1 ";
		return $this->searchByCondition($condition,$arr,$sql,2,$index,$size); 
	} 
	
	//查询该订单下的所有商品
    function getProductArrayByOrder($order_code){
	    $arr = array();
	    $arr[] = $order_code;
	    $sql = "select b.shop_name as shop,a.* from #__order_product a left join #__shop b on a.sid = b.sid where a.order_code = ? and a.status = 1 ";
	    $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }
    
    //根据订单号搜索订单
	function getOrderByOrderCode($order_code,$agree,$state,$orderType){
		$arr = array();
		$arr[] = $order_code;
		$arr[] = $orderType;
		$sql = "select b.nickname as applier,c.nickname as payer,a.* from #__order a left join #__member b on a.order_from_mid = b.mid left join #__member c on a.payer_mid = c.mid where a.order_code = ? and a.type = ? and a.status = 1 ";
		if($agree != "" && $agree != 0){
			$arr[] = $agree;
			$sql .= "and a.agree = ? ";
		}
		if($state != "" && $state != 0){
			$arr[] = $state;
			$sql .= "and a.state = ? ";
		}
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
	
	//根据商品名称搜索订单
    function getOrderByProduct($product,$orderType,$condition){
	   	$arr = array();
	   	$arr[] = $orderType;
	   	$sql = "select distinct a.order_code,a.order_code from #__order a join #__member b on a.order_from_mid = b.mid join #__order_product c on a.order_code = c.order_code where a.type = ? and a.status = 1 ";
	   	if($product != ""){
		   	$arr[] = '%'.$product.'%';
		   	$sql .= "and c.name like ? ";
	   	}
	   	if($condition != ""){
			return $this->searchByCondition($condition,$arr,$sql,3,0,0);   	
	   	} else {
		   	$sql.= "order by a.time desc ";
		   	$dbAgent=DBAgent::getInstance();
			return $dbAgent->queryRecords($sql,$arr);
	   	}  	
   	}
	
	//查找订单工具方法
	function searchByCondition($condition,$arr,$sql,$type,$index,$size){//type 1:查数量 2:查订单 3:搜商品
		if(isset($condition['applier'])){
		    $arr[] ='%'.$condition['applier'].'%';
			$sql.="and b.name like ? ";
		}
	    if(isset($condition['payer'])){
		    $arr[] ='%'.$condition['payer'].'%';
			$sql.="and c.name like ? ";
		}
		if(isset($condition['start_time'])){
		    $arr[] = $condition['start_time'];
			$sql.="and a.time >= ? ";
		}
		if(isset($condition['end_time'])){
		    $arr[] = $condition['end_time'];
			$sql.="and a.time <= ? ";
		}
		if(isset($condition['agree'])){
		    $arr[] = $condition['agree'];
			$sql.="and a.agree = ? ";
		}
		if(isset($condition['state'])){
		    $arr[] = $condition['state'];
			$sql.="and a.agree = 2 and a.state = ? ";
		}
		
		$dbAgent=DBAgent::getInstance();
		if($type == 1){
			$result = $dbAgent->querySingleRecord($sql,$arr);
			if($result === false){
				return false;
			}
			return $result['count'];
		} else if($type == 2){
			$sql.="order by a.time desc limit $index,$size ";
	        return $dbAgent->queryRecords($sql,$arr);
		} else {
			$sql.= "order by a.time desc ";
			return $dbAgent->queryRecords($sql,$arr);
		}	
    }
}	
?>
