<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabInfo extends BaseAction{

	public function action(){
        $id = isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
        FileUtil::requireService('LabDetailServ');
        $infoServ = new LabDetailServ();
        $info = $infoServ->getLabDetail($id);

        if($info == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        $member = $infoServ->getMemberBymid($info['mid']);
        if($member == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }

        $tname = $infoServ->getTypeNameBytid($info['type_id']);


        $ptype = $infoServ->getTypeNameBytid($tname['parentid']);

        $org = $infoServ->getBelongInstitude($info['institude_id']);
       /* $org = array();
        $org = $infoServ->getAllInstitude();
        if($org == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }*/
        $params = array();
        $params['substyle'] = 'labListing';
        $params['info'] = $info;
        $params['member'] = $member;
        $params['tname'] = $tname;
        $params['org'] = $org;
        $params['ptype'] = $ptype;
		return $params;
	}

}