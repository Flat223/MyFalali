<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Search extends BaseAction
{

    public function action()
    {
        $info=isset($_REQUEST['info'])?trim($_REQUEST['info']):"";
        $page = isset($_REQUEST['page'])?trim($_REQUEST['page']):1;
        FileUtil::requireService('LabListServ');
        FileUtil::requireService('PageUtil');

        FileUtil::requireService('ShareServ');
        $shareServ = new ShareServ();
        $user = UserAgent::getUser();
        $cl = array();
        if(!empty($user)){
            $colab = $shareServ->getCollectedLab($user['mid']);
            for($i = 0;$i<count($colab);$i++){
                $cl[] = $colab[$i]['aid'];
            }
        }
        $labListServ = new LabListServ();
        $pagesize=6;
        $params['cl'] = $cl;
        $params['info']=$labListServ->getLabByinfo($info,$page,$pagesize);
        $pageUtil = new PageUtil($pagesize,$params['info']['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/lab/search.html?type=lab&info='.$info;
        $params['pager'] = $pageUtil;
        return $params;
    }
}
?>