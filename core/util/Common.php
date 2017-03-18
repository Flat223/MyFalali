<?php
class Common{
	
	//构造函数->默认载入函数
    public function __construct(){
    }
    
    public static function isInteger($value){
	    return is_numeric($value) && is_int($value+0);
    }
    
    public static function isMobile($mobile){
		$mobileRegExp = "/^((\+?86)|(\(\+86\)))?(13[012356789][0-9]{8}|15[012356789][0-9]{8}|18[02356789][0-9]{8}|147[0-9]{8}|1349[0-9]{7})$/";
		return preg_match($mobileRegExp, $mobile);
	}
	
	public static function isEmail($email){
		$emailRegExp = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
		return preg_match($emailRegExp, $email);
	}
	
	//验证整数
	public static function isInt($val){
		$pattern = "/^-?\d+$/";
		return preg_match($pattern, $val);
	}
	
	//验证正整数
	public static function isPositiveInt($val){
		$pattern = "/^[0-9]*[1-9][0-9]*$/";
		return preg_match($pattern, $val);
	}
	
	//验证负整数
	public static function isNegtiveInt($val){
		$pattern = "/^-[0-9]*[1-9][0-9]*$/";
		return preg_match($pattern, $val);
	}
	
	//验证非负整数
	public static function isNonnegativeInt($val){
		$pattern = "/^\d+$/";
		return preg_match($pattern, $val);
	}
	
	//验证非正整数
	public static function isNonPositiveInt($val){
		$pattern = "/^((-\d+)|(0+))$/";
		return preg_match($pattern, $val);
	}
	
	//验证浮点数
	public static function isFloat($val){
		$pattern = "/^(-?\d+)(\.\d+)?$/";
		return preg_match($pattern, $val);
	}
	
	//验证正浮点数
	public static function isPostiveFloat($val){
		$pattern = "/^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/";
		return preg_match($pattern, $val);
	}
	
	//验证负浮点数
	public static function isNegativeFloat($val){
		$pattern = "/^(-(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*)))$/";
		return preg_match($pattern, $val);
	}
	
	//验证非负浮点数
	public static function isNonnegativeFloat($val){
		$pattern = "/^\d+(\.\d+)?$/";
		return preg_match($pattern, $val);
	}
	
	//验证非正浮点数
	public static function isNonPositiveFloat($val){
		$pattern = "/^((-\d+(\.\d+)?)|(0+(\.0+)?))$/";
		return preg_match($pattern, $val);
	}
	
/*
	//验证邮箱
	public static function isEmail($val){
		$pattern = "/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/";
		return preg_match($pattern, $val);
	}
*/
	
	//验证url
	public static function isUrl($val){
		$pattern = "/^[a-zA-z]+:\/\/(\w+(-\w+)*)(\.(\w+(-\w+)*))*(\?\S*)?$/";
		return preg_match($pattern, $val);
	}
	
	//验证账号用（数字、英文字母或者下划线）
	public static function isAccount($val){
		$pattern = "/^\w+$/";
		return preg_match($pattern, $val);
	}
	
	public static function makePage($page,$baseurl){
		$html = "<ul>";
		if($page->hasPrevious()){
			$html .= "<li class='page_privous'><a href='".$baseurl."&page=".($page->getCurrentPage()-1)."' >上一页</a></li>";
		}else{
			$html .= "<li class='page_privous disabled'><a href='javascript:void(0);'>上一页</a></li>";
		}
		if($page->getCurrentPage() >= 7){
			$html .= "<li><a href='".$baseurl."&page=1'>1</a></li>";
			$html .= "<li><a href='".$baseurl."&page=2'>2</a></li>";
			$html .= "<li class='page_eliipsis'>...</li>";
			$html .= "<li><a href='".$baseurl."&page=".($page->getCurrentPage()-2)."' >".($page->getCurrentPage()-2)."</a></li>";
			$html .= "<li><a href='".$baseurl."&page=".($page->getCurrentPage()-1)."' >".($page->getCurrentPage()-1)."</a></li>";
		}else{
			for($i=1;$i<$page->getCurrentPage();$i++){
				$html .= "<li><a href='".$baseurl."&page=".$i."'>".$i."</a></li>";
			}
		}
		$html .= "<li class='page_active'><a href='javascript:void(0);'>".$page->getCurrentPage()."</a></li>";
		if($page->getPageCount() > ($page->getCurrentPage()+5)){
			$html .= "<li><a href='".$baseurl."&page=".($page->getCurrentPage()+1)."'>".($page->getCurrentPage()+1)."</a></li>";
			$html .= "<li><a href='".$baseurl."&page=".($page->getCurrentPage()+2)."'>".($page->getCurrentPage()+2)."</a></li>";
			$html .= "<li class='page_eliipsis'>...</li>";
			$html .= "<li><a href='".$baseurl."&page=".($page->getPageCount()-1)."'>".($page->getPageCount()-1)."</a></li>";
			$html .= "<li><a href='".$baseurl."&page=".$page->getPageCount()."'>".$page->getPageCount()."</a></li>";
		}else{
			for($i=($page->getCurrentPage()+1);$i<=$page->getPageCount();$i++){
				$html .= "<li><a href='".$baseurl."&page=".$i."'>".$i."</a></li>";
			}
		}
		if($page->hasNext()){
			$html .= "<li class='page_next'><a href='".$baseurl."&page=".($page->getCurrentPage()+1)."' >下一页</a></li>";
		}else{
			$html .= "<li class='page_privous disabled'><a href='javascript:void(0);'>下一页</a></li>";
		}
		$html .= "</ul>";
		return $html;
	}
	
	//将数字转化为时间数值
	public static function  transTimeFromNum($num,$needseconds = false){
		if($num <= 0){
			if($needseconds){
				return "0分钟0秒";
			}
			return "0分钟";
		}
		$days = 0;
		$hours = 0;
		$minutes = 0;
		$seconds = 0;
		if($num >= 86400){
			$days = intval(floor($num/86400));
			$num = $num%86400;
		}
		if($num >= 3600){
			$hours = intval(floor($num/3600));
			$num = $num%3600;
		}
		if($num >= 60){
			$minutes = intval(floor($num/60));
			$num = $num%60;
		}
		$seconds = $num;
		$str = "";
		if($days > 0){
			$str .= $days."天".$hours."小时".$minutes."分钟";
		}else if($hours > 0){
			$str .= $hours."小时".$minutes."分钟";
		}else if($minutes > 0){
			$str .= $minutes."分钟";
		}
		if($needseconds){
			$str .= $seconds."秒";
		}
		if($str == ""){
			$str = "0分钟";
		}
		return $str;	
	}
	
	function getExistYears($time){
	    $now = time();
	    $seconds = $now - $time;
	    if($seconds <= 0){
		    return 1;
	    }
	    $year = ceil($seconds/(3600*24*365));
	    return $year;
    }
	
	function getRandomToken($len=10,$format='ALL'){
		$is_abc = $is_numer = 0;
		$randToken = $tmp ='';  
		switch($format){
			case 'ALL':
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				break;
			case 'CHAR':
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				break;
			case 'NUMBER':
				$chars='0123456789';
				break;
			default :
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				break;
		}
		mt_srand((double)microtime()*1000000*getmypid());
		while(strlen($randToken) < $len){
			$tmp = substr($chars,(mt_rand()%strlen($chars)),1);
			if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 ) || $format == 'CHAR'){
				$is_numer = 1;
			}
			if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
				$is_abc = 1;
			}
			$randToken.= $tmp;
		}
		if($is_numer <> 1 || $is_abc <> 1 || empty($randToken) ){
			$randToken = getRandomToken($len,$format);
		}
		return $randToken;
	}
	
	public static function getIP(){
		$ip=false;
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if($ip){
				 array_unshift($ips, $ip); 
				 $ip = FALSE; 
			}
			for ($i = 0; $i < count($ips); $i++) {
				if (!preg_match ("/^(10|172\.16|192\.168)\./i", $ips[$i])) {
					$ip = $ips[$i];
					break;
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}
	
   public static function UNESCAPE($str){ 
		$ret = ''; 
		$len = strlen($str); 
		for ($i = 0; $i < $len; $i++){ 
			if ($str[$i] == '%' && $str[$i+1] == 'u'){ 
				$val = hexdec(substr($str, $i+2, 4)); 
				if ($val < 0x7f) $ret .= chr($val); 
				else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f)); 
				else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f)); 
				$i += 5; 
			} 
			else if ($str[$i] == '%'){ 
				$ret .= urldecode(substr($str, $i, 3)); 
				$i += 2; 
			} 
			else $ret .= $str[$i]; 
		} 
		return $ret; 
	}

	public static function getRandOrdercode(){
		$now = time();
		$rand = rand(1,9999);
		$strlen = strlen($rand);
		if($strlen < 4){
			$len = 4 - $strlen;
			for($i=0;$i<$len;$i++){
				$rand = '0'.$rand;
			}
		}
		$code = $now.$rand;
		return $code;
	}
	
	
}
?>