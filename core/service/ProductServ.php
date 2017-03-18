<?php
class ProductServ{
	
	private $newArr;
	private $tempArr;
	
	//构造函数->默认载入函数
    public function __construct(){
	    
    }
	//获取产品2级类型
	function getParentType(){
		$arr=array();
		$arr[]=2;
		$arr[]=1;
		$sql="select * from #__product_type where level= ? and status= ? ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);	
	}
	//获取产品类型
	function getChildType($id){
		$arr=array();
		$arr[]=$id;
		$sql="select * from #__product_type where parentid= ? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);	 
	}
	//根据level3的ptid获取level的信息
	function getLevle1ByL3($ptid){
		$arr=array();
		$arr[]=$ptid;
		$sql="select parentid as parentid from #__product_type where ptid=? and status=1 and level=3 ";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		
		$arr1=array();
		$arr1[]=$result['parentid'];
		$sql1="select parentid as parentid from #__product_type where ptid=? and status=1 and level=2 ";
		$result1=$dbAgent->querySingleRecord($sql1,$arr1);
		return $result1['parentid'];
	}
	//更新浏览量
	function updateSee($pid){
		$arr=array();
		$arr[]=$pid;
		$sql="update #__product set view_num=view_num+1 where MD5(pid) = ? status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->query($sql,$arr);
	}
	//获取组合产品详情
	function getProGroupDetail($id){
		$arr=array();
		$arr[]=$id;
		$sql="select * from #__product_group where id=? and status=1 ";
		$dbAgent=DBAgent::getInstance();
		return  $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	
	function getLevel3ByL2($ptid){
		$arr=array();
		$arr[]=$ptid;
		$sql="select ptid from #__product_type where status=1 and level=3 and parentid=? ";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->queryRecords($sql,$arr);
		$temp=array();
		foreach($result as $value2){
			$temp[]=$value2['ptid'];	
		}
		$last=implode(',', $temp);
		return $last;
	}
	
	function getLevel3ByL1($ptid){
		$arr1=array();
		$arr1[]=$ptid;
		$sql="select ptid from #__product_type where parentid=? and status=1 and level=2 ";
		$dbAgent=DBAgent::getInstance();
		$result1=$dbAgent->queryRecords($sql,$arr1);
		$ptids=array();
		foreach($result1 as $value){
			$ptids[]=$value['ptid'];
		}
		$three=array();
		foreach($ptids as $value1){
			$arr=array();
			$arr[]=$value1;
			$sql="select ptid from #__product_type where status=1 and level=3 and parentid=? ";
			$result=$dbAgent->queryRecords($sql,$arr);
			$temp=array();
			foreach($result as $value2){
				$temp[]=$value2['ptid'];	
			}
			$temp1=implode(',',$temp);
			$three[]=$temp1;
		}		
		$last=implode(',', $three);
		return $last;
	}
	//获取产品1级类型
	function getProductType(){
		$arr=array();
		$arr[]=1;
		$arr[]=1;
		$sql="select * from #__product_type where level= ? and status= ? ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);	
	}
	function getProtype(){
		$arr=array();
		$arr[]=1;
		$sql="select * from #__product_type where status=?";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);	
	}
	//根据ptid获取类型
	function getSingleType($id){
		$arr=array();
		$arr[]=$id;
		$sql="select * from #__product_type where ptid= ? and status=1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);	
	}
	//获取产品在sku表中的数量
	function getProductSku($pid){
		$arr=array();
		$arr[]=$pid;
		$sql="select count(*) as num from #__sku where MD5(pid)=? and status=1";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		return $result['num'];
	}
	//获取推荐的产品
	function getRecommendProduct(){
		$arr=array();
		$arr[]=1;
		$sql="select a.*,b.*,c.name as typename,c.parentid from #__product a join #__product_recommend b on a.pid=b.pid join #__product_type c on a.ptid=c.ptid ";
		$sql.="where a.status=1 and b.status=1 and c.status= ? ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	//获取产品详情
	function getProductDetail($pid){
		$arr=array();
		$arr[]=$pid;
		$sql="select a.*,b.name as brandname,b.image as brandimage,c.sale_num,c.inventory,d.shop_name,d.logo from #__product a left join #__brand b on a.brand_id=b.brand_id left join #__sku c on a.pid=c.pid left join #__shop d on a.sid=d.sid where MD5(a.pid)=? and a.status=1 and b.status=1 and c.status=1 and d.status=1";
		$dbAgent=DBAgent::getInstance();

		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}

	//获取购买产品详情
	function getBuyProductDetail($skuid){
		$arr=array();
		$arr[]=$skuid;
		$sql="select a.name as pname,a.images,a.intro,a.v1_discount,a.v2_discount,a.v3_discount,a.v4_discount,a.can_testing,a.quality_testing,a.can_guarantee,a.guarantee_1,a.guarantee_2,a.guarantee_5,b.*,c.sid,c.shop_name from #__product a join #__sku b on a.pid=b.pid join #__shop c on a.sid=c.sid where 
		b.skuid=? and a.status=1 and b.status=1 and c.status=1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	//获取好评度
	function getGoodCommentPercent($pid){
		$arr=array();
		$arr[]=$pid;
		$sql="select count(*) as num from #__product_comment where md5(pid)= ? and status=1";
		$sql1="select count(*) as good from #__product_comment where md5(pid)=? and score>=4 and status=1";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		$result1=$dbAgent->querySingleRecord($sql1,$arr);
		if($result['num']==0||$result1['good']==0){
			return 0;
		}
		return $percent=($result1['good']/$result['num'])*100;
		
	}
	// 
	function getPropertyDetail($skuid){
		$arr=array();
		$arr[]=$skuid;
		$sql="select b.name as proval,c.name as proname from #__sku a join #__product_property_val b on FIND_IN_SET(b.id,a.properties) join #__product_property c on  ";
		$sql.="b.propertyid=c.id where a.skuid=? and a.status=1 and b.status=1 and c.status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}	
	//获取对比推荐产品
	function getRecommentProducts($ptid){
		$arr=array();
		$arr[]=$ptid;
		$sql="select * from #__product where ptid= ? and status=1 limit 6";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);	
	}
	//获取产品简单详情
	function getSingleProduct($pid){
		$arr=array();
		$arr[]=$pid;
		$sql="select * from #__product a left join #__shop b on a.sid = b.sid where a.pid=? and a.status!=0 and b.status!=0";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	
	//获取产品简单详情
	function getSingleProductMd5($pid){
		$arr=array();
		$arr[]=$pid;
		$sql="select * from #__product a left join #__shop b on a.sid = b.sid where md5(a.pid)=? and a.status!=0 and b.status!=0";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	
	//加入购物车
	function AddCart($mid,$pid,$sid,$num,$skuid,$testing=0,$guarantee=0){
		
		$arr=array();
		$arr[]=$pid;
		$arr[]=$skuid;
		$arr[]=$mid;
		$arr[]=$testing;
		$arr[]=$guarantee;
		$sql="select * from #__cart where pid=? and skuid=?  and mid=? and testing=? and guarantee= ? and status=1";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		//return $result;
		if($result===false){
			return false;
		}else if($result===null){
			$table="cart";
			$time=time();
			$insertColumns=array("mid","pid","sid","num","skuid",'time','testing','guarantee');
			$insertVals=array($mid,$pid,$sid,$num,$skuid,$time,$testing,$guarantee);
			return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
		}else{
			$arr1=array();
			$arr1[]=$num;
			$arr1[]=$pid;
			$arr1[]=$skuid;
			$arr1[]=$mid;
			$arr1[]=$testing;
			$arr1[]=$guarantee;
			$sql1="update #__cart set num=num+? where pid=? and skuid=? and mid=? and testing= ? and guarantee= ? and status=1";
			return $dbAgent->query($sql1,$arr1);
		}
	}
	function updateCart($ids,$mid){
		$sqls=array();
		$arrs=array();
		$idlist=explode(',', $ids);
		foreach($idlist as $id){
			$arr=array();
			$arr[]=$id;
			$arr[]=$mid;
			$sql="update #__cart set status=0 where id=? and mid=? and status=1 ";
			array_push($sqls, $sql);
			array_push($arrs, $arr);
		}
		$dbAgent=DBAgent::getInstance();
	    $result = $dbAgent->QueryWithTransaction($sqls,$arrs);
	    return $result;

	}
	//获取产品属性
	function getProductProperty($ptid){
		$arr=array();
		$arr[]=$ptid;
		$sql="select * from #__product_property where ptid=? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);

	}

	function getCartProducts($mid){
        $arr = array();
        $arr[] = $mid;
        $sql = "select count(*) a from #__cart where mid = ? and status = 1";
        $dbAgent=DBAgent::getInstance();
        $result = $dbAgent->queryRecords($sql,$arr);
        return $result['0']['a'];
    }
	//获取产品最低价
    function getLowPricebyId($pid){
        $sql="select price from #__sku where pid=$pid and status=1";
        $dbAgent=DBAgent::getInstance();
        $result=$dbAgent->queryRecords($sql,array());
        $ret=array();
        foreach($result as $k=>$v){
            $ret[]=$v['price'];
        }
        @$res=min($ret);
        return $res;
    }
    
	function getProductProperties($pid){
		$arr=array();
		$arr[]=$pid;
		$sql="select * from #__sku where MD5(pid)=? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	//保存订单
	function saveOrders($orders,$products){
	    $sqls = array();
	    $arrs=array();
	    $now = time();
	    foreach($orders as $value){
			$arr=array();    
		    $arr[]=$value['ordercode'];$arr[]=$now;$arr[]=$value['province'];$arr[]=$value['city'];$arr[]=$value['district'];$arr[]=$value['district'];$arr[]=$value['consignee'];
		    $arr[]=$value['mobile'];$arr[]=$value['zip'];$arr[]='1';$arr[]='1';$arr[]=$value['mid'];$arr[]=$value['freight'];$arr[]=$value['totalprice'];$arr[]=$value['sid'];$arr[]=$value['remarks'];$arr[]=$value['couponid'];$arr[]=$value['disprice'];$arr[]=$value['invoiceid'];$arr[]=$value['type'];$arr[]=$value['agree'];$arr[]=$value['payer_mid'];$arr[]=$value['order_from_mid'];$arr[]=$value['address_id'];$arr[]=$value['payment_type'];
		    $sql = "insert into #__order(order_code,time,province,city,district,address,consignee,mobile,zip,status,state, ";
		    $sql .= "mid,freight,tot_fee,sid,remarks,couponid,dis_fee,invoice_id,type,agree,payer_mid,order_from_mid,address_id,payment_type) ";
		    $sql .= "values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? ) ";
		    array_push($sqls, $sql);
		    array_push($arrs, $arr);
	    }
	    foreach($products as $value){
		    $arr=array();
		    $arr[]=$value['ordercode'];$arr[]=$value['pid'];$arr[]=$value['buynum'];$arr[]=$value['price'];$arr[]=$value['name'];$arr[]=$value['images'];$arr[]=$value['props'];$arr[]=$value['skuid'];$arr[]=$value['intro'];$arr[]=1;
		    $sql = "insert into #__order_product(order_code,pid,num,price,name,images,props,skuid,intro,state) ";
		    $sql .= "values(?,?,?,?,?,?,?,?,?,?) ";
		    array_push($sqls, $sql);
		    array_push($arrs, $arr);
		    $arr1=array();
		    $arr1[]=$value['buynum'];$arr1[]=$value['pid'];$arr1[]=$value['skuid'];
		    $sql2 = "update #__sku set inventory = inventory-? where pid = ? and skuid = ? ";
		    array_push($sqls, $sql2);
		    array_push($arrs,$arr1);
		    
	    }
	    $dbAgent=DBAgent::getInstance();
	    $result = $dbAgent->QueryWithTransaction($sqls,$arrs);
	    return $result;
    }
	
	//获取产品属性的值
	function getPropertyVal($id){
		$arr=array();
		$arr[]=$id;
		$sql="select * from #__product_property_val where id=? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	
	//评论后更新产品评论数量
	function updateProCommentNum($pid){
		$arr = array();
		$arr[] = $pid;
		$sql = "update #__product set comment_num = comment_num + 1 where pid = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->query($sql,$arr);
	}
	
	//获取产品的评价
	function getProductComment($pid){
		$arr=array();
		$arr[]=$pid;
		$sql="select a.*,b.face,b.nickname from #__product_comment a join #__member b on a.mid=b.mid where MD5(a.pid)=? and a.status=1 and b.status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	//获取热卖产品
	function getHotSellProduct(){
		$arr=array();
		$arr[]=1;
		$sql="select * from #__product where status=? order by sale_num desc limit 3";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	//获取产品列表 sort 1 综合排序  2 销量  3 价格上升  5 评论数
	function getProductList($brandid,$ptid,$lprice,$rprice,$sort,$index,$pagesize,$property,$sid=null){
		$arr=array();
		$arr[]=1;
		$sql="select * from #__product where status=? ";
		if($brandid>0){
			$arr[]=$brandid;
			$sql.="and brand_id=? ";
		}
		if($ptid>0){
			$arr[]=$ptid;
			$sql.="and ptid=? ";
		}
		if($sid>0){
            $arr[]=$sid;
            $sql.="and sid=? ";
        }
		if($lprice>=0){
			$sql.="and price >= $lprice ";
		}
        if($rprice>0){
            $sql.="and price <= $rprice ";
        }
        if(!empty($property)){
            $sql.="and pid in(select pid from labring_sku where FIND_IN_SET($property,properties))";
        }
		if($sort==1){

		}else if($sort==2){
			$sql.="order by sale_num desc ";
		}else if($sort==3){
			$sql.="order by price asc ";
		}else if($sort==4){
			$sql.="order by comment_num desc ";
		}
		$sql .= " limit $index,$pagesize";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
//        return $sql;
	}
	//获取产品列表产品个数
	function getProductListCount($brandid,$ptid,$lprice,$rprice,$property){
		$arr=array();
		$arr[]=1;
		$sql="select count(*) as num from #__product where status=? ";
		if($brandid>0){
			$arr[]=$brandid;
			$sql.="and brand_id=? ";
		}
		if($ptid>0){
			$arr[]=$ptid;
			$sql.="and ptid=? ";
		}
		if($lprice>=0){
			$sql.="and price >= $lprice ";
		}
        if($rprice!=0){
            $sql.=" and price <= $rprice ";
        }
        if(!empty($property)){
            $sql.="and pid in(select pid from labring_sku where FIND_IN_SET($property,properties))";
        }
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		return $result['num'];
	}
	
	//sort 1 综合排序  2 销量  3 价格上升  4 评论数
	function getBrandProductList($brandid,$type,$sort,$index,$pagesize,$sid=0){
		$arr=array();
		$sql="select * from #__product where first_tid='$type' and status=1";
        if($brandid>0){
            $arr[]=$brandid;
            $sql.=" and brand_id=$brandid ";
        }
        if($sid>0){
            $arr[]=$sid;
            $sql.=" and sid=$sid ";
        }
		if($sort==1){
			//$sql.="order by rand() ";
		}else if($sort==2){
			$sql.="order by sale_num desc ";
		}else if($sort==3){
			$sql.="order by price asc ";
		}else if($sort==4){
			$sql.="order by comment_num desc ";
		}

		$sql .= " limit $index,$pagesize";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	function getBrandProductListCount($brandid,$type,$sid=0){
		$arr=array();
		$ptids=ProductServ::getLevel3ByL1($type);
		$sql="select  count(*) as num from #__product where first_tid='$type' and status=1 ";
		if($brandid>0){
            $arr[]=$brandid;
            $sql.=" and brand_id=$brandid ";
        }
        if($sid>0){
            $arr[]=$sid;
            $sql.=" and sid=$sid ";
        }

		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		return $result['num'];
	}
	//商城点击第一级
	function getProductListFT($ptid,$type,$brandid,$lprice,$rprice,$sort,$index,$pagesize,$property){
		$arr=array();
		$ptids="";
		if($type==1){
			$ptids=ProductServ::getLevel3ByL1($ptid);
		}else if($type==2){
			$ptids=ProductServ::getLevel3ByL2($ptid);
		}
		$arr[]=$ptids;
		$sql="select * from #__product where status=1 and  FIND_IN_SET(ptid,?) ";
		if($brandid>0){
			$arr[]=$brandid;
			$sql.="and brand_id=? ";
		}
		if($lprice>=0){
			$sql.="and price >= $lprice ";
		}
        if($rprice>0){
            $sql.="and price <= $rprice ";
        }
        if(!empty($property)){
            $sql.="and pid in(select pid from labring_sku where FIND_IN_SET($property,properties))";
        }
		if($sort==1){

		}else if($sort==2){
			$sql.="order by sale_num desc ";
		}else if($sort==3){
			$sql.="order by price asc ";
		}else if($sort==4){
			$sql.="order by comment_num desc ";
		}
		$sql .= " limit $index,$pagesize";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	function getProductListFTCount($ptid,$type,$brandid,$lprice,$rprice,$property){
		$arr=array();
		$ptids="";
		if($type==1){
			$ptids=ProductServ::getLevel3ByL1($ptid);
		}else if($type==2){
			$ptids=ProductServ::getLevel3ByL2($ptid);;
		}
		$arr[]=$ptids;
		$sql="select count(*) as num from #__product where status=1 and  FIND_IN_SET(ptid,?) ";
		if($brandid>0){
			$arr[]=$brandid;
			$sql.="and brand_id=? ";
		}
		if($lprice>=0){
			$sql.="and price >= $lprice ";
		}
        if($rprice!=0){
            $sql.=" and price <= $rprice ";
        }
        if(!empty($property)){
            $sql.="and pid in(select pid from labring_sku where FIND_IN_SET($property,properties))";
        }
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		return $result['num'];
	}
	
	//查询sku表的详情
	function getSkuDetail($propertys){
		$arr=array();
		$arr[]=$propertys;
		$sql="select * from #__sku where properties=? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	//获取购物车产品属性
	function getCartProduct($pid,$skuid){
		$arr=array();
		$arr[]=$pid;
		$arr[]=$skuid;
		$sql="select a.*,b.price as pprice,c.shop_name from #__product a join #__sku b on a.pid=b.pid join #__shop c on a.sid=c.sid where MD5(a.pid)=? and b.skuid=? and a.status=1 and b.status=1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	//获取购物车产品的属性
	function getCartProperties($skuid){
		$arr=array();
		$arr[]=$skuid;
		$sql="select b.*,c.name as pname from #__sku a join #__product_property_val b on FIND_IN_SET(b.id,a.properties)  
		join #__product_property c on b.propertyid=c.id where a.skuid=? and a.status=1 and b.status=1 and c.status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	//根据skuid获取pid
	function getProductBySkuId($skuid){
		$arr=array();
		$arr[]=$skuid;
		$sql="select pid from #__sku where skuid=? and status=1";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		return $result['pid'];
	}
	//获取产品组合
	function getProductGroup($pid){
		$arr=array();
		$arr[]=$pid;
		$sql="select DISTINCT c.* from #__product a join #__sku b on a.pid = b.pid join #__product_group c on 
FIND_IN_SET(b.skuid,c.skuids) where MD5(a.pid)= ?";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	function getSkuDetailById($id){
		$arr=array();
		$arr[]=$id;
		$sql="select * from #__sku where skuid=? and status=1";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		return $result;
	}
	//md5
	function getSkuDetailByIdmd($id){
		$arr=array();
		$arr[]=$id;
		$sql="select * from #__sku where MD5(skuid)=? and status=1";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		return $result;
	}


    //获取产品类别名称
    public function GetTypeName($ptid){
        $dbAgent=DBAgent::getInstance();
        $sql="select name from #__product_type where ptid=$ptid";
        $result=$dbAgent->querySingleRecord($sql,$params=array());
        return $result['name'];
    }

    public function GetProperty($ptid){
        $dbAgent=DBAgent::getInstance();
        $sql1="select id from labring_product_property where ptid=$ptid and status=1";
        $ids=$dbAgent->queryRecords($sql1,$params=array());
        $temp=array();
        foreach($ids as $key=>$id){
	       $tid=$id['id'];
	       $sql2="select * from labring_product_property_val where propertyid=$tid and status=1";
		   $result=$dbAgent->queryRecords($sql2,array());
		   foreach($result as $s){
			   array_push($temp, $s);
		   }
        }
        return $temp;
    }
    
    //
    public function getUserRecommendProduct($type,$size){//$type : 1,猜你喜欢 2,推荐产品;(逻辑待定)
	    if($type == 1) {
			$sql = "select * from #__product where status = 1 order by rand() limit $size ";    
	    } else {
		    $sql = "select * from #__product where status = 1 order by rand() limit $size ";
	    }
	    $dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,array());
    }

    public function getProductbyRand($num){
        $sql="select * from labring_product where status=1 order by rand() limit 4";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }
    
    
    //以下是我的店铺所用
    
    //获取所有产品对应级别类型
    
    function getMyProduct($pid){
		$arr=array();
		$arr[]=$pid;
		$sql="select * from #__product a left join #__shop b on a.sid = b.sid where a.pid=? and a.status <> 0 and b.status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
    
    //根据产品名称查询产品
    function getProductByName($pname){
		$arr=array();
		$arr[]=$pname;
		$sql="select * from #__product where name = ? and status <> 0 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);	
	}
    
    //根据指定级别查询产品类型
	function getProductTypeByLev($level){
		$arr=array();
		$arr[]=$level;
		$sql="select * from #__product_type where level= ? and status= 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);	
	}
	
	//添加产品
	function addMyProduct($product){	
		$table = "product";
		$insertColumns = array('sid','name','pinyin','first_tid','second_tid','third_tid','forth_tid','fifth_tid',      'ptid','brand_id','fre_id','images','time','status');
		$insertVals = array($product['sid'],$product['name'],$product['pinyin'],$product['first_tid'],$product['second_tid'],$product['third_tid'],$product['forth_tid'],$product['fifth_tid'],$product['ptid'],$product['brand_id'],$product['fre_id'],$product['images'],$product['time'],'2');
		
		if(isset($product['alias'])){
			$insertColumns[] = 'alias';
			$insertVals[] = $product['alias'];
		}
		if(isset($product['EnglishName'])){
			$insertColumns[] = 'EnglishName';
			$insertVals[] = $product['EnglishName'];
		}
		if(isset($product['CASnumber'])){
			$insertColumns[] = 'CASnumber';
			$insertVals[] = $product['CASnumber'];
		}
		if(isset($product['goods_code'])){
			$insertColumns[] = 'goods_code';
			$insertVals[] = $product['goods_code'];
		}
		if(isset($product['size'])){
			$insertColumns[] = 'size';
			$insertVals[] = $product['size'];
		}
		if(isset($product['purity'])){
			$insertColumns[] = 'purity';
			$insertVals[] = $product['purity'];
		}
		if(isset($product['address_id'])){
			$insertColumns[] = 'address_id';
			$insertVals[] = $product['address_id'];
		}
		if(isset($product['weight'])){
			$insertColumns[] = 'weight';
			$insertVals[] = $product['weight'];
		}
		if(isset($product['volume'])){
			$insertColumns[] = 'volume';
			$insertVals[] = $product['volume'];
		}
		if(isset($product['shelf_life'])){
			$insertColumns[] = 'shelf_life';
			$insertVals[] = $product['shelf_life'];
		}
		if(isset($product['packing'])){
			$insertColumns[] = 'packing';
			$insertVals[] = $product['packing'];
		}
		if(isset($product['unit'])){
			$insertColumns[] = 'unit';
			$insertVals[] = $product['unit'];
		}
		if(isset($product['producer'])){
			$insertColumns[] = 'producer';
			$insertVals[] = $product['producer'];
		}
		if(isset($product['store'])){
			$insertColumns[] = 'store';
			$insertVals[] = $product['store'];
		}
		if(isset($product['traffic'])){
			$insertColumns[] = 'traffic';
			$insertVals[] = $product['traffic'];
		}
		if(isset($product['is_harmful'])){
			$insertColumns[] = 'is_harmful';
			$insertVals[] = $product['is_harmful'];
		}
		
		if(isset($product['video_img'])){
			$insertColumns[] = 'video_img';
			$insertVals[] = $product['video_img'];
		}
		if(isset($product['video_url'])){
			$insertColumns[] = 'video_url';
			$insertVals[] = $product['video_url'];
		}
		if(isset($product['intro'])){
			$insertColumns[] = 'intro';
			$insertVals[] = $product['intro'];
		}
		if(isset($product['intro_mobile'])){
			$insertColumns[] = 'intro_mobile';
			$insertVals[] = $product['intro_mobile'];
		}
		if(isset($product['vipone'])){
			$insertColumns[] = 'v1_discount';
			$insertVals[] = $product['vipone'];
		}
		if(isset($product['viptwo'])){
			$insertColumns[] = 'v2_discount';
			$insertVals[] = $product['viptwo'];
		}
		if(isset($product['vipthree'])){
			$insertColumns[] = 'v3_discount';
			$insertVals[] = $product['vipthree'];
		}
		if(isset($product['vipfour'])){
			$insertColumns[] = 'v4_discount';
			$insertVals[] = $product['vipfour'];
		}
		
		if(isset($product['can_testing'])){
			$insertColumns[] = 'can_testing';
			$insertVals[] = $product['can_testing'];
		}
		if(isset($product['quality_testing'])){
			$insertColumns[] = 'quality_testing';
			$insertVals[] = $product['quality_testing'];
		}
		if(isset($product['can_guarantee'])){
			$insertColumns[] = 'can_guarantee';
			$insertVals[] = $product['can_guarantee'];
		}
		if(isset($product['guarantee_1'])){
			$insertColumns[] = 'guarantee_1';
			$insertVals[] = $product['guarantee_1'];
		}
		if(isset($product['guarantee_2'])){
			$insertColumns[] = 'guarantee_2';
			$insertVals[] = $product['guarantee_2'];
		}
		if(isset($product['guarantee_5'])){
			$insertColumns[] = 'guarantee_5';
			$insertVals[] = $product['guarantee_5'];
		}
		
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	//修改产品基本信息
	public function Modifypro($product){
		$table = "product";
		$updateColumns = array();
		$updateVals = array();
				
		if(isset($product['ptid'])){
			$updateColumns[] = 'ptid';
			$updateVals[] = $product['ptid'];
		}
		if(isset($product['first_tid'])){
			$updateColumns[] = 'first_tid';
			$updateVals[] = $product['first_tid'];
		}
		if(isset($product['second_tid'])){
			$updateColumns[] = 'second_tid';
			$updateVals[] = $product['second_tid'];
		}
		if(isset($product['third_tid'])){
			$updateColumns[] = 'third_tid';
			$updateVals[] = $product['third_tid'];
		}
		if(isset($product['forth_tid'])){
			$updateColumns[] = 'forth_tid';
			$updateVals[] = $product['forth_tid'];
		}
		if(isset($product['fifth_tid'])){
			$updateColumns[] = 'fifth_tid';
			$updateVals[] = $product['fifth_tid'];
		}
		if(isset($product['name'])){
			$updateColumns[] = 'name';
			$updateVals[] = $product['name'];
		}
		if(isset($product['brand_id'])){
			$updateColumns[] = 'brand_id';
			$updateVals[] = $product['brand_id'];
		}
		if(isset($product['fre_id'])){
			$updateColumns[] = 'fre_id';
			$updateVals[] = $product['fre_id'];
		}
		if(isset($product['address_id'])){
			$updateColumns[] = 'address_id';
			$updateVals[] = $product['address_id'];
		}
		if(isset($product['images'])){
			$updateColumns[] = 'images';
			$updateVals[] = $product['images'];
		}
		if(isset($product['alias'])){
			$updateColumns[] = 'alias';
			$updateVals[] = $product['alias'];
		}
		if(isset($product['EnglishName'])){
			$updateColumns[] = 'EnglishName';
			$updateVals[] = $product['EnglishName'];
		}
		if(isset($product['CASnumber'])){
			$updateColumns[] = 'CASnumber';
			$updateVals[] = $product['CASnumber'];
		}
		if(isset($product['goods_code'])){
			$updateColumns[] = 'goods_code';
			$updateVals[] = $product['goods_code'];
		}
		if(isset($product['size'])){
			$updateColumns[] = 'size';
			$updateVals[] = $product['size'];
		}
		if(isset($product['weight'])){
			$updateColumns[] = 'weight';
			$updateVals[] = $product['weight'];
		}
		if(isset($product['volume'])){
			$updateColumns[] = 'volume';
			$updateVals[] = $product['volume'];
		}
		if(isset($product['purity'])){
			$updateColumns[] = 'purity';
			$updateVals[] = $product['purity'];
		}
		if(isset($product['shelf_life'])){
			$updateColumns[] = 'shelf_life';
			$updateVals[] = $product['shelf_life'];
		}
		if(isset($product['packing'])){
			$updateColumns[] = 'packing';
			$updateVals[] = $product['packing'];
		}
		if(isset($product['unit'])){
			$updateColumns[] = 'unit';
			$updateVals[] = $product['unit'];
		}
		if(isset($product['producer'])){
			$updateColumns[] = 'producer';
			$updateVals[] = $product['producer'];
		}
		if(isset($product['store'])){
			$updateColumns[] = 'store';
			$updateVals[] = $product['store'];
		}
		if(isset($product['traffic'])){
			$updateColumns[] = 'traffic';
			$updateVals[] = $product['traffic'];
		}
		if(isset($product['is_harmful'])){
			$updateColumns[] = 'is_harmful';
			$updateVals[] = $product['is_harmful'];
		}
		if(isset($product['video_img'])){
			$updateColumns[] = 'video_img';
			$updateVals[] = $product['video_img'];
		}
		if(isset($product['video_url'])){
			$updateColumns[] = 'video_url';
			$updateVals[] = $product['video_url'];
		}
		if(isset($product['intro'])){
			$updateColumns[] = 'intro';
			$updateVals[] = $product['intro'];
		}
		if(isset($product['intro_mobile'])){
			$updateColumns[] = 'intro_mobile';
			$updateVals[] = $product['intro_mobile'];
		}
		if(isset($product['vipone'])){
			$updateColumns[] = 'v1_discount';
			$updateVals[] = $product['vipone'];
		}
		if(isset($product['viptwo'])){
			$updateColumns[] = 'v2_discount';
			$updateVals[] = $product['viptwo'];
		}
		if(isset($product['vipthree'])){
			$updateColumns[] = 'v3_discount';
			$updateVals[] = $product['vipthree'];
		}
		if(isset($product['vipfour'])){
			$updateColumns[] = 'v4_discount';
			$updateVals[] = $product['vipfour'];
		}
		if(isset($product['can_testing'])){
			$updateColumns[] = 'can_testing';
			$updateVals[] = $product['can_testing'];
		}
		if(isset($product['quality_testing'])){
			$updateColumns[] = 'quality_testing';
			$updateVals[] = $product['quality_testing'];
		}
		if(isset($product['can_guarantee'])){
			$updateColumns[] = 'can_guarantee';
			$updateVals[] = $product['can_guarantee'];
		}
		if(isset($product['guarantee_1'])){
			$updateColumns[] = 'guarantee_1';
			$updateVals[] = $product['guarantee_1'];
		}
		if(isset($product['guarantee_2'])){
			$updateColumns[] = 'guarantee_2';
			$updateVals[] = $product['guarantee_2'];
		}
		if(isset($product['guarantee_5'])){
			$updateColumns[] = 'guarantee_5';
			$updateVals[] = $product['guarantee_5'];
		}
		
		$conditionColumns = array('pid','sid','status');
		$conditionVals = array($product['pid'],$product['sid'],'2');
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//获取刚加入的产品
	function getJustInsertProduct($name,$sid,$ptid,$time){
		$sql = 'select * from #__product where name = "'.$name.'" and sid = "'.$sid.'" and ptid = "'.$ptid.'" ';
		$sql .= 'and time = "'.$time.'" ';
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql);	
	}
	
	//更新产品编号
	function updateProductNumber($pid,$pronumber){
		$arr = array();
		$arr[] = $pronumber;
		$arr[] = $pid;
		$sql = "update #__product set code = ? where pid = ? and status <> 0 ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
	}
	
	//根据pid和ptid获取产品
	function getProductByPidAndSid($pid,$sid){
		$arr = array();
		$arr[] = $sid;
		$arr[] = $pid;
		$sql = "select * from #__product where sid = ? and pid = ? and status <> 0 ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->querySingleRecord($sql,$arr);
	}
	
/*
	function getProductValbyPid($pid){
		$arr = array();
		$arr[] = $pid;
		$arr[] = $pid;
		$sql = "select c.* from #__product a join #__product_property b on a.ptid = b.ptid join #__product_property_val c on b.id = c.propertyid where a.pid = ? and c.pid = ? and a.status <> 0 and b.status = 1 and c.status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result = $dbAgent->queryRecords($sql,$arr);
	}
*/
	//根据pid获取属性值
	function getProductValbyPid($pid){
		$arr = array();
		$arr[] = $pid;
		$sql = "select * from #__product_property_val where pid = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result = $dbAgent->queryRecords($sql,$arr);
	}
		
	//根据pid获取产品库存;
    function getProductSkuByPid($pid){
	    $arr = array();
        $arr[] = $pid;
        $sql = "select * from #__sku where pid = ? and status = 1 ";
        $dbAgent=DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,$arr);
    }
	
	//获取属性及其属性值信息
/*
	function getPropertyAndVal($pid){
		$arr = array();
		$arr[] = $pid;
		$sql = "select b.name as property,a.* from #__product_property_val a join #__product_property b on a.propertyid = b.id where a.pid = ? and a.status = 1 and b.status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result = $dbAgent->queryRecords($sql,$arr);
	}
*/
	
	//根据条件获取产品属性值
	function getProPropertyValByCon($pid,$pro_id,$name){
		$arr = array();
		$arr[] = $pid;
		$arr[] = $pro_id;
		$arr[] = $name;
		$sql = "select count(*) as count from #__product_property_val where pid = ? and propertyid = ? and name = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->querySingleRecord($sql,$arr);
		if($result === false){
			return false;
		}
		return $result['count'];
	}
	
	//插入产品属性值
	function insertProPropertyVal($pid,$pro_id,$name){
		$table = "product_property_val";
		$insertColumns = array('pid','name','propertyid','status');
		$insertVals = array($pid,$name,$pro_id,'1');	
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	//根据id查找属性 (多个id)
	function getPropertyByIds($ids){
		$arr = array();
		$arr[] = $ids;
		$sql = "select * from #__product_property where find_in_set(id,?) and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	//根据属性id获取属性值
	function getPropertyValByProId($pro_id,$pid){
		$arr = array();
		$arr[] = $pro_id;
		$arr[] = $pid;
		$sql = "select * from #__product_property_val where propertyid = ? and pid = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
    //根据属性id,pid获取属性值
    function getPropertyValById($id,$pid){
        $arr = array();
        $arr[] = $id;
        $arr[] = $pid;
        $sql = "select * from #__product_property_val where id = ? and pid = ? and status = 1 ";
        $dbAgent=DBAgent::getInstance();
        return $result=$dbAgent->querySingleRecord($sql,$arr);
    }

	//清除该产品已存在的库存信息
    function clearProductSku($pid){
	    $arr = array();
	    $arr[] = $pid;
	    $sql = "update #__sku set status = 0 where pid = ? and status = 1 ";
	    $dbAgent = DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
    }
    
    //修改产品分类后清除该产品已存在的属性值
    function clearSameProValues($pid){
	    $arr = array();
	    $arr[] = $pid;
	    $sql = "update #__product_property_val set status = 0 where pid = ? and status = 1 ";
	    $dbAgent = DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
    }
	    
    //保存产品属性	前清除该产品已存在的属性值
    function clearSameProValuesByProids($pid,$pro_ids){
	    $arr = array();
	    $arr[] = $pid;
	    $arr[] = $pro_ids;
	    $sql = "update #__product_property_val set status = 0 where pid = ? and !find_in_set(propertyid,?) and status = 1 ";
	    $dbAgent = DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
    }
	
	//保存产品属性	后清除同一产品下相同属性多余的属性值
	function clearSamePropertyValues($pid,$pro_id,$propvals){		
		$arr = array();
		$arr[] = $propvals;
		$arr[] = $pid;
		$arr[] = $pro_id;
		$sql = "update #__product_property_val set status = 0 where !find_in_set(name,?) and pid = ? and propertyid = ? and status = 1 ";
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->query($sql,$arr);
	}
	
	function getSkuidArr($props){
		$this->newArr = array();
		$this->tempArr = array();
		$this->combinationArr($props,0);
		return $this->newArr;	
	}
	
	private function combinationArr($arr,$index){
		$size = count($arr);
		if($size > $index){
			$newIndex = $index + 1;
			for($i=0;$i<count($arr[$index]['vals']);$i++){
				$vals = $arr[$index]['vals'];
				$this->tempArr[$index] = $vals[$i]['id'];
				$this->combinationArr($arr,$newIndex);
			}
		}else{
			$newTempArr = array();
			foreach($this->tempArr as $value){
				$newTempArr[] = $value;
			}
			$this->newArr[] = $newTempArr;
		}
	}
	
	function getSkuArr($props){
		if(count($props) == 0){
			return array();
		}
		$this->newArr = array();
		$this->tempArr = array();
		$this->combinationArrWithParams($props,0);
		return $this->newArr;
	}
	
	private function combinationArrWithParams($arr,$index){
		$size = count($arr);
		if($size > $index){
			$newIndex = $index + 1;
			for($i=0;$i<count($arr[$index]['vals']);$i++){
				$vals = $arr[$index]['vals'];
				$this->tempArr['name'][$index] = $vals[$i]['name'];
				$this->tempArr['vals'][$index] = $vals[$i]['id'];
				$this->combinationArrWithParams($arr,$newIndex);
			}
		}else{
			$newTempArr = array();
			$newTempArr['name'] = array();
			$newTempArr['vals'] = array();
			foreach($this->tempArr['name'] as $value){
				$newTempArr['name'][] = $value;
			}
			foreach($this->tempArr['vals'] as $value){
				$newTempArr['vals'][] = $value;
			}
			$this->newArr[] = $newTempArr;
		}
	}
	
	//根据属性查库存
	function getSkuByProperty($properties,$pid){
		$arr = array();
		$arr[] = $pid;
		$sql = "select * from #__sku where pid = ? and status = 1 ";
		if($properties != ""){
			$arr[] = $properties;	
			$sql .= "and properties = ? ";
		}
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	//更新产品库存数据
	function updateProduct($pid,$upSkus,$addSkus,$productInfo,$skuids,$type){
		$sqls = array();
		$arr = array();
		
		foreach($upSkus as $sku){
			$arr1 = array();
			$arr1[] = $sku['price'];
			$arr1[] = $sku['inventory'];
			$arr1[] = $sku['inventoryWarn'];
			$arr1[] = $sku['skuid'];
			$arr1[] = $pid;
			
			$sql1 = "update #__sku set price = ?,inventory = ?,inventory_warn = ? where properties = ? and pid = ? and status = 1 ";
			$sqls[] = $sql1;
			$arr[] = $arr1;
		}
		
		foreach($addSkus as $sku){
			$arr2 = array();
			$arr2[] = $pid;
			$arr2[] = $sku['skuid'];
			$arr2[] = $sku['price'];
			$arr2[] = $sku['inventory'];
			$arr2[] = $sku['inventoryWarn'];
			
			$sql2 = "insert into #__sku(pid,properties,price,inventory,inventory_warn,status)values(?,?,?,?,?,'1') ";
			$sqls[] = $sql2;
			$arr[] = $arr2;
		}
		
		$arr3 = array();
		$arr3[] = $pid;
		$sql3 = "update #__sku set status = 0 where pid = ? and status = 1 ";
		foreach ($skuids as $skuid){
			$arr3[] = $skuid;
			$sql3 .= "and properties != ? ";
		}
		$sqls[] = $sql3;
		$arr[] = $arr3;
		
		$arr4 = array();
		$arr4[] = $productInfo['price'];
		$arr4[] = $pid;
		$sql4 = "update #__product set price = ? where pid = ? and status <> 0 ";
		$sqls[] = $sql4;
		$arr[] = $arr4;
		
		if($type == 2){
			$arr5 = array();
			$arr5[] = $pid;
			$sql5 = "update #__product_property_val set status = 0 where pid = ? and status = 1 ";
			$sqls[] = $sql5;
			$arr[] = $arr5;
		}
		
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->QueryWithTransaction($sqls,$arr);
	}

    //获取商品列表
    public function getProductLists($ch,$info){
        $arr = array();
        $sql = "";
        $dbAgent = DBAgent::getInstance();
        if($ch == 1){
            $sql = "SELECT count(*) as num FROM #__product product
                          JOIN (
                               SELECT GROUP_CONCAT(recommend.pid) AS recommendpids 
                                FROM #__product_recommend recommend 
                                WHERE recommend.pid > 0 AND recommend.`status` = 1
                            ) recommends
                            ON !FIND_IN_SET(product.pid,recommends.recommendpids) where product.status = 1";
            if(!empty($info)){
                $sqls = "select * from #__product_type where level = 2 and status = 1 and name = '$info' ";
                $data = $dbAgent->querySingleRecord($sqls,array());
                if(!empty($data)){
                    $level2 = $data['ptid'];
                    $sql = "";
                    $sql = "select count(*) as num from #__product where second_tid = $level2 and status = 1";
                }else{
                    $sql.= " and product.name like \"%$info%\" ";
                }
            }
        }else if($ch == 2){
            $sql = "SELECT count(*) as num FROM #__product product
                            JOIN (
                                SELECT GROUP_CONCAT(recommend.pid) AS recommendpids 
                                FROM #__product_recommend recommend 
                                WHERE recommend.pid > 0 AND recommend.`status` = 1
                            ) recommends
                            ON !FIND_IN_SET(product.pid,recommends.recommendpids) where product.status = 2";
            if(!empty($info)) {
                $sql.= " and product.name like \"%$info%\" ";
            }
        }else if ($ch == 3){
            if(empty($info)){
                $sql = "select count(*) as num from #__product p join #__product_recommend r on p.pid = r.pid where p.status = 1 and r.status = 1";
            }else{
                $sql = "select count(*) as num from #__product p join #__product_recommend r on p.pid = r.pid where p.name like \"%$info%\" and p.status = 1 and r.status = 1 ";
            }
        }
        $result = $dbAgent->queryRecords($sql,$arr);
        return $result[0]['num'];
    }

    //获取商品列表
    public function getPageProductLists($ch,$info,$index,$pagesize){
        $arr = array();
        $sql = "";
        $dbAgent = DBAgent::getInstance();
        if($ch == 1){
            $sql = "SELECT recommends.*,product.* FROM #__product product
                          JOIN (
                              SELECT GROUP_CONCAT(recommend.pid) AS recommendpids 
                              FROM #__product_recommend recommend 
                             WHERE recommend.pid > 0 AND recommend.`status` = 1
                            ) recommends
                            ON !FIND_IN_SET(product.pid,recommends.recommendpids) where product.status = 1 ";
            if(!empty($info)){
                $sqls = "select * from #__product_type where level = 2 and status = 1 and name = '$info' ";
                $data = $dbAgent->querySingleRecord($sqls,array());
                if(!empty($data)){
                    $level2 = $data['ptid'];
                    $sql = "";
                    $sql = "select * from #__product product where product.second_tid = $level2 and product.status = 1";
                }else{
                    $sql.= " and product.name like \"%$info%\" ";
                }
            }
            $sql.= " order by product.time desc";
        }else if($ch == 2){
            $sql = "SELECT recommends.*,product.* FROM #__product product
                            JOIN (
                                SELECT GROUP_CONCAT(recommend.pid) AS recommendpids 
                                FROM #__product_recommend recommend 
                                WHERE recommend.pid > 0 AND recommend.`status` = 1
                            ) recommends
                            ON !FIND_IN_SET(product.pid,recommends.recommendpids) where product.status = 2 ";
            if(!empty($info)) {
                $sql.= " and product.name like \"%$info%\" ";
            }
            $sql.= " order by product.time desc";
        }else if ($ch == 3){
            if(empty($info)){
                $sql = "SELECT p.*,t.name AS cname FROM labring_product p JOIN labring_product_recommend r ON p.pid = r.pid LEFT JOIN labring_product_type t ON p.second_tid = t.ptid WHERE p.STATUS = 1 AND r. STATUS = 1 AND t. STATUS = 1 ORDER BY r.time DESC";
            }else{
                $sql = "SELECT p.*,t.name AS cname FROM labring_product p JOIN labring_product_recommend r ON p.pid = r.pid LEFT JOIN labring_product_type t ON p.second_tid = t.ptid WHERE p.name like \"%$info%\" AND p.STATUS = 1 AND r. STATUS = 1 AND t. STATUS = 1 ORDER BY r.time DESC ";
            }
        }
        $sql .= " limit $index,$pagesize";
        return $dbAgent->queryRecords($sql,$arr);
    }

    //获取商品列表
    public function getBrandNameById($id){
        $arr = array();
        $arr[] = $id;
        $sql = "select b.name from #__brand b where brand_id = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    //根据id获取商品
    public function getProductById($id){
        $arr = array();
        $arr[] = $id;
        $sql = "select * from #__product where pid = ?";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    //下架商品
    function downProduct($id){
        $table = "product";
        $updateColumns = array('status');
        $updateVals = array(2);
        $conditionColumns = array('pid');
        $conditionVals = array($id);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    public function getShopByPid($id){
        $arr = array();
        $arr[] = $id;
        $sql = "select s.mid from #__shop s join #__product p on p.sid = s.sid  where p.pid = ? and s.status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    //给用户发送商品下架原因消息
    public function sendUserDownMessage($mid,$reason,$product){
        $table = "message";
        $insertColumns = array('from_id',"mid","title","content","time","is_read","status");
        $insertVals = array(0,$mid,"下架通知","理由：您的商品(".$product['name'].")      ".$reason,time(),0,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    //设置推荐商品
    public function recommendProductById($id,$flag,$product){
        $table = "product_recommend";
        $dbAgent = DBAgent::getInstance();
        if($flag == 1){
            $updateColumns = array('status');
            $updateVals = array(0);
            $conditionColumns = array('pid');
            $conditionVals = array($id);
            return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
        }else{
            $arr = array();
            $arr[] = $id;
            $sql = "select * from #__product_recommend where pid = ? and status = 1";
            $data = $dbAgent->querySingleRecord($sql,$arr);
            if(empty($data)){
                $insertColumns = array("pid","time","status",'ptid','level1','level2','level3','level4','level5');
                $insertVals = array($id,time(),1,$product['ptid'],$product['first_tid'],$product['second_tid'],$product['third_tid'],$product['forth_tid'],$product['fifth_tid']);
                return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
            }else{
                $updateColumns = array('status');
                $updateVals = array(1);
                $conditionColumns = array('pid');
                $conditionVals = array($id);
                return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
            }
        }
    }

    public function getProductTypeLevel1(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__product_type where level = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }
    public function getCurrentProductType($id){
        $arr = array();
        $arr[] = $id;
        $sql = "select * from #__product_type where ptid = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    public function geParentProductType($parentid){
        $arr = array();
        $arr[] = $parentid;
        $sql = "select * from #__product_type where ptid = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    public function getProductByLevel1($lv){
        $arr = array();
        $arr[] = $lv;
        $sql = "SELECT p.*, b. NAME AS bname,t. NAME AS tname FROM labring_product p JOIN labring_product_recommend r ON p.pid = r.pid JOIN labring_brand b ON p.brand_id = b.brand_id JOIN labring_product_type t ON p.second_tid = t.ptid WHERE r.level1 = ? AND r. STATUS = 1 AND p. STATUS = 1 AND b. STATUS = 1 AND t. STATUS = 1 ORDER BY p.time DESC";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    //手机搜索
    public function getProductBySearch($data){
        $arr = array();
        $arr[] = $data;
        $sql = "select p.* from #__product p join #__sku s on p.pid = s.pid  where p.name like \"%$data%\" and p.status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    function geProductCateByName($name){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__product_type where name like \"%$name%\" and status = ? ";
        $sql .= " limit 0,20";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    public function geProductTypeByName($name){
        $arr = array();
        $arr[] = $name;
        $sql = "select * from #__product_type where name = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    public function getDataByBId($id){
        $arr = array();
        $arr[] = $id;
        $sql = "select * from #__brand_type where brandid = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    //
    public function addBrandTypeInfo($data){
        $table = "brand_type";
        $insertColumns = "";
        $insertVals = "";
        if($data['level'] == 1){
            $insertColumns = array('brandid','first_ptid','status');
            $insertVals = array($data['bid'],$data['ptid'],1);
        }else if($data['level'] == 2){
            $insertColumns = array('brandid','second_ptid','status');
            $insertVals = array($data['bid'],$data['ptid'],1);
        }else if($data['level'] == 3){
            $insertColumns = array('brandid','third_ptid','status');
            $insertVals = array($data['bid'],$data['ptid'],1);
        }
        else if($data['level'] == 4){
            $insertColumns = array('brandid','forth_ptid','status');
            $insertVals = array($data['bid'],$data['ptid'],1);
        }else if($data['level'] == 5){
            $insertColumns = array('brandid','fifth_ptid','status');
            $insertVals = array($data['bid'],$data['ptid'],1);
        }
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    //
    function updateBrandTypeInfo($data){
        $table = "brand_type";
        $updateColumns = "";
        $updateVals = "";
        if($data['level'] == 1){
            $updateColumns = array('first_ptid');
            if(empty($data['info']['first_ptid'])){
                $updateVals = array($data['ptid']);
            }else{
                $updateVals = array($data['info']['first_ptid'].",".$data['ptid']);
            }
        }else if($data['level'] == 2){
            $updateColumns = array('second_ptid');
            if(empty($data['info']['second_ptid'])){
                $updateVals = array($data['ptid']);
            }else{
                $updateVals = array($data['info']['second_ptid'].",".$data['ptid']);
            }
        }
        else if($data['level'] == 3){
            $updateColumns = array('third_ptid');
            if(empty($data['info']['third_ptid'])){
                $updateVals = array($data['ptid']);
            }else{
                $updateVals = array($data['info']['third_ptid'].",".$data['ptid']);
            }
        }
        else if($data['level'] == 4){
            $updateColumns = array('forth_ptid');
            if(empty($data['info']['forth_ptid'])){
                $updateVals = array($data['ptid']);
            }else{
                $updateVals = array($data['info']['forth_ptid'].",".$data['ptid']);
            }
        }
        else if($data['level'] == 5){
            $updateColumns = array('fifth_ptid');
            if(empty($data['info']['fifth_ptid'])){
                $updateVals = array($data['ptid']);
            }else{
                $updateVals = array($data['info']['fifth_ptid'].",".$data['ptid']);
            }
        }
        $conditionColumns = array('brandid','status');
        $conditionVals = array($data['bid'],1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    public function deleteProductById($id){
        $table = "product";
        $updateColumns = "status";
        $updateVals = 0;
        $conditionColumns = array('pid','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }


}