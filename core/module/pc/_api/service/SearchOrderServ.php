<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SearchOrderServ extends BaseAction{
    public function action(){
        $DBAgent=DBAgent::getInstance();

        $mid=isset($_POST['mid'])?$_POST['mid']:"";
        $bind_company=isset($_POST['bind_company'])?$_POST['bind_company']:"";
        $product=isset($_POST['product'])?$_POST['product']:"";
        $order_code=isset($_POST['order_code'])?$_POST['order_code']:"";
        $start_time=isset($_POST['start_time'])?strtotime($_POST['start_time']):"";
        $end_time=isset($_POST['end_time'])?strtotime($_POST['end_time']):"";
        $type=isset($_POST['type'])?$_POST['type']:"";
        $sql="select labring_member.name,labring_order.* from labring_order left join labring_member on labring_order.mid=
              labring_member.mid where labring_order.order_from_mid in (select mid from labring_member where bind_company=$bind_company)";
        if(!empty($product)){
            $sql.=" and labring_order.order_code in (select order_code from labring_order_product where name like '%".$product."%')";
        }
        if(!empty($order_code)){
            $sql.=" and labring_order.order_code=$order_code";
        }
        if(!empty($mid)){
            $sql.=" and order_from_mid in(select mid from labring_member where name like '%".$mid."%')";
        }
        if($start_time){
            $sql.=" and time>=$start_time";
        }
        if($end_time){
            $sql.=" and time<=$end_time";
        }

        $sql.=" order by id DESC";
        $result=$DBAgent->queryRecords($sql,array());
        $sql2="select * from labring_order_product where 1=1";
//        if(!empty($product)){
//            $sql2.=" and name like '%".$product."%'";
//        }
        foreach($result as $k=>$v){
            $sql_q=$sql2." and order_code='".$v['order_code']."'";
            $result[$k]['product']=$DBAgent->queryRecords($sql_q,array());
            if(empty($result[$k]['product'])){
                unset($result[$k]);
            }
        }
        exit(json_encode($result));
    }

}
?>