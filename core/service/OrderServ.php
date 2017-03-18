<?php
class OrderServ{
	
	//根据订单号查找订单
	function getOrderByOrderCode($order_code){
		$arr = array();
		$arr[] = $order_code;
		$sql = "select * from #__order where order_code = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $order=$dbAgent->querySingleRecord($sql,$arr);
	}
	
	//根据订单号搜索订单(过滤身份不符合订单)
	function searchOrderByOrderCode($order_code,$mid,$cid,$subtype,$orderType,$agree,$state){
		$arr = array();
		$sql = "select b.name as applier,a.* from #__order a join #__member b "; 
	    
		$arr[] = $order_code;
		if($orderType == 1){//公司需求订单
		    $sql .= "on a.order_from_mid = b.mid and b.status = 1 where a.order_code = ? ";
		    if($subtype == 1){
			    $arr[] = $mid;
				$sql .= "and a.order_from_mid = ? ";
		    } else{
			    $sql .= "and a.order_from_mid <> 0 ";
		    }
	    } else {
		    $sql .= "on a.payer_mid = b.mid and b.status = 1 where a.order_code = ? ";
		    if($subtype == 0 && $orderType != 4){
				$sql .= "and a.payer_mid <> 0 ";
		    } else {
			    $arr[] = $mid;
			    $sql .= "and a.payer_mid = ? ";
		    }
	    }
	    
	    $orderType = $orderType == 1 ? '1,2' : $orderType;
	    $arr[] = $orderType;
	    $arr[] = $cid;
	    $sql .= "and find_in_set(a.type,?) and a.mid = ? and a.status = 1 ";
	    
		if(!empty($agree)){
			$arr[] = $agree;
			$sql .= "and a.agree = ? ";	
		}
		if(!empty($state)){
			$arr[] = $state;
			$sql .= "and a.state = ? ";	
		}
		
		$dbAgent=DBAgent::getInstance();
		return $order=$dbAgent->querySingleRecord($sql,$arr);
	}
	 
	//查询订单数量	
	function getOrderCount($mid,$cid,$subtype,$orderType,$condition){
		$arr = array();
	    $sql = "select count(*) as count from #__order a join #__member b ";
	    if($orderType == 1){//公司需求订单
		    $sql .= "on a.order_from_mid = b.mid and b.status = 1 ";
		    if($subtype == 1){
			    $arr[] = $mid;
				$sql .= "where a.order_from_mid = ? ";
		    } else{
			    $sql .= "where a.order_from_mid <> 0 ";
		    }
	    } else {
		    $sql .= "on a.payer_mid = b.mid and b.status = 1 ";
		    if($subtype == 0 && $orderType != 4){
				$sql .= "where a.payer_mid <> 0 ";
		    } else{
			    $arr[] = $mid;
			    $sql .= "where a.payer_mid = ? ";
		    }
	    }
	    
	    $orderType = $orderType == 1 ? '1,2' : $orderType;
 	    $arr[] = $orderType;
	    $arr[] = $cid;
	    $sql .= "and find_in_set(a.type,?) and a.mid = ? and a.status = 1 ";
	    
	    if($condition == ""){
		    $dbAgent=DBAgent::getInstance();
		    $result = $dbAgent->querySingleRecord($sql,$arr);
			if($result === false){
				return false;
			}
			return $result['count'];
	    }
	    return $this->searchByCondition($condition,$arr,$sql,1,0,0);   
	}
	
	//分页查询所有订单
	function getAllOrder($mid,$cid,$subtype,$orderType,$condition,$index,$size){
		$arr = array();
	    $sql = "select b.name as applier,a.* from #__order a join #__member b "; 
	    if($orderType == 1){//公司需求订单
		    $sql .= "on a.order_from_mid = b.mid and b.status = 1 ";
		    if($subtype == 1){
			    $arr[] = $mid;
				$sql .= "where a.order_from_mid = ? ";
		    } else{
			    $sql .= "where a.order_from_mid <> 0 ";
		    }
	    } else {
		    $sql .= "on a.payer_mid = b.mid  and b.status = 1 ";
		    if($subtype == 0 && $orderType != 4){
				$sql .= "where a.payer_mid <> 0 ";
		    } else{
			    $arr[] = $mid;
			    $sql .= "where a.payer_mid = ? ";
		    }
	    }
	    
	    $orderType = $orderType == 1 ? '1,2' : $orderType;
	    $arr[] = $orderType;
	    $arr[] = $cid;
	    $sql .= "and find_in_set(a.type,?) and a.mid = ? and a.status = 1 ";
	    
	    if($condition == ""){
		    $dbAgent=DBAgent::getInstance();
		    $sql.="order by a.time desc limit $index,$size ";
	        return $dbAgent->queryRecords($sql,$arr);
	    }
	    return $this->searchByCondition($condition,$arr,$sql,2,$index,$size);
	}
	
	//查询该订单下的所有商品
    function getProductArrayByOrder($order_code){
	    $arr = array();
	    $arr[] = $order_code;
	    $sql = "select b.status as pro_status,a.* from #__order_product a left join #__product b on a.pid = b.pid and b.status <> 0 where a.order_code = ? and a.status = 1 ";
	    $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }
	
	//根据商品名称搜索订单
    function searchOrderByProduct($product,$mid,$cid,$subtype,$orderType,$condition){
	   	$arr = array();
	   	$sql = "select distinct a.order_code,b.name as applier,a.* from #__order a join #__order_product c on a.order_code = c.order_code and c.status = 1 join #__member b ";
	   	if($orderType == 1){//公司需求订单
		    $sql .= "on a.order_from_mid = b.mid and b.status = 1 ";
		    if($subtype == 1){
			    $arr[] = $mid;
				$sql .= "where a.order_from_mid = ? ";
		    } else{
			    $sql .= "where a.order_from_mid <> 0 ";
		    }
	    } else {
		    $sql .= "on a.payer_mid = b.mid and b.status = 1 ";
		    if($subtype == 0 && $orderType != 4){
				$sql .= "where a.payer_mid <> 0 ";
		    } else{
			    $arr[] = $mid;
			    $sql .= "where a.payer_mid = ? ";
		    }
	    }
	    
	    $orderType = $orderType == 1 ? '1,2' : $orderType;
	    $arr[] = $orderType;
	    $arr[] = $cid;
	    $sql .= "and find_in_set(a.type,?) and a.mid = ? and a.status = 1 ";
	    
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
   	
   	    //搜索总方法
    function searchByCondition($condition,$arr,$sql,$type,$index,$size){
		if(isset($condition['applier'])){
		    $arr[] ='%'.$condition['applier'].'%';
		    $arr[] ='%'.$condition['applier'].'%';
			$sql.="and (b.nickname like ? or b.name like ? )";
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
			$sql.= "and a.state = ? ";
		}
		
		$dbAgent=DBAgent::getInstance();
		if($type == 1){ //查数量 
			$result = $dbAgent->querySingleRecord($sql,$arr);
			if($result === false){
				return false;
			}
			return $result['count'];
		} else if($type == 2){ //查订单
			$sql.="order by a.time desc limit $index,$size ";
	        return $dbAgent->queryRecords($sql,$arr);
		} else { //搜商品
			$sql.= "order by a.time desc ";
			return $dbAgent->queryRecords($sql,$arr);
		}	
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
    
    //获取订单产品
    function getOrderProductByPid($pid,$order_code){
	    $arr = array();
	    $arr[] = $pid;
	    $arr[] = $order_code;
	    $sql = "select * from #__order_product where pid = ? and order_code = ? and status = 1 ";
	    $dbAgent=DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
    }
    
    //退款申请
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
	   $sql = "select 	case when c.id is null then '未知' else c.name end as province_name,
						case when d.id is null then '' else d.name end as city_name,
						case when e.id is null then '' else e.name end as country_name,
						b.detail_address,b.zip,a.* from #__order a 
						left join #__address b on a.address_id = b.id
						left join #__area c on b.province = c.id 
						left join #__area d on b.city = d.id   
						left join #__area e on b.country = e.id   
						where a.order_code = ? and a.status = 1 ";
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
		$sql="update #__order set state=2,payment_type=1,payment_method=? where order_code=? and state=1 and status=1 ";
		array_push($sqls, $sql);
		array_push($arrs, $arr);
		
		$arr3=array();
		$arr3[]=$code;
		$sql3="update #__product a join #__order_product b on a.pid=b.pid set a.sale_num=a.sale_num+b.num where b.order_code=? and a.status=1 and b.status=1";
		array_push($sqls,$sql3);
		array_push($arrs, $arr3);
			
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
			$sql="update #__order set state=6,voucher=?,payment_type=3 where order_code=? and payer_mid=? and status=1 and state=1  "; 
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
		$updateVals = array('0','12',$mid);
		
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //取消订单后更新商品库存信息
    function updateSkuInfoOnCancelOrder($skuids){
	    $arr = array();
	    $arr[] = $skuids;
	    $sql = "update #__sku set inventory = inventory + 1 where find_in_set(skuid,?) and status = 1 ";	
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
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
    
    //更新订单状态
    function updateOrderState($order_code,$state){
	    $table = "order";
	    $conditionColumns = array('order_code','status');
		$conditionVals = array($order_code,1);
		$updateColumns = array('state');
		$updateVals = array($state);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //更新订单下产品状态
    function updateOrderProductState($order_code,$pid,$state){
	    $table = "order_product";
	    $conditionColumns = array('order_code','pid','status');
		$conditionVals = array($order_code,$pid,1);
		$updateColumns = array('state');
		$updateVals = array($state);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //评论产品
    function reviewProduct($mid,$pid,$sid,$score,$content,$image){
	    $table="product_comment";
		$insertColumns=array("mid","pid","sid","score","content",'images','time','status');
		$insertVals=array($mid,$pid,$sid,$score,$content,$image,time(),'1');
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }
    
    //上传线下付款凭证
    function uploadVoucher($mid,$order_code,$voucher){
	    $table = "order";
	    $conditionColumns = array('order_code','payer_mid','status');
		$conditionVals = array($order_code,$mid,1);
		$updateColumns = array('state','voucher');
		$updateVals = array(6,$voucher);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //订单退款退货
    function refundOrderProduct($order_code,$pid,$reason,$otherReason,$image){
		$table = "product_refund";
		$insertColumns = array("order_code","pid","reason","otherReason","image",'state','status','time');
		$insertVals = array($order_code,$pid,$reason,$otherReason,$image,1,1,time());
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }
    
    //获取退款信息
    function getRefundInfo($order_code,$pid){
	    $arr = array();
	    $arr[] = $order_code;
	    $arr[] = $pid;
	    $sql = "select * from #__product_refund where order_code = ? and pid = ? and status = 1 ";
	    $dbAgent = DBAgent::getInstance();
	    return $result=$dbAgent->querySingleRecord($sql,$arr);
    }
}


?>