<?php
class MobileOrderServ{
	
	public function __construct(){
	    
    }
	
	//获取订单数量
	function getOrderCount($mid,$cid,$orderType,$subType,$agree,$state){
		$arr = array();
		$arr[] = $cid;
		$sql = "select count(*) as count from #__order where mid = ? and status = 1 ";
	
		if($orderType == 1){//公司需求订单
			if($subType == 1){//科研人员
				$arr[] = $mid;
				$sql .= "and order_from_mid = ? and find_in_set(type,'1,2') ";
			} else {
				$sql .= "and order_from_mid != 0 and find_in_set(type,'1,2') ";
			}
	    } else {
		    $arr[] = $orderType;
		    $arr[] = $mid;
		    $sql .= "and type = ? and payer_mid = ? ";
	    }
	    if($agree != '0'){
			$arr[] = $agree;
			$sql .= "and agree = ? ";
		}
		if($state != '0'){
			$arr[] = $state;
			$sql .= "and state = ? ";
		}
	    
	    $dbAgent=DBAgent::getInstance();
	    $result = $dbAgent->querySingleRecord($sql,$arr);
		if($result === false){
			return false;
		}
		return $result['count'];
	}
	
	//分页获取订单
	function getOrder($mid,$cid,$orderType,$subType,$agree,$state,$index,$size){
		$arr = array();
		$arr[] = $cid;
		$sql = "select * from #__order where mid = ? and status = 1 ";
	
		if($orderType == 1){//公司需求订单
			if($subType == 1){//科研人员
				$arr[] = $mid;
				$sql .= "and order_from_mid = ? and find_in_set(type,'1,2') ";
			} else {
				$sql .= "and order_from_mid <> 0 and find_in_set(type,'1,2') ";
			}
	    } else {
		    $arr[] = $orderType;
		    $arr[] = $mid;
		    $sql .= "and type = ? and payer_mid = ? ";
	    }
	    if($agree != '0'){
			$arr[] = $agree;
			$sql .= "and agree = ? ";
		}
		if($state != '0'){
			$arr[] = $state;
			$sql .= "and state = ? ";
		}
	    
	    $sql .= "order by time desc limit $index,$size ";
	    $dbAgent=DBAgent::getInstance();
	    return $dbAgent->queryRecords($sql,$arr);
	}
	
	//获取店铺订单数量
	function getShopOrderCount($sid,$state){
		$arr = array();
		$arr[] = $sid;
		$sql = "select count(*) as count from #__order where sid = ? and status = 1 ";
   		if($state != '0'){
			$arr[] = $state;
			$sql .= "and state = ? ";
		}
		$dbAgent=DBAgent::getInstance();
	    $result = $dbAgent->querySingleRecord($sql,$arr);
		if($result === false){
			return false;
		}
		return $result['count'];
	}
	
	//获取店铺订单
	function getShopOrder($sid,$state,$index,$size){
		$arr = array();
		$arr[] = $sid;
		$sql = "select * from #__order where sid = ? and status = 1 ";
   		if($state != 0){
			$arr[] = $state;
			$sql .= "and state = ? ";
		}
		$sql .= "order by time desc limit $index,$size ";
		$dbAgent=DBAgent::getInstance();
	    return $result = $dbAgent->queryRecords($sql,$arr);
	}
		
	//查询该订单下的所有商品
    function getProductArrayByOrder($order_code){
	    $arr = array();
	    $arr[] = $order_code;
	    $sql = "select * from #__order_product where order_code = ? and status = 1 ";
	    $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }
		
	//处理公司需求订单
    function handleOrder($mid,$order_code,$type){//type 1:通过 2:拒绝
	    $table = "order";
	    $conditionColumns = array('order_code','agree');
		$conditionVals = array($order_code,1);
		
	    if($type == 1){
		    $updateColumns = array('type','agree','state','payer_mid','time');
			$updateVals = array('2','2','1',$mid,time());
	    } else {
		    $updateColumns = array('agree','refuse_mid');
			$updateVals = array('3',$mid);
	    }
		
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //提款申请
    function refundOrder($mid,$order_code){
	    $tabl="order";
		$conditionColumns = array('order_code','state','payer_mid');
		$conditionVals = array($order_code,2,);
		$updateColumns = array('state');
		$updateVals = array('11');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
   //查看订单详情
   function getOrderDetailByCode($order_code){
	   $arr = array();
	   $arr[] = $order_code;
	   $sql = "select * from #__order where order_code = ? and status = 1 ";
	   $dbAgent=DBAgent::getInstance();
	   return $dbAgent->querySingleRecord($sql,$arr);
   }
   
   //提醒卖家
   function remindMerchan($code,$mid,$sid){
	   $arr=array();
	   $arr[]=$code;
	   $arr[]=$mid;
	   $time=time();
	   $sql="select * from #__order_remind where order_code=? and mid=? and status=1 ";
	   $dbAgent=DBAgent::getInstance();
	   $remind=$dbAgent->querySingleRecord($sql,$arr);
	   $arr[]=$sid;
	   if($remind===false){
		   return false;
	   }else if($remind===null){
		   $sql2="insert into #__order_remind(order_code,mid,time,remind_num,status,sid) values(?,?,'$time',1,1,?) ";
	   }else {
		   $sql2="update #__order_remind set remind_num=remind_num+1,time='$time' where order_code=? and mid=? and status=1 and sid=? ";
	   }
	   return $dbAgent->query($sql2,$arr);
   }
   //充值
	function recharge($code,$type,$mid,$point,$id){
		$sqls=array();
		$arrs=array();
		
		$arr1=array();
		$arr1[]=$point;
		$arr1[]=$mid;
		$sql1="update #__member set accumulated_points=accumulated_points+? where status=1 and mid=? ";
		array_push($sqls, $sql1);
		array_push($arrs, $arr1);
		
		if($id>0){
			$arr2=array();
			$arr2[]=$mid;
			$arr2[]=$id;
			$sql2="update #__coupon set use_status=2 where mid=? and id=? and status=1 ";
			array_push($sqls, $sql2);
			array_push($arrs, $arr2);
		}
		
		$arr=array();
		$arr[]=$type;
		$arr[]=$code;
		$sql="update #__order set state=2,payment_method=? where order_code=? and state=1 and status=1 ";
		array_push($sqls, $sql);
		array_push($arrs, $arr);
		
	
		$dbAgent=DBAgent::getInstance();
	    $result = $dbAgent->QueryWithTransaction($sqls,$arrs);
	    return $result;
	}
	
	
	function setOrderOffline($codes,$mid,$url){
		$codelist=explode(',', $codes);
		$sqls=array();
		$arrs=array();
		foreach($codelist as $code){
			$arr=array();
			$arr[]=$url;
			$arr[]=$code;
			$arr[]=$mid;
			$sql="update #__order set state=9,voucher=? where order_code=? and payer_mid=? and status=1 and state=1  "; 
			array_push($sqls, $sql);
			array_push($arrs, $arr);
		}
		$dbAgent=DBAgent::getInstance();
	    $result = $dbAgent->QueryWithTransaction($sqls,$arrs);
	    return $result;
	}
	
	//会员充值  保存订单
	function saveVipOrder($mid,$time,$code,$price,$viptype,$paymethod){
		$table="order_vip";
		$insertColumns=array("payer_mid","time","order_code","pay_price","viptype",'paymethod','status','state');
		$insertVals=array($mid,$time,$code,$price,$viptype,$paymethod,1,1);
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	//会员充值
	function Viprecharge($code,$type,$mid,$point,$rtype,$isvip){
		$sqls=array();
		$arrs=array();
		
		$arr1=array();
		$arr1[]=$point;
		$arr1[]=$mid;
		$sql1="update #__member set accumulated_points=accumulated_points+? where status=1 and mid=? ";
		array_push($sqls, $sql1);
		array_push($arrs, $arr1);
		
		//rtype 1 月  2季度   3年
		//isvip 1是会员 0不是
		$time=0;
		if($rtype==1){
			$time=30*24*60*60;
		}else if($rtype==2){
			$time=90*24*60*60;
		}else if($rtype==3){
			$time=365*24*60*60;
		}
		$arr2=array();
		$sql2="";
		if($isvip==1){
			$arr2[]=$time;
			$arr2[]=$mid;
			$sql2="update #__member set end_time=end_time+? where mid=? and is_vip=1 and status=1 ";
		}else if($isvip==0){
			$arr2[]=time();
			$arr2[]=time()+$time;
			$arr2[]=$mid;
			$sql2="update #__member set start_time=?,end_time=?,is_vip=1 where mid=?  and is_vip=0 and status=1";
		}
		array_push($sqls, $sql2);
		array_push($arrs, $arr2);

		$arr=array();
		$arr[]=$type;
		$arr[]=$code;
		$sql="update #__order_vip set state=2,paymethod=? where order_code=? and state=1 and status=1 ";
		array_push($sqls, $sql);
		array_push($arrs, $arr);
		
		$dbAgent=DBAgent::getInstance();
	    $result = $dbAgent->QueryWithTransaction($sqls,$arrs);
	    return $result;
	}

	//查看会员充值订单详情
	function getVipOrderDetail($ordercode){
		$arr=array();
		$arr[]=$ordercode;
		$sql="select * from #__order_vip where order_code=? and status=1 ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
	//查看订单详情1
   function getOrderDetail($order_code,$mid){
	   $arr = array();
	   $arr[] = $order_code;
	   $arr[]=$mid;
	   $sql = "select * from #__order where order_code = ? and payer_mid=? and status = 1 ";
	   $dbAgent=DBAgent::getInstance();
	   return $dbAgent->querySingleRecord($sql,$arr);
   }

   //查看订单产品详情
   function getOrderShopDetail($order_code){
	   $arr = array();
	   $arr[] = $order_code;
	   $sql = "select * from #__order_product where order_code = ? and status = 1 ";
	   $dbAgent=DBAgent::getInstance();
	   return $dbAgent->queryRecords($sql,$arr);
   }
    
    //取消订单
    function cancelOrder($mid,$order_code){
	    $table = "order";
	    $conditionColumns = array('order_code','status');
		$conditionVals = array($order_code,1);
		
		$updateColumns = array('status','state','cancel_mid');
		$updateVals = array('0','8',$mid);
		
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //更新科研基金订单的状态
    function updatefoundorder($ordercode){
	    $table = "order";
	    $conditionColumns = array('order_code','state');
		$conditionVals = array($ordercode,1);
		$updateColumns = array('state');
		$updateVals = array('2');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //更新会员的科研基金
    function updatememberfound($mid,$allmoney){
	    $arr=array();
	    $arr[]=$allmoney;
	    $arr[]=$mid;
	    $sql="update #__college_member_fund set used_money=used_money+? where mid=? and  status=1";
	    $dbAgent = DBAgent::getInstance();
	    return $result=$dbAgent->query($sql,$arr);
    }
    
	//删除订单
    function deleteOrder($order_code){
	    $table = "order";
	    $conditionColumns = array('order_code','status');
		$conditionVals = array($order_code,1);
		$updateColumns = array('status');
		$updateVals = array('0');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //确认收货
    function confirmReceiveGoods($mid,$order_code){
	    $table = "order";
	    $conditionColumns = array('order_code','status');
		$conditionVals = array($order_code,1);
		$updateColumns = array('state');
		$updateVals = array('4');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
}


?>