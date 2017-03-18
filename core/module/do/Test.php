<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Test extends BaseAction{
	private $aaaa;
	
	public function action(){
/*
		$this->aaaa = 'hello';
		$ret = array();
		$data =  isset($_REQUEST['data'])?$_REQUEST['data']:null;
		$data = json_decode($data,true);
		if($data == null){
			$ret['ret'] = 0;
			$ret['msg'] = "有错误";
			return $ret;
		}
		foreach($data as $value1){
			$first = array();
			$first['name'] = $value1['name'];
			$first['parentid'] = 0;
			$first['level'] = 1;
			if(!$this->addData($first)){
				$ret['ret'] = 0;
				$ret['msg'] = "有错误";
				return $ret;
			}
			$firstInsert = $this->getDataByName($first['name']);
			if(!$firstInsert){
				$ret['ret'] = 0;
				$ret['msg'] = "有错误";
				return $ret;
			}
			if(isset($value1['subTypes']) && is_array($value1['subTypes'])){
				foreach($value1['subTypes'] as $value2){
					$second = array();
					$second['name'] = $value2['name'];
					$second['parentid'] = $firstInsert['ptid'];
					$second['level'] = 2;
					if(!$this->addData($second)){
						$ret['ret'] = 0;
						$ret['msg'] = "有错误";
						return $ret;
					}
					$secondInsert = $this->getDataByName($second['name']);
					if(!$secondInsert){
						$ret['ret'] = 0;
						$ret['msg'] = "有错误";
						return $ret;
					}
					if(isset($value2['subTypes']) && is_array($value2['subTypes'])){
						foreach($value2['subTypes'] as $value3){
							$third = array();
							$third['name'] = $value3['name'];
							$third['parentid'] = $secondInsert['ptid'];
							$third['level'] = 3;
							if(!$this->addData($third)){
								$ret['ret'] = 0;
								$ret['msg'] = "有错误";
								return $ret;
							}
						}
					}	
				}
			}
		}
		$ret['ret'] = 1;
		$ret['msg'] = "成功";
		return $ret;
*/
		FileUtil::requireService('SampleServ');
		$service = new SampleServ();
		$id = 2;
		$data = $service->getProductTypes($id);

		$params = array();
		$params['hello'] = '孙宁';
		$params['data'] = $data;
		
		return $params;
	}
	
	
	
	private function addData($data){
		$sql = "insert into #__product_type(name,parentid,level,status)values(?,?,?,1) ";
		$dbAgent = DBAgent::getInstance();
		$params = array($data['name'],$data['parentid'],$data['level']);
		return $dbAgent->query($sql,$params);
	}
	
	private function getDataByName($name){
		$sql = "select * from #__product_type where name = ? order by ptid desc limit 1 ";
		$dbAgent = DBAgent::getInstance();
		$params = array($name);
		return $dbAgent->querySingleRecord($sql,$params);
	}
	
	
	
	
	
}