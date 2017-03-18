<?php
class ShopOrderServ{
	
	//获取店家订单数量
	function getShopOrderCount($sid,$condition){
	    $arr = array();
	    $arr[] = $sid;
	    $sql = "select count(*) as count from #__order where sid = ? and status = 1 ";
	    if(isset($condition['start_time'])){
		    $arr[] = $condition['start_time'];
			$sql.="and time >= ? ";
		}
		if(isset($condition['end_time'])){
		    $arr[] = $condition['end_time'];
			$sql.="and time <= ? ";
		}
		if(isset($condition['state'])){
		    $arr[] = $condition['state'];
			$sql.= "and state = ? ";
		}
		
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->querySingleRecord($sql,$arr);
		if($result === false){
			return false;
		}
		return $result['count'];
    }
    
	//分页获取店家订单数量
    function getAllShopOrder($sid,$condition,$index,$size){
	    $arr = array();
	    $arr[] = $sid;
	    $sql = "select * from #__order where sid = ? and status = 1 ";
	    if(isset($condition['start_time'])){
		    $arr[] = $condition['start_time'];
			$sql.="and time >= ? ";
		}
		if(isset($condition['end_time'])){
		    $arr[] = $condition['end_time'];
			$sql.="and time <= ? ";
		}
		if(isset($condition['state'])){
		    $arr[] = $condition['state'];
			$sql.= "and state = ? ";
		}
		$sql .= "order by time limit $index,$size ";
		$dbAgent=DBAgent::getInstance();
	    return $result = $dbAgent->queryRecords($sql,$arr);
    }
	
	//根据订单号搜索店家订单
	function getShopOrderByOrderCode($sid,$order_code,$state){
		$arr = array();
		$arr[] = $sid;
		$arr[] = $order_code;
		$sql = "select * from #__order where sid = ? and order_code = ? and status = 1 ";
		if(!empty($state)){
			$arr[] = $state;
			$sql.= "and state = ? ";
		}
		$dbAgent=DBAgent::getInstance();
		return $order=$dbAgent->querySingleRecord($sql,$arr);
	}
	
	//根据商品名称搜索店家订单数量
    function getShopOrderCountByProduct($sid,$product){
	   	$arr = array();
	   	$arr[] = $sid;
	   	$sql = "select distinct a.order_code from #__order a left join #__order_product b on a.order_code = b.order_code and b.status = 1 where a.sid = ? ";
	   	if($product != ""){
		   	$arr[] = '%'.$product.'%';
		   	$sql .= "and b.name like ? ";
	   	}
	    if(!empty($state)){
			$arr[] = $state;
			$sql.= "and a.state = ? ";
		}
	   	$dbAgent=DBAgent::getInstance();
	   	$result = $dbAgent->queryRecords($sql,$arr); 
	    if($result === false){
			return false;
		}
		return count($result); 	
   	}

	 //根据商品名称搜索店家订单
    function getShopOrderByProduct($sid,$product,$state,$index,$size){
	   	$arr = array();
	   	$arr[] = $sid;
	   	$sql = "select distinct a.order_code,a.* from #__order a left join #__order_product b on a.order_code = b.order_code and b.status = 1 where a.sid = ? ";
	   	if($product != ""){
		   	$arr[] = '%'.$product.'%';
		   	$sql .= "and b.name like ? ";
	   	}
	    if(!empty($state)){
			$arr[] = $state;
			$sql.= "and a.state = ? ";
		}
		$sql .= "order by a.time limit $index,$size ";
	   	$dbAgent=DBAgent::getInstance();
	    return $result = $dbAgent->queryRecords($sql,$arr);  	
   	}
	
   //查看订单详情
   function getShopOrderDetailByCode($order_code){
	   $arr = array();
	   $arr[] = $order_code;
	   $sql = "select * from #__order where order_code = ? and status = 1 ";
	   $dbAgent=DBAgent::getInstance();
	   return $dbAgent->querySingleRecord($sql,$arr);
   }
   
   //查询该订单下的所有商品
    function getProductByOrdercode($order_code){
	    $arr = array();
	    $arr[] = $order_code;
	    $sql = "select * from #__order_product where order_code = ? and status = 1 ";
	    $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

	//店家发货
    function confirmSendGoods($sid,$order_code,$logistics,$logistics_code){
	    $table = "order";
		$updateColumns = array('state','logistics_name','logistics_code');
		$updateVals = array('3',$logistics,$logistics_code);
		$conditionColumns = array('sid','order_code','status');
		$conditionVals = array($sid,$order_code,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //店家拒绝线下付款凭证
    function refuseVoucher($sid,$order_code,$reason){
	    $table = "order";
		$updateColumns = array('state','remarks');
		$updateVals = array('7',$reason);
		$conditionColumns = array('sid','order_code','status','state');
		$conditionVals = array($sid,$order_code,1,6);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //店家处理退款请求
    function refuseRefund($order_code,$pid,$type,$reply){
	    $table = "product_refund";
	    if($type == 1){
		    $updateColumns = array('state');
			$updateVals = array(2);
	    } else {
		    $updateColumns = array('state','reply');
			$updateVals = array(3,$reply);
	    }
		$conditionColumns = array('order_code','pid','status','state');
		$conditionVals = array($order_code,$pid,1,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
}
?>