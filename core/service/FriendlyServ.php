<?php
class FriendlyServ{
    //获取友情链接
    public function getFriendlyLink($order,$num,$page){
        $a=($page-1)*$num;
        $dbAgent = DBAgent::getInstance();
        $sql="select * from labring_friendly_link where status=1 order by $order";
        if(isset($num)){
            $sql.=" limit $a,$num";
        }
        return $result=$dbAgent->queryRecords($sql,array());
    }

    //获取数量
    public function getFriendlynum(){
        $dbAgent = DBAgent::getInstance();
        $sql="select count(*) as num from labring_friendly_link where status=1";
        $result=$dbAgent->querysingleRecord($sql,array());
        return $result['num'];
    }
}

?>