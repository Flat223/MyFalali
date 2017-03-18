<?php
class IdentityServ{
    public function GetUseridentity($mid){
        $sql="select * from labring_identity where mid=$mid order by id DESC";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,array());
    }
}
?>