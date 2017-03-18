<?php
class CouponServ{
	
	public function __construct(){
	    
    }
    
	function getCouponList($mid,$type,$use_status){
		$arr = array();
		$arr[] = $mid;
		$arr[] = $type;
		$arr[] = $use_status;
		$sql = "select * from #__coupon where mid = ? and type = ? and use_status = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	//获取代金券列表
	function getCashList($key,$sid,$type,$index,$pagesize){
		$a=($index-1)*$pagesize;
		$arr=array();
		$arr[]=time();
		$arr[]=time();
		$arr[]=$sid;
		$arr[]=$type;
		$sql="select * from #__coupon_list where start_time<? and end_time>?  and sid=? and type=? and status=1 ";
		if($key!=""){
			$sql.=" and name like '%$key%' ";
		}
		$sql.=" limit $a,$pagesize ";
		$dbAgent=DBAgent::getinstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	//获取代金券列表数量
	function getCashListCount($key){
		$arr=array();
		$arr[]=time();
		$arr[]=time();
		$sql="select count(*) as num from #__coupon_list where start_time<? and end_time>? and status=1 ";
		if($key!=""){
			$sql.=" and name like '%$key%' ";
		}
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		return $result['num'];
	}	
	//生成卷
	function insertCoupon($num,$starttime,$endtime,$money,$type,$name,$intro,$sid,$limit){
		$table = "coupon_list";
		$insertColumns = array('num','start_time','end_time','money','type','name','intro','sid','min_limit','status');
		$insertVals = array($num,$starttime,$endtime,$money,$type,$name,$intro,$sid,$limit,1);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	//领取卷
	function getCoupon($mid,$mobile,$couponid,$type,$sid){
		$arra=array();
		$arra[]=$couponid;
		$arra[]=$type;
		$arra[]=$sid;
		$sqla="select * from #__coupon_list where id=? and type=?  and sid=? and status=1";
		$dbAgent = DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sqla,$arra);
		if($result===false){
			return false;
		}else if($result===null){
			return false;
		}
		$couponinfo=$result;
		
		$arrz=array();
		$arrz[]=$mid;
		$arrz[]=$couponid;
		$arrz[]=$type;
		$arrz[]=$sid;
		$sqlz="select count(*) as num from #__coupon where mid=? and coupon_id=? and type=? and sid=? and status=1";
		$resz=$dbAgent->querySingleRecord($sqlz,$arrz); 
		if($resz===false){
			return false;
		}else if($resz['num']>=1){
			return -1;
		}
		
		$arrs=array();
		$sqls=array();
		$time=time();
		if($time<$couponinfo['start_time']||$time>$couponinfo['end_time']){
			return false;
		}
		if($couponinfo['num']<=0){
			return false;
		}
		$arr=array();
		$arr[]=$couponid;
		$sql="update #__coupon_list set num=num-1 where id=? and status=1 ";
		array_push($arrs, $arr);
		array_push($sqls, $sql);
		
		$arr1=array();
		$arr1[]=$mid;
		$arr1[]=$type;
		$arr1[]=time();
		$arr1[]=$couponinfo['money'];
		$arr1[]=$couponinfo['name'];
		$arr1[]=$couponinfo['intro'];
		$arr1[]=$couponinfo['start_time'];
		$arr1[]=$couponinfo['end_time'];
		$arr1[]=$couponinfo['min_limit'];
		$arr1[]=$couponid;
		$arr1[]=$sid;
		$arr1[]=$mobile;
		$sql1="insert #__coupon (mid,type,number,use_status,money,name,intro,start_time,end_time,min_limit,coupon_id,sid,mobile) values(?,?,?,1,?,?,?,?,?,?,?,?,?) ";
		array_push($arrs, $arr1);
		array_push($sqls, $sql1);
		$result1 = $dbAgent->QueryWithTransaction($sqls,$arrs);
		 
	    return $result1;
	}
	
	//更新优惠券状态
	function updayestatus($mid,$id,$status){
		$table="coupon";
		$updateColums=array('status');
		$updateVals=array($status);
		$conditionColums=array('mid','id');
		$conditionVals=array($mid,$id);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColums,$updateVals,$conditionColums,$conditionVals);
	}
	//更新优惠券使用状态
	function updateUseStatus($mid,$id,$ustatus){
		$table="coupon";
		$updateColums=array('use_status');
		$updateVals=array($ustatus);
		$conditionColums=array('mid','id');
		$conditionVals=array($mid,$id);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColums,$updateVals,$conditionColums,$conditionVals);
	}
	//获取卷的详情
	function getCouponDetail($type,$id,$mid){
		$time=time();
		$arr=array();
		$arr[]=$type;
		$arr[]=$id;
		$arr[]=$mid;
		$sql="select * from #__coupon where type=? and id=? and mid=? and use_status=1 and start_time<".$time." and end_time>".$time." and status=1";
		$dbAgent=DBAgent::getInstance();
		//return $sql;
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	
	//获取待使用卷的详情
	function getCouponDetailS($id,$mid){
		$time=time();
		$arr=array();
		$arr[]=$id;
		$arr[]=$mid;
		$sql="select * from #__coupon where id=? and mid=? and use_status=4 and start_time<".$time." and end_time>".$time." and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}

	
	
	//根据sid获取优惠券列表
	function getCouponBysid($sid=null,$num,$page)
    {
        $a=($page-1)*$num;
        $sql = "select * from #__coupon_list where 1=1";
        if ($sid) {
            $sql .= " and sid=$sid";
        }
        $sql.=" limit $a,$num";
        $dbAgent=DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,array());
//        return $sql;
    }

    //根据Sid获取优惠券数量
    function getCouponCount($sid=null){
        $sql="select count(*) as num from #__coupon_list where 1=1";
        if($sid){
            $sql.=" and sid=$sid";
        }
        $dbAgent=DBAgent::getInstance();
        $result=$dbAgent->querySingleRecord($sql,array());
        return $result['num'];
    }
}
?>