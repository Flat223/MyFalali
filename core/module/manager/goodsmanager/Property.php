<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Property extends BaseAction{

    public function action(){
        FileUtil::requireService('PropertyServ');
        $service = new PropertyServ();
        
/*
        $count = $service->getprocount();
        if($count === false){
			FileUtil::load404Html();
			exit(0);
		}
*/
        
        $firstLevelArray = $service->getPropertyByLevl(1);
        if($firstLevelArray === false){
			FileUtil::load404Html();
			exit(0);
		}
        
        $secondLevelArray = $service->getPropertyByLevl(2);
        if($secondLevelArray === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$thirdLevelArray = $service->getPropertyByLevl(3);
        if($thirdLevelArray === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		foreach ($secondLevelArray as &$secondLevel){
			$newLevelArray = array();
			foreach ($thirdLevelArray as $thirdLevel){
				if($thirdLevel['parentid'] == $secondLevel['ptid']){
					$newLevelArray[] = $thirdLevel;
				}
			}
			$secondLevel['sub_level'] = $newLevelArray;
		}
		        
        $params['style'] = 'goodsmanager';
		$params['substyle'] = 'property';
// 		$params['count'] = $count;
        $params['first'] = $firstLevelArray;
        $params['second'] = $secondLevelArray;
        return $params;
    }

}