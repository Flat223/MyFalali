<?php 
class UserAgent{
	
	//构造函数->默认载入函数
    public function __construct(){
    }
 
    public static function isLogin(){
	    if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
		    return true;
	    }
	    return false;
    }
    
    public static function getUser(){
	    if(self::isLogin()){
		    return $_SESSION['user'];
	    }
	    return null;
    }
    
    public static function cancelUser(){
	    unset($_SESSION['user']);
    }
    
    public static function addUser($user){
	    $_SESSION['user'] = $user;
    }
    
    public static function addAdmin($admin){
	    $_SESSION['admin'] = $admin;
    }
    
    public static function isAdminLogin(){
	    if(isset($_SESSION['admin']) && !empty($_SESSION['admin'])){
		    return true;
	    }
	    return false;
    }
    
    public static function getAdmin(){
	    if(self::isAdminLogin()){
		    return $_SESSION['admin'];
	    }
	    return null;
    }
  	
  	public static function getUserFund(){
	  	$user=$_SESSION['user'];
	  	if($user['type']!=1){
		  	return -1;
	  	}
	  	if($user['bind_status']!=2){
		  	return -2;
	  	}
	  	$cmid=$user['bind_company'];
	  	FileUtil::requireService("CollegeServ");
	  	$service=new CollegeServ();
	  	$result=$service->getUserFund($user['mid'],$cmid);
	  	if($result===false){
		  	return -3;
	  	}else if($result==null){
		  	return -4;
	  	}
	  	$all=$result['total_fund'];
	  	$used=$result['used_money'];
	  	
	  	$left=$all-$used; 
	  	if($left<=0){
		  	return -5;
	  	}
	  	return $left;
  	}  
}
?>