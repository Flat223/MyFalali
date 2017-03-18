<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/module/_baseClass/BaseAction.php';



class SignServ extends BaseAction{
	
	public function action(){
        $user = UserAgent::getUser();
        $ret = array();
        if($user == null || $user == false){
            $ret['ret'] = -1;
            $ret['msg'] = "尚未登录，登陆后重试";
            $ret['user'] = $user;
            return $ret;
        }
        FileUtil::requireService('UserSignServ');
        $signServ = new UserSignServ();
        $signRecord = $signServ->getSignRecordById($user['mid']);
        if($signRecord === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
        if(empty($signRecord)){
            $callback = $signServ->insertSignRecord($user['mid']);
            if($callback === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
				return $ret;
			}
			
			$callback=$this->addIntegrateRecord();
            if($callback === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误7，请稍后再试";
				return $ret;
			}
        }else if(date('Y-m-d') == date('Y-m-d',$signRecord['sign_time'])){
            $ret['ret'] = -2;
            $ret['msg'] = "你今天已经签过啦，已连续签到".$signRecord['sign_count']."天";
            $ret['user'] = $user;
            return $ret;
        }else if(date('Y-m-d') != date('Y-m-d',$signRecord['sign_time'])){
            /*是否连续签到*/
            if(date('Y-m-d',$signRecord['sign_time']+86400) != date('Y-m-d')){
                $callback = $signServ->updateRecord($signRecord['id'],0,$signRecord['m_id']);
                if($callback === false){
					$ret['ret'] = 0;
					$ret['msg'] = "抱歉，服务器错误4，请稍后再试";
					return $ret;
				}
            }else{
                $callback=$signServ->updateRecord($signRecord['id'],$signRecord['sign_count'],$signRecord['m_id']);
                if($callback === false){
					$ret['ret'] = 0;
					$ret['msg'] = "抱歉，服务器错误5，请稍后再试";
					return $ret;
				}
            }
            $callback=$this->addIntegrateRecord();
            if($callback === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误7，请稍后再试";
				return $ret;
			}
        }
        
        $signRecord = $signServ->getSignRecordById($user['mid']);
        if($signRecord === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误6，请稍后再试";
			return $ret;
		}
        $count = $signRecord['sign_count'];
        $ret['ret'] = 1;
        $ret['msg'] = "恭喜你签到成功，已连续签到".$count."天";
        $ret['sign_count'] = $count;
        $ret['user'] = $user;
		return $ret;
	}
	
	function addIntegrateRecord(){
		$point = 5;
		$user = UserAgent::getUser();
		FileUtil::requireService('UserServ');
        $service = new UserServ();
		$callback = $service->updatepoint($user['mid'],$point);
		if($callback === false){
			return false;
		}
		$user2 = $service->getMemberByMid($user['mid']);
		if($user2 === false){
			return false;
		}
		$_SESSION['user'] = $user2;
		
		FileUtil::requireService('TransactionServ');
        $service = new TransactionServ();
        
        $record['type'] = 3;
        $record['party_a'] = 0;
        $record['party_b'] = $user2['mid'];
        $record['pay_type'] = 1;
        $record['currency'] = 2;
        $record['number'] = $point;
        $record['remarks'] = '签到';
        
		return $service->addTransactionRecord($record);
	}
}