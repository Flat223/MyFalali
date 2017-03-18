<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddArticlebyidServ extends BaseAction{
    public function action(){
        $categoryid=isset($_POST['categoryid'])?$_POST['categoryid']:"";
        $page=isset($_POST['page'])?$_POST['page']:"";
        FileUtil::requireService("ArticleServ");
        $Article=new ArticleServ();
        /*$ret=$Article->GetAriticleBynum(11,$page+1,$categoryid,'1',1);*/
        $ret = $Article->getArticleByCategoryId(15,$page+1,$categoryid);
        $member = array();
        foreach ($ret as $val){
            $member[] = $Article->getMemberByMid($val['mid']);
        }
        /*$cate = array();
        foreach ($ret as $val){
            $cate[] = $Article->getCategoryByCid($val['categoryId']);
        }*/

        $html="";
        $i = 0;
        foreach($ret as $k=>$v){
            $url='../info/article.html?id='.$v['id'];
            $vl=$v['id'];
            $img=empty($v['images'])?'/images/temp_pc/0.jpg':$v['images'];
            $html.="<li class='clearfix'>";
            $html.="<dl>";
            $html.="<dt>";
            $html.="<a href=$url ><img src=$img />";
            $html.="</a>";
            $html.="</dt>";
            $html.="<dd>";
            $html.="<h2 class='jumpartice' urlid=$vl>".$v['title']."</h2>";
            $html.="<span class='desc'>".$v['intro']."</span>";
            $html.="<p class='extra'>";
            if(empty($v['video_url'])){
                $html.="<span>文</span>";
            }else{
                $html.="<span>视频</span>";
            }
            $html.="<span>&nbsp;/&nbsp; </span>";
            if(empty($member[$i][0]['nickname'])){
                $html.="<b>实验圈</b>";
            }else{
                $html.="<b>".$member[$i][0]['nickname']."</b>";
            }
            $html.="<span>·".date('Y-m-d H:i:s',$v['time'])."</span>";
            $html.="<span>".$v['cname']."</span>";
            $html.="</p></dd></dl></li>";
            $i++;
        }


        exit(json_encode($html));
    }
}