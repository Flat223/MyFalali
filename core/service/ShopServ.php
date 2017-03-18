<?php
class ShopServ{
	//构造函数->默认载入函数
	public function __construct(){
		
	}
	
	public function getShopDetail($sid){
		$arr=array();
		$arr[]=$sid;
		$sql="select * from #__shop where sid=? and status=1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}

    public function getShops(){
        $arr = array();
        $arr[] = 1;
        $sql="select * from #__shop where status = ? ";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页获取实验室仪器*/
    public function getPageShops($index,$pagesize){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__shop where status = ? ";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    public function getShopsByInfo($info){
        $arr = array();
        $arr[] = 1;
        $sql="select * from #__shop where shop_name like \"%$info%\" and status = ? ";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*分页获取实验室仪器*/
    public function getPageShopsByInfo($info,$index,$pagesize){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__shop where shop_name like \"%$info%\" and status = ? ";
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*获取对应店铺店主*/
    public function getShopBossById($mid){
        $arr = array();
        $arr[] = $mid;
        $sql = "select m.name from #__member m where mid = ? and status=1 ";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->querySingleRecord($sql,$arr);
    }

    /*获取对应店铺分类*/
    public function getShopTypeById($sid){
        $arr = array();
        $arr[] = $sid;
        $sql = "select st.name from #__shop_type st where stid = ? and status=1 ";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->querySingleRecord($sql,$arr);
    }

    /*获取所有u店铺分类*/
    public function getShopType(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__shop_type  where status = ? ";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,$arr);
    }

    /*获取公司*/
    public function getOrg($name){
        $arr = array();
        $arr[] = $name;
        $sql = "select * from #__company_regist  where name = ? AND status = 1";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->querySingleRecord($sql,$arr);
    }

    /*获取公司*/
    public function getOrgByName($name){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__institude  where name like \"%$name%\" AND status = ?";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,$arr);
    }

    /*获取用户*/
    public function getShopMember($name){
        $arr = array();
        $arr[] = 3;
        $sql = "select * from #__member  where (name like \"%$name%\" or mobile like \"%$name%\") and type = ? AND status = 1";
        $sql .= " limit 0,20";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,$arr);
    }

    /*根据用户id获取他的店铺*/
    public function getShopById($mid){
        $arr = array();
        $arr[] = $mid;
        $sql = "select * from #__shop where mid = ? and status=1 ";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->querySingleRecord($sql,$arr);
    }

    /*根据用户id获取他的店铺*/
    public function getShopBySId($sid){
        $arr = array();
        $arr[] = $sid;
        $sql = "select * from #__shop where sid = ? and status=1 ";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->querySingleRecord($sql,$arr);
    }

    /*根据用户id获取他的店铺*/
    public function getMemberByName($name){
        $arr = array();
        $arr[] = $name;
        $sql = "select * from #__member where name = ? and status=1 ";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->querySingleRecord($sql,$arr);
    }

    /*添加店铺*/
    public function insertShop($data){
        $table = "shop";
        $insertColumns = array('shop_name','mid','address','phone','time','shop_state','grade_id','status');
        $insertVals = array($data['name'],$data['mid'],$data['address'],$data['phone'],time(),1,1,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    /*根据id删除店铺*/
    public function deleteShopById($id){
        $table = "shop";
        $updateColumns = array('status');
        $updateVals = array(0);
        $conditionColumns = array('sid','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    //根据用户的MID获取用户店铺的商品
    public function userProductBymid($sid,$sta,$name,$index,$size){
        $sql="select distinct a.pid,case when d.skuid is null then 0 else 1 end as is_complete,b.name as bname,a.*,c.name as tname from #__product a left join labring_brand b on a.brand_id=b.brand_id left join labring_product_type c on c.ptid=a.ptid left join #__sku d on a.pid = d.pid and d.status = 1 where a.sid=$sid and a.status <> 0 ";
        if($sta){
            $sql.="and a.status=$sta ";
        }
        if($name){
	        if(Common::isInteger($name)){
	            $sql .= "and a.pid = $name ";
	        } else {
		        $sql .= "and a.name like '%$name%' ";
	        }
        }
        
        $sql.="order by a.pid desc limit $index,$size ";
        $dbAgent = DBAgent::getInstance();
        $result=$dbAgent->queryRecords($sql,array());
        
//         print_r($result);
        foreach($result as &$pro){
            $sql2="select avg(score) as score,count(*) as num from #__product_comment where pid=".$pro['pid'];
            $ret=$dbAgent->querySingleRecord($sql2,array());
            $pro['score']=$ret['score'];
            $pro['see']=$ret['num'];
        }
        return $result;
    }
    //根据MID获取用户商品数目
    public function userProductCount($sid,$sta,$name){
	    $arr = array();
	    $arr[] = $sid;
        $sql="select count(*) as count from labring_product where sid = ? and status <> 0 ";
        if($sta){
	        $arr[] = $sta;
            $sql.=" and status = ? ";
        }
        if($name){
	        if(Common::isInteger($name)){
		        $arr[] = $name;
	            $sql .= "and pid = ? ";
	        } else {
		        $arr[] = "%".$name."%";
		        $sql .= "and name like ? ";
	        }
        }
        $dbAgent = DBAgent::getInstance();
        $result=$dbAgent->querySingleRecord($sql,$arr);
        
        if($result === false){
	        return false;
        }
        return $result['count'];
    }
    
    //根据mid获取店铺
   function getShopByMid($mid){
	   $arr = array();
	   $arr[] = $mid;
	   $sql = "select * from #__shop where mid = ? and status = 1 ";
	   $dbAgent=DBAgent::getInstance();
	   return $result=$dbAgent->querySingleRecord($sql,$arr);
   }

    public function updateshop($sid,$address,$city_id,$district,$province_id,$zip,$shop_name,$logo,$mobile){
        $dbAgent = DBAgent::getInstance();
        $table="shop";
        $conditionColumns=array("sid");
        $conditionVals=array($sid);
        $updateVals=array($address,$city_id,$district,$province_id,$zip,$shop_name,$logo,$mobile);
        $updateColumns=array('address','city_id','district','province_id','zip','shop_name','logo','phone');
        if($dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals,$hasPrefix=true)){
            $result['ret']=1;
            $result['msg']="成功";
        }else{
            $result['ret']=0;
            $result['msg']="失败";
        }
        return $result;
    }
    
    //店主删除商品
    public function deleteProduct($pids,$sid){
	    $arr = array();
		$arr[] = $sid;
	    $arr[] = $pids;
	    $sql = "update #__product set status = 0 where sid = ? and find_in_set(pid,?) and status != 0 ";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->query($sql,$arr);
    }
	
	//产品上架/下架
    function updateProStatus($sid,$pids,$status){
		$arr = array();
		$arr[] = ($status == 1) ? 2 : 1;
		$arr[] = $sid;
		$arr[] = $pids;
		$arr[] = $status;
		$sql = "update #__product set status = ? where sid = ? and find_in_set(pid,?) and status = ? ";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
    }
    
    //获取快递公司信息
    function getExpress(){
	    $sql = "select * from #__express where status = 1 ";
	    $dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
    }
    
    //获取店铺消息数量
    function getShopMessageCount($sid,$type){
	    $arr = array();
	    $arr[] = $sid;
	    if($type == 1){//提醒发货消息
		    $sql = "select count(*) as count from #__order_remind where sid = ? and status = 1 ";
	    } else if($type == 2){//新订单消息
		    $sql = "select count(*) as count from #__order where sid = ? and type <> 1 and status = 1 ";
	    }
	    $dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		
		if($result === false){
			return false;
		}
		return $result['count'];
    }
    
    //分页获取店铺消息
    function getShopMessage($sid,$type,$index,$size){
	    $arr = array();
	    $arr[] = $sid;
	    if($type == 1){//提醒发货消息
		    $sql = "select b.consignee,a.* from #__order_remind a join #__order b on a.order_code = b.order_code where a.sid = ? and a.status = 1 and b.status = 1 ";
	    } else if($type == 2){//新订单消息
		    $sql = "select b.nickname as consignee,a.* from #__order a join #__member b on a.payer_mid = b.mid where a.sid = ? and a.type <> 1 and a.shop_message = 0 and a.status = 1 and b.status = 1 ";
	    }
	    $sql .= "order by a.time desc limit $index,$size ";
	    $dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
    }
    
    //获取该店铺下所有产品数量
    function getAllShopProCount($sid){
		$arr = array();
		$arr[] = $sid;
		$sql = "select count(*) as count from #__product where sid = ? and status <> 0 ";
		$dbAgent = DBAgent::getInstance();
		$result = $dbAgent->querySingleRecord($sql,$arr);
		if($result === false){
			return false;
		}
		return $result['count'];
    }
    
    //删除提醒发货消息
    function deleteRemindMesage($ids,$sid){
	    $arr = array();
	    $arr[] = $ids;
	    $arr[] = $sid;
	    $sql = "update #__order_remind set status = 0 where find_in_set(id,?) and sid = ? and status = 1 ";
	    $dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->query($sql,$arr);
    }
    
    //删除新的订单消息
    function deleteNewOrderMesage($order_codes,$sid){
	    $arr = array();
	    $arr[] = $order_codes;
	    $arr[] = $sid;
	    $sql = "update #__order set shop_message = 1 where find_in_set(order_code,?) and sid = ? and shop_message = 0 and type <> 1 and status = 1 ";
	    $dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->query($sql,$arr);
    }
}	
?>