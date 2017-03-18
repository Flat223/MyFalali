<?php
class InvoiceServ{
	//保存发票抬头
	function insertInvoice($title,$mid){
		$table = "invoice";
		$insertColumns = array('title','mid',);
		$insertVals = array($title,$mid);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}	
	//获取发票抬头的信息
	function getInvoice($mid){
		$arr=array();
		$arr[]=$mid;
		$sql="select * from #__invoice where mid=? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	
	function insertOrderInvoice($invoice){
		$table="invoice_order";
		$insertColumns=array('title','type','content','mobile','email','company_name','code','re_location','re_mobile','bank_name'
		,'bank_account','name','invoice_code');
		$insertVals=array($invoice['title'],$invoice['type'],$invoice['content'],$invoice['mobile'],$invoice['email'],$invoice['company_name'],$invoice['code'],$invoice['re_location'],$invoice['re_mobile'],$invoice['bank_name'],$invoice['bank_account'],$invoice['name'],$invoice['invoice_code']);
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	//个人中心 获取我的发票信息
	function getUserInvoiceList($mid,$type){
		$arr=array();
		$arr[]=$mid;
		$arr[]=$type;
		$sql="select a.mid,a.title,b.* from #__invoice a join #__invoice_order b on a.id = b.id where a.mid = ? and b.type = ? and a.status = 1 ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	//保存抬头
	function saveTitle($title,$mid){
		$table="invoice";
		$insertColumns=array('title','mid','status');
		$insertVals=array($title,$mid,1);
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	//个人中心 删除我的发票
	function deleteUserInvoice($mid,$rid){
		$table = "invoice";
		$updateColumns = array('status');
		$updateVals = array('0');
		$conditionColumns = array('mid','id','status');
		$conditionVals = array($mid,$rid,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);	
	}
	//获取发票列表
    function getOrderInvoice($page,$num,$type){
        $a=($page-1)*$num;
        $sql="select * from #__invoice_order where status!=0";
        if($type && $type!=""){
            $sql.=" and type=$type";
        }
        $sql.=" limit $a,$num";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }
    //获取发票数量按type
    public function getOrderNum($type){
        $sql="select count(*) as num from #__invoice_order where status!=0";
        if($type && $type!=""){
            $sql.=" and type=$type";
        }
        $dbAgent = DBAgent::getInstance();
        $result=$dbAgent->querySingleRecord($sql,array());
        return $result['num'];
    }

    //审核发票
    public function CheckInvoice($vid,$status){
        $table = "invoice_order";
        $updateColumns = array('status');
        $updateVals = array($status);
        $conditionColumns = array('id');
        $conditionVals = array($vid);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);

    }
}
?>