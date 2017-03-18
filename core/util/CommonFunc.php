<?php

/**
 * 一些公共方法
 * Class CommonFunc
 */
class CommonFunc {
	public function __construct() {
	}
	
	/*
	 * 生成表格化 HTML
	 * $tableheader thead 元素用于对 HTML 表格中的表头内容进行分组
	 * $tabletbody tbody 元素应该与 thead 和 tfoot 元素结合起来使用
	 * $tablecellwidth 记录 array("10%","20%","70%")，每一个cell 的宽度，从左至右
	 *
	 */
	public static function GenTableView($tableheader,$tabletbody,$tablefooter,$tablecellwidth)
	{
		$rand = time();
		$tableclass= "ichuk-table";
		$tableid= $tableclass."-".$rand;
		$tableheaderclass= "ichuk-table-header";
		$tablefooterclass= "ichuk-table-footer";
		$tablebodyclass= "ichuk-table-body";
		$html = "<style>  
	
					#$tableid
					{ 
						font-size: 12px;
						background: #fff; 
						width:100%; 
						border-collapse: collapse;
						text-align: center;
					}
					#$tableid th
					{
						font-size: 14px;
						font-weight: normal;
						color: #039;
					    word-break: break-all;
						padding: 10px 8px;
						border-bottom: 2px solid #6678b1;
					}
					#$tableid td
					{
						border-bottom: 1px solid #ccc;
						color: #669;
					    word-break: break-all;
						padding: 6px 8px;
					}
					#$tableid tbody tr:hover td
					{
					    word-break: break-all;
						color: #009;
					}
				 </style>
		         ";
	    $html .= "<table border='0' class='$tableclass' id='".$tableid."'>";
	    if(count($tableheader) > 0)
		{
			$html .=  "<thead class='$tableheaderclass'>
						<tr>";
			foreach($tableheader as $headkey=>$headval)
			{
				$html .=   "<th style='width:".$tablecellwidth[$headkey].";'>$headval</th>";
			}
			$html .=   "</tr>
					  </thead>";
		}
	
		
	    if(count($tablefooter) > 0)
		{
			$html .= "<tfoot class='$tablefooterclass'>
						<tr>";
			foreach($tablefooter as $footkey=>$footval)
			{
				$html .=   "<td style='width:".$tablecellwidth[$footkey].";'>$footval</td>";
			}
			$html .=   "</tr>
					  </tfoot>";
		}
	
	
	
		$html .= "<tbody class='$tablebodyclass'>";
		if(count($tabletbody) > 0)
		{
			foreach($tabletbody as $bodykey=>$bodyval)
			{
			    $html .=   "<tr>";
		        foreach($bodyval as $tdkey=>$tdval)
				{
					$html .=   "<td style='width:".$tablecellwidth[$tdkey].";'>$tdval</td>";
				} 
				$html .=   "</tr>";
		    } 
		}
		else
		{
			$html .=   "<tr><td colspan='".count($tableheader)."'>没有您需要的数据</td></tr>";
		}
	    
		$html .=  "</tbody>
				</table>";
		return $html;
	
	}


    function object_to_array($obj){
		$_arr = is_object($obj)? get_object_vars($obj) :$obj;
		foreach ($_arr as $key => $val){
			$val=(is_array($val)) || is_object($val) ? $this->object_to_array($val) :$val;
			$arr[$key] = $val;
		}
		return $arr; 
	}

    /*
    * 获取IP地址
    */
    function GetRemoteAddress()
    {
	    if(!empty($_SERVER["HTTP_CLIENT_IP"]))
		{
			$cip = $_SERVER["HTTP_CLIENT_IP"];
		}
		else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
		{
			$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		else if(!empty($_SERVER["REMOTE_ADDR"]))
		{
			$cip = $_SERVER["REMOTE_ADDR"];
		}
		else
		{
			$cip = '';
		}
		preg_match("/[\d\.]{7,15}/", $cip, $cips);
		$cip = isset($cips[0]) ? $cips[0] : 'unknown';
		unset($cips);
		return $cip;
    }

	/**
	 *  中文截取2，单字节截取模式
	 *
	 * @access    public
	 * @param     string  $str  需要截取的字符串
	 * @param     int  $slen  截取的长度
	 * @param     int  $startdd  开始标记处
	 * @return    string
	 */
	function cn_substr_utf8($str, $length, $start=0)
	{
		if(strlen($str) < $start+1)
		{
			return '';
		}
		preg_match_all("/./su", $str, $ar);
		$str = '';
		$tstr = '';

		//为了兼容mysql4.1以下版本,与数据库varchar一致,这里使用按字节截取
		for($i=0; isset($ar[0][$i]); $i++)
		{
			if(strlen($tstr) < $start)
			{
				$tstr .= $ar[0][$i];
			}
			else
			{
				if(strlen($str) < $length + strlen($ar[0][$i]) )
				{
					$str .= $ar[0][$i];
				}
				else
				{
					break;
				}
			}
		}
		return $str;
	}
	 

	/*
	* 内容图片替换为本站图片
	*
	*/

	function ContentImageFilter($content)
	{
		$ImageAgent = new ImageAgent();
		$images = $this->GetStringImg($content);
		if(count($images) > 0)
		{
			foreach($images as $value)
			{
				if(strpos($value, "http://".$_SERVER['HTTP_HOST']) === false && CheckUrlFormat($value))
				{
					$_litpic = $ImageAgent->DownloadImage($value,true);
					if($_litpic['ret'] == 1)
					{
						$litpic = $_litpic['relative'];
						$content = str_replace($value, $litpic, $content);
					}
				}
			}
			
		}
		return $content;
	}

	/*
	* getStringImg()获取字符串出现的图片标签并返回图片集
	*
	*
	*/
	function GetStringImg($string){ 
		 $preg = "/<img.*?src=[\'\"](.+?)[\'\"].*?>/i";
		 preg_match_all($preg, $string, $match);  
		 $imgurl = $match[1];  
		 return $imgurl;
	}

	function CheckLogin()
    {
	    if(isset($_COOKIE["iChukUserID"]))
	    {
		    setcookie("iChukUserID", $_COOKIE["iChukUserID"], time()+3600,'/',CookieDomain);
	    }
	    $mid = isset($_COOKIE["iChukUserID"])?$_COOKIE["iChukUserID"]:"0";  
	    return intval($mid);
    }

	function transferTime($time,$type=""){
		if($type == "full")
		{
			$resultTime = date('Y-m-d H:i:s',$time);
		}
		else if($type == "month")
		{
			$resultTime = date('Y-m',$time);
		}
		else if($type == "year")
		{
			$resultTime = date('Y',$time);
		}
		else if($type == "singleday")
		{
			$resultTime = date('d',$time);
		}
		else if($type == "singlemonth")
		{
			$resultTime = date('m',$time);
		}
		else if($type == "monthday")
		{
			$resultTime = date('m-d',$time);
		}
		else
		{
			$resultTime = date('Y-m-d',$time);   
		}
		return $resultTime; 
	}

	/*
	 * 生成分页HTML
	 * $linkhtml = "../../?console/demo/{page}/{pagesize}/demo2/demo3/"
	 * $pagetemplet = "{page}"
	 * $pagesizetemplet = "{pagesize}"
	 */
	public static function GenPageBreakHtml($page,$pagesize,$total,$linkhtml="",$pagetemplet,$pagesizetemplet,$wrapclass="",$itemclass="")
	{
		$version = time();
		$linkurl = "";
		$_totalpage = ceil(intval($total)/intval($pagesize));
		$pagehtml ="<!--iChuk Auto PageBreak V1.0-->";
		$pagehtml .= "<div class='ichuk-page-break ".$wrapclass."' data-version='".$version."' data-total='".$total."' data-totalpage='".$_totalpage."'  style='overflow:hidden;'>"; 
		$pagehtml .="  <div class='pagelist' style='float:left;'>";

	  
		$linkhtml_ = empty($linkhtml)?"":str_replace($pagesizetemplet,$pagesize,$linkhtml);
		$pagehtml .='<a class="'.$itemclass.'" >共 '.ceil(intval($total)/intval($pagesize)).' 页</a>';

		if($_totalpage < 7)
		{
			for($i = 1;$i < ($_totalpage+1);$i++)
			{
				if($i == $page)
				{
					$pagehtml .="<a class='".$itemclass." on'>".$i."</a>";
				}
				else
				{
					if(!empty($linkhtml_))
					{
						$linkhtml_2 = str_replace($pagetemplet,$i,$linkhtml_);
					}
					$pagehtml .="<a class='".$itemclass."' data-page='".$i."' href='".$linkhtml_2."'>".$i."</a>";
				}
				
			}
		}
		else
		{
			for($i = 1;$i < (3+1);$i++)
			{
				if($i == $page)
				{
					$pagehtml .="<a class='".$itemclass." on'>".$i."</a>";
				}
				else
				{
					if(!empty($linkhtml_))
					{
						$linkhtml_2 = str_replace($pagetemplet,$i,$linkhtml_);
					}
					$pagehtml .="<a class='".$itemclass."' data-page='".$i."' href='".$linkhtml_2."'>".$i."</a>";
				}
				
			}
			
			if(intval($page) >= 3 && intval($page) < ($_totalpage - 2))
			{ 
				if(intval($page) > 4)
				{
					$pagehtml .="<a class='".$itemclass."'>...</a>";
					if(!empty($linkhtml_))
					{
						$linkhtml_2 = str_replace($pagetemplet,$page-1,$linkhtml_);
					}
					$pagehtml .="<a class='".$itemclass."' data-page='".($page-1)."' href='".$linkhtml_2."'>".($page-1)."</a>"; 
				} 
				if(intval($page) !=3)
				{
					$pagehtml .="<a class='".$itemclass." on'>".$page."</a>";
				} 
				if(intval($page) < ($_totalpage - 3))
				{
					if(!empty($linkhtml_))
					{
						$linkhtml_2 = str_replace($pagetemplet,$page+1,$linkhtml_);
					}
					$pagehtml .="<a class='".$itemclass."' data-page='".($page+1)."' href='".$linkhtml_2."'>".($page+1)."</a>";
					$pagehtml .="<a class='".$itemclass."'>...</a>";
				} 
			}
			else
			{
				$pagehtml .="<a class='".$itemclass."'>...</a>";
				if(intval($page) > 4 && intval($page) <= ($_totalpage - 2))
				{
					if(!empty($linkhtml_))
					{
						$linkhtml_2 = str_replace($pagetemplet,$page-1,$linkhtml_);
					}
					$pagehtml .="<a class='".$itemclass."' data-page='".($page-1)."' href='".$linkhtml_2."'>".($page-1)."</a>";  
				}
				 
			}
			
			for($i = ($_totalpage - 2);$i < ($_totalpage+1);$i++)
			{
				if($i == $page)
				{
					$pagehtml .="<a class='".$itemclass." on'>".$i."</a>";
				}
				else
				{
					if(!empty($linkhtml_))
					{
						$linkhtml_2 = str_replace($pagetemplet,$i,$linkhtml_);
					}
					$pagehtml .="<a class='".$itemclass."' data-page='".$i."' href='".$linkhtml_2."'>".$i."</a>";
				}
				
			}
		}
		$pagehtml .="  </div>";
		$pagehtml .="  <div class='quickpage' style='float:left;'>";
		if(intval($page) != 1)
		{ 
			if(!empty($linkhtml_))
			{
				$linkhtml_2 = str_replace($pagetemplet,(intval($page)-1),$linkhtml_);
			}
			$pagehtml .="<a data-page='".(intval($page)-1)."' href='".$linkhtml_2."' style='margin:0 5px;'>上一页</a>";
		}
		if(intval($page) < intval($_totalpage))
		{
			if(!empty($linkhtml_))
			{
				$linkhtml_2 = str_replace($pagetemplet,(intval($page)+1),$linkhtml_);
			}
			$pagehtml .="<a data-page='".(intval($page)+1)."' href='".$linkhtml_2."' style='margin:0 5px;'>下一页</a>";
		}
		$pagehtml .="  </div>";
		$pagehtml .='  <input class="pageinput" type="text" name="gotopage" placeholder="页码">';
		$pagehtml .='  <input class="pagego" type="button" name="gotoview" value="查看">';
		$pagehtml .='  <script>
							$("input[name=gotoview]").click(function(){
								var page = $("input[name=gotopage]").val();
								if(page != "")
								{
									if(Number(page) <= Number('.$_totalpage.') && Number(page) > 0)
									{
										var linkhtml = "'.$linkhtml_.'";
										linkhtml = linkhtml.replace("{page}", page);
										location.href = linkhtml;
									}
									else
									{
										alert("请输入正确页码");
									}
								}
							})
						</script>';

		$pagehtml .="</div>";
	 
		return $pagehtml;
	}
	
	/**
	 * 验证传递数据,将其转化为合法数据
	 * @param $params
	 * @param int $type
	 * @return mixed
	 */
	public function getSafeParams($params, $type = 1) {
		$value = '';
		//1 判断string, 0判断int
		if ($type == 1) {
			if (is_string($params)) {
				$value = htmlspecialchars(addslashes($params));
			}
		} elseif ($type == 0) {
			if (is_numeric($params)) {
				$value = $params;
			}
		}
		return $value;
	}
	
	/**
	 * 将数据库中的特殊字符数据重新转义
	 * @param $params
	 * @return string
	 */
	public function returnSafeParams($params) {
		return htmlspecialchars_decode(stripcslashes($params));
	}
	
	/**
	 * 获取request过来的数据
	 * @param $param
	 * @param string $type
	 * @return null|string
	 */
	public function getRequest($param, $type = 'request') {
		$value = '';
		$type = strtolower($type);
		if ($type == 'post') {
			$value = (isset($_POST[$param]) && !empty($_POST[$param])) ? trim($_POST[$param]) : null;
		} elseif ($type == 'get') {
			$value = (isset($_GET[$param]) && !empty($_GET[$param])) ? trim($_GET[$param]) : null;
		} elseif ($type == 'request') {
			$value = (isset($_REQUEST[$param]) && !empty($_REQUEST[$param])) ? trim($_REQUEST[$param]) : null;
		}
		return $value;
	}
	
	/**
	 * 判断字符串是否是特殊字符
	 * @param $params
	 * @return bool
	 */
	public function isSpecialParams($params) {
		$res = false;
		$specialChar = ['/', '\\', '\'', '\"', '&', '<', '>', '#'];
		if (is_string($params)) {
			foreach ($specialChar as $value) {
				$res = (mb_strstr($params, $value)) ? true : false;
				if ($res) {
					break;
				}
			}
		} elseif (is_array($params)) {
			foreach ($params as $param) {
				foreach ($specialChar as $value) {
					$res = (mb_strstr($param, $value)) ? true : false;
					if ($res) {
						break;
					}
				}
			}
		}
		return $res;
	}
	
	/**
	 * 验证手机号
	 * @param $phoneNumber
	 * @return int
	 */
	public function regexPhoneNumber($phoneNumber) {
		$pattern = '/^1(3[\d]|4[57]|5[0-35-9]|7[6-8]|8[\d])\d{8}$/';
		$res = preg_match_all($pattern, $phoneNumber);
		return $res;
	}
	
	/**
	 * 判断数据是否存在
	 * @param $table
	 * @param $column
	 * @param $where
	 * @return array|bool
	 */
	public function isExist($table, $column, $where) {
		$dataUtil = new DataAgentUtil();
		$res = $dataUtil->getData($table, $column, $where);
		return $res;
	}
	
	/**
	 * 判断数据的长度
	 * @param $str
	 * @param $minLength
	 * @param $maxLength
	 * @return bool
	 */
	public function judgeLength($str, $minLength, $maxLength) {
		$len = mb_strlen($str, 'UTF8');
		return ($len >= $minLength) ? $len <= $maxLength : false;
	}
	
	/**
	 * 获取随机数
	 * @param int $len
	 * @return null|string
	 */
	public function getRandNum($len = 4) {
		$number = null;
		for ($i = 0; $i < $len; $i++) {
			$number .= rand(0, 9);
		}
		return $number;
	}
	
	/**获取随机字符串
	 * @param int $len
	 * @return null|string
	 */
	public function getRandChar($len = 1) {
		$char = null;
		$arrOne = range('A', 'Z');
		$arrTwo = range('a', 'z');
		$arrLetter = array_merge($arrOne, $arrTwo);
		shuffle($arrLetter);
		$char = mb_substr(implode($arrLetter), 0, $len, 'utf8');
		return $char;
	}
	
	/**获取随机字符在前，随机数字在后的随机字符串
	 * @param $charLen
	 * @param $numLen
	 * @return string
	 */
	public function getRandomStr($charLen, $numLen) {
		return $this->getRandChar($charLen) . $this->getRandNum($numLen);
	}
	
	/**获取字符与数字混合的随机字符串
	 * @param $len
	 * @return string
	 */
	public function getRandomCharMixNum($len) {
		$arrOne = range('A', 'Z');
		$arrTwo = range('a', 'z');
		$arrThree = range(0, 9);
		$arr = array_merge($arrOne, $arrTwo, $arrThree);
		shuffle($arr);
		$char = mb_substr(implode($arr), 0, $len, 'utf8');
		return $char;
	}
	
	/**
	 * 伪造一定格式的url
	 * @param $uid
	 * @param $action
	 * @param $flag
	 * @return string
	 */
	public function getForgeUrl($uid, $action, $flag) {
		$url = '//' . $_SERVER['HTTP_HOST'] . '/' . $action;
		return $url . '?' . $flag . '&detail=' . "{$uid}";
	}
	
	/**
	 * 根据用户ip获取地址
	 * 伪造类似淘宝的api
	 * @return mixed
	 */
	public function getAddressByIP() {
		$ip = $_SERVER['REMOTE_ADDR'];
		$requestURL = "http://ip.taobao.com/service/getIpInfo.php?ip={$ip}";
		$res = json_decode(file_get_contents($requestURL), true);
		return $res;
	}
	
	public function getAddressExPress() {
		$result = null;
		$region = null;
		$city = null;
		$county = null;
		$res = $this->getAddressByIP();
		if (!empty($res) && is_array($res) && !empty($res['data']) && is_array($res['data'])) {
			$region = $res['data']['region'];
			$city = $res['data']['city'];
			$county = $res['data']['county'];
		}
		return $region . $city . $county;
	}
	
	//打印数据
	public function dump($data) {
		echo '<pre>';
		if (is_array($data)) {
			var_dump($data);
		} else {
			echo $data;
		}
		echo '</pre>';
	}
	
	//获取或验证   手机验证码
	public function getOrValidateMobileCode($mobile, $mobileCode = '') {
		$params = ['stage' => '亿旺商户', 'mobile' => $mobile, 'platform' => 'website', 'usage' => 'register'];
		if (empty($mobileCode)) {
			$url = "http://www.ichuk.com/?api/sendsmsverifycode/e75ce5d42105d8e581327164f8e860/1";
		} else {
			$url = "http://www.ichuk.com/?api/checksmsverifycode/e75ce5d42105d8e581327164f8e860/1";
			$params['code'] = $mobileCode;
		}
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		$data = curl_exec($curl);
		curl_close($curl);
		$data = json_decode($data, true);
		return $data;
	}
	
	//获取ip
	public static function getClientIP() {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$client_ip =
				(!empty($_SERVER['REMOTE_ADDR'])) ?
					$_SERVER['REMOTE_ADDR']
					:
					((!empty($_ENV['REMOTE_ADDR'])) ?
						$_ENV['REMOTE_ADDR']
						:
						"unknown");
			
			$entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
			
			reset($entries);
			while (list(, $entry) = each($entries)) {
				$entry = trim($entry);
				if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)) {
					$private_ip = array(
						'/^0\./',
						'/^127\.0\.0\.1/',
						'/^192\.168\..*/',
						'/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
						'/^10\..*/');
					
					$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
					
					if ($client_ip != $found_ip) {
						$client_ip = $found_ip;
						break;
					}
				}
			}
		} else {
			$client_ip =
				(!empty($_SERVER['REMOTE_ADDR'])) ?
					$_SERVER['REMOTE_ADDR']
					:
					((!empty($_ENV['REMOTE_ADDR'])) ?
						$_ENV['REMOTE_ADDR']
						:
						"unknown");
		}
		return $client_ip;
	}
	
	//获取产品运费
	public static function getProductFreight($cityid,$pid,$num=1){
		$ret=array();
	    $expressp=-1;
	    $emsp=-1;
	    $mailp=-1;
	    FileUtil::requireService("FreightServ");
	    FileUtil::requireService("GoodsServ");
	    $service=new FreightServ();
	    $gservice=new GoodsServ();
	    $product=$gservice->getProductDetail($pid);
	    $freightid=$product['fre_id'];
	    if($freightid==0){
		    $ret['ret']=2;
		    $ret['mode']="卖家承担运费";
		    $ret['money']=0; 
		    return $ret;
	    }
		$freight=$service->getFreightDetail($freightid);
	    if($freight===false){
		   	$ret['ret']=0;
		    $ret['data']="服务器错误,请稍后再试";
		    return $ret;
	    }
	    if($freight['type']==1){
		    $ret['ret']=2;
		    $ret['mode']="卖家承担运费";
		    $ret['money']=0; 
		    return $ret;
	    }
	    $kg=$num*$product['weight'];
		$m3=$num*$product['volume'];

		//判断计价方式   1按件数，2按重量(kg)，3按体积(m³)
		if($freight['express_flag']==1){
			$express=json_decode($freight['express'],true);
			foreach($express['options'] as $option){
				$citylist=explode(',', $option['cities']);
				for($a=0;$a<count($citylist);$a++){
					if($cityid==$citylist[$a]){
						if($freight['valuation_unit']==1){
							if($num<=$option['val1']){
								$expressp=$option['val2'];
							}else {
								$left=$num-$option['val1'];
								$money=ceil($left/$option['val3'])*$option['val4'];
								$expressp=$option['val2']+$money;
							}
						}else if($freight['valuation_unit']==2){
							if($kg<=$option['val1']){
								$expressp=$option['val2'];
							}else{
								$left=$kg-$option['val1'];
								$money=ceil($left/$option['val3'])*$option['val4'];
								$expressp=$option['val2']+$money;
							}
						}else if($freight['valuation_unit']==3){
							if($m3<=$option['val1']){
								$expressp=$option['val2'];
							}else{
								$left=$m3-$option['val1'];
								$money=ceil($left/$option['val3'])*$option['val4'];
								$expressp=$option['val2']+$money;
							}
						}
					}
				}
			}
			if($expressp==-1){
				if($freight['area_limit_flag']==0){
					if($freight['valuation_unit']==1){
						if($num<=$express['defaultVals']['val1']){
							$expressp=$express['defaultVals']['val2'];
						}else {
							$left=$num-$express['defaultVals']['val1'];
							$money=ceil($left/$express['defaultVals']['val3'])*$express['defaultVals']['val4'];
							$expressp=$express['defaultVals']['val2']+$money;
						}
					}else if($freight['valuation_unit']==2){
						if($kg<=$express['defaultVals']['val1']){
							$expressp=$express['defaultVals']['val2'];
						}else{
							$left=$kg-$express['defaultVals']['val1'];
							$money=ceil($left/$express['defaultVals']['val3'])*$express['defaultVals']['val4'];
							$expressp=$express['defaultVals']['val2']+$money;
						}
					}else if($freight['valuation_unit']==3){
						if($m3<=$express['defaultVals']['val1']){
							$expressp=$express['defaultVals']['val2'];
						}else{
							$left=$m3-$express['defaultVals']['val1'];
							$money=ceil($left/$express['defaultVals']['val3'])*$express['defaultVals']['val4'];
							$expressp=$express['defaultVals']['val2']+$money;
						}
					}
				}
			}
		} 
		if($freight['ems_flag']==1){
			$ems=json_decode($freight['ems'],true);
			foreach($ems['options'] as $option1){
				$citylist=explode(',', $option1['cities']);
				for($a=0;$a<count($citylist);$a++){
					if($cityid==$citylist[$a]){
						if($freight['valuation_unit']==1){
							if($num<=$option1['val1']){
								$emsp=$option1['val2'];
							}else {
								$left=$num-$option1['val1'];
								$money=ceil($left/$option1['val3'])*$option1['val4'];
								$emsp=$option1['val2']+$money;
							}
						}else if($freight['valuation_unit']==2){
							if($kg<=$option1['val1']){
								$emsp=$option1['val2'];
							}else{
								$left=$kg-$option1['val1'];
								$money=ceil($left/$option1['val3'])*$option1['val4'];
								$emsp=$option1['val2']+$money;
							}
						}else if($freight['valuation_unit']==3){
							if($m3<=$option1['val1']){
								$ems=$option1['val2'];
							}else{
								$left=$m3-$option1['val1'];
								$money=ceil($left/$option1['val3'])*$option1['val4'];
								$emsp=$option1['val2']+$money;
							}
						}
					}
				}
			}
			if($emsp==-1){
				if($freight['area_limit_flag']==0){
					if($freight['valuation_unit']==1){
						if($num<=$ems['defaultVals']['val1']){
							$emsp=$ems['defaultVals']['val2'];
						}else {
							$left=$num-$ems['defaultVals']['val1'];
							$money=ceil($left/$ems['defaultVals']['val3'])*$ems['defaultVals']['val4'];
							$emsp=$ems['defaultVals']['val2']+$money;
						}
					}else if($freight['valuation_unit']==2){
						if($kg<=$ems['defaultVals']['val1']){
							$emsp=$ems['defaultVals']['val2'];
						}else{
							$left=$kg-$ems['defaultVals']['val1'];
							$money=ceil($left/$ems['defaultVals']['val3'])*$ems['defaultVals']['val4'];
							$emsp=$ems['defaultVals']['val2']+$money;
						}
					}else if($freight['valuation_unit']==3){
						if($m3<=$ems['defaultVals']['val1']){
							$emsp=$ems['defaultVals']['val2'];
						}else{
							$left=$m3-$ems['defaultVals']['val1'];
							$money=ceil($left/$ems['defaultVals']['val3'])*$ems['defaultVals']['val4'];
							$emsp=$ems['defaultVals']['val2']+$money;
						}
					}
				}
			}
			
		}
		if($freight['mail_flag']==1){
			$mail=json_decode($freight['mail'],true);
			foreach($mail['options'] as $option2){
				$citylist=explode(',', $option2['cities']);
				for($a=0;$a<count($citylist);$a++){
					if($cityid==$citylist[$a]){
						if($freight['valuation_unit']==1){
							if($num<=$option2['val1']){
								$mailp=$option2['val2'];
							}else {
								$left=$num-$option2['val1'];
								$money=ceil($left/$option2['val3'])*$option2['val4'];
								$mailp=$option2['val2']+$money;
							}
						}else if($freight['valuation_unit']==2){
							if($kg<=$option2['val1']){
								$mailp=$option2['val2'];
							}else{
								$left=$kg-$option2['val1'];
								$money=ceil($left/$option2['val3'])*$option2['val4'];
								$mailp=$option2['val2']+$money;
							}
						}else if($freight['valuation_unit']==3){
							if($m3<=$option2['val1']){
								$mailp=$option2['val2'];
							}else{
								$left=$m3-$option2['val1'];
								$money=ceil($left/$option2['val3'])*$option2['val4'];
								$mailp=$option2['val2']+$money;
							}
						}
					}
				}
			}
			if($mailp==-1){
				if($freight['area_limit_flag']==0){
					if($freight['valuation_unit']==1){
						if($num<=$mail['defaultVals']['val1']){
							$mailp=$mail['defaultVals']['val2'];
						}else {
							$left=$num-$mail['defaultVals']['val1'];
							$money=ceil($left/$mail['defaultVals']['val3'])*$mail['defaultVals']['val4'];
							$mailp=$mail['defaultVals']['val2']+$money;
						}
					}else if($freight['valuation_unit']==2){
						if($kg<=$mail['defaultVals']['val1']){
							$mailp=$mail['defaultVals']['val2'];
						}else{
							$left=$kg-$mail['defaultVals']['val1'];
							$money=ceil($left/$mail['defaultVals']['val3'])*$mail['defaultVals']['val4'];
							$mailp=$mail['defaultVals']['val2']+$money;
						}
					}else if($mail['valuation_unit']==3){
						if($m3<=$mail['defaultVals']['val1']){
							$mailp=$mail['defaultVals']['val2'];
						}else{
							$left=$m3-$mail['defaultVals']['val1'];
							$money=ceil($left/$mail['defaultVals']['val3'])*$mail['defaultVals']['val4'];
							$mailp=$mail['defaultVals']['val2']+$money;
						}
					}
				}
			}
		}
		$ret['ret']=1;
		$ret['expressp']=$expressp;
		$ret['emsp']=$emsp;
		$ret['mailp']=$mailp;
		return $ret;	    
	}
	
	//获取手机验证码
	public function getMobileValidate($mobile) {
		$isValidateCodeSuccess = ['ret' => 0, 'msg' => '验证码发送失败'];
		$result = $this->getOrValidateMobileCode($mobile);
		$ret = isset($result['ret']) ? $result['ret'] : 0;
		if (1 == $ret) {
			$isValidateCodeSuccess['ret'] = 1;
			$isValidateCodeSuccess['msg'] = '验证码已发送至你的手机，请查收';
		}
		return $isValidateCodeSuccess;
	}
	
	//检查手机验证码
	//成功返回true;
	public function checkMobileValidate($mobile, $mobileCode) {
		$isValidateSuccess = false;
		$result = $this->getOrValidateMobileCode($mobile, $mobileCode);
		$ret = isset($result['ret']) ? $result['ret'] : 0;
		if (1 == $ret) {
			$isValidateSuccess = true;
		}
		return $isValidateSuccess;
	}
}
