<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/module/_baseClass/BaseAction.php';

class InterestingCircle extends BaseAction
{

    public function action()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        FileUtil::requireService("InterestServ");
        $interest = new InterestServ();
        $user = UserAgent::getUser();
        $type = 1;

        FileUtil::requireService("CircleServ");
        $service = new CircleServ();
        $followfcircle = $service->getUserCircleList($user['mid'], 0, 12);
        $key = isset($_REQUEST['key']) ? trim($_REQUEST['key']) : '';
        $circleArray = array();
        if ($key != "") {
            $type = 2;
            $circleArray = $service->searchCircleByKey($key);
        }

        FileUtil::requireService("FriendsServ");
        $service = new FriendsServ();
        $friendMidArray = $service->getUserfriendsMid($user['mid']);
        $myFriends = "";
        foreach ($friendMidArray as $friend) {
            if ($myFriends == "") {
                $myFriends = $friend['mid'];
            } else {
                $myFriends .= "," . $friend['mid'];
            }
        }
        $friendArray = $service->getRecommendfriends($user['mid'], $myFriends, 8);

        $params['type'] = $type;
        $params['search_circle'] = $circleArray;
        $params['user'] = $user;
        $params['recom_friend'] = $friendArray;
        $params['followcircle'] = $followfcircle;
        $params['label'] = $interest->GetAllLabel();
        $num = 20;
//        $params['count']=$interest->GetHotCount($user['mid']);
        $params['count'] = $interest->GetAllTopicCount();
        FileUtil::requireService('PageUtil');
        $count = $interest->getCircleComment($user['mid']);
        $pageUtil = new PageUtil($num,$count,$page);
        $params['baseurl'] = 'http://' . $_SERVER['HTTP_HOST'] . '/user/interestingCircle.html?';
        $params['pager'] = $pageUtil;

        if (!$user) {
//            $params['hot']=$interest->GetFiveHotByUser($user['mid'],$num,$page);
            $hot = $interest->GetuserTopicB($num, $page);
            $params['recommend'] = $interest->GetbyTime(5);
            return $params;
        } else {
            $index = ($pageUtil->getCurrentPage()-1)*$num;
            $hot = $interest->getPageCircleComment($user['mid'],$index,$num);

            if ($params['user']['interest_labels'] != '') {
                $inter = explode(',', $params['user']['interest_labels']);
            } else {
                $inter = array();
            }
            if ($params['user']['industry_ids'] != '') {
                $indus = explode(',', $params['user']['industry_ids']);
            } else {
                $indus = array();
            }
            $recommend = array();
            for ($i = 0; $i < count($inter); $i++) {
                $ret = $interest->GetInterestLabels($inter[$i], $params['user']['mid']);
                $recommend = array_merge($recommend, $ret);
            }
            if (count($recommend) < 5) {
                for ($i = 0; $i < count($indus); $i++) {
                    $res = $interest->GetIndustryIds($indus[$i], $params['user']['mid']);
                    $recommend = array_merge($recommend, $res);
                }
            }
            if (count($recommend) < 5) {
                $reu = $interest->GetById($params['user']['mid']);
                $recommend = array_merge($recommend, $reu);
            }
            if (count($recommend) < 5) {
                $rev = $interest->GetBynotinterest($params['user']['mid']);
                $recommend = array_merge($recommend, $rev);
            }
            $rec = array();
            $rect = array();
            foreach ($recommend as $k => $v) {
                $rec[$k] = implode($v, '()()');
            }
            $rec = array_unique($rec);
            foreach ($rec as $k => $v) {
                array_push($rect, $recommend[$k]);
            }
            $params['recommend'] = $rect;
            foreach ($hot as $k => $v) {
                $hot[$k]['pic'] = $this->GetStringImg($v['content']);
            }
            $params['hot'] = $hot;
            return $params;
        }
    }

    function GetStringImg($string)
    {
        $preg = "/<img.*?src=[\'\"](.+?)[\'\"].*?>/i";
        preg_match_all($preg, $string, $match);
        $imgurl = $match[1];
        return $imgurl;
    }

}