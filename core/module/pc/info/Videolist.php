<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class videolist extends BaseAction
{
    public function action()
    {
        $id=isset($_GET['id'])?$_GET['id']:"";
        $page=isset($_GET['page'])?$_GET['page']:1;
        FileUtil::requireService("VideoServ");
        $video=new VideoServ();
        $params['videoinfo']=$video->GetvideoinfoBy($id);
        if($params['videoinfo'] == null || $params['videoinfo'] == false){
            FileUtil::load404Html();
            exit(0);

        }
        $params['count']=$video->GetVideoCountbycate($id);
        $video->Addviewnum($id);
        $num=12;
        $params['videolist']=$video->GetvideoByid($id,$num,$page);
        FileUtil::requireService('PageUtil');
        $pageUtil = new PageUtil($num,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/info/videolist.html?id='.$id;
        $params['pager'] = $pageUtil;
        return $params;
    }
}