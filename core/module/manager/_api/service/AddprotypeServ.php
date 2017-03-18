<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/module/_baseClass/BaseAction.php';

class AddprotypeServ extends BaseAction
{

    public function action()
    {
        $admin = UserAgent::getAdmin();
        if (empty($admin)) {
            $ret['ret'] = 0;
            $ret['msg'] = "请先登录";
            return $ret;
        }

        $ptid = isset($_REQUEST['ptid']) ? $_REQUEST['ptid'] : "";
        $propertyNames = isset($_REQUEST['propertyNames']) ? $_REQUEST['propertyNames'] : "";
        if (empty($ptid) || empty($propertyNames)) {
            $ret['ret'] = 0;
            $ret['msg'] = "缺少参数";
            return $ret;
        }

        FileUtil::requireService('PropertyServ');
        $service = new PropertyServ();
        $proType = $service->getProTypeByPtid($ptid);
        if ($proType === false) {
            $ret['ret'] = 0;
            $ret['msg'] = "抱歉，服务器错误0，请稍后再试";
            return $ret;
        }
        if (empty($proType)) {
            $ret['ret'] = 0;
            $ret['msg'] = "未找到该产品分类";
            return $ret;
        }

        $proNameArr = explode(",", $propertyNames);
        foreach ($proNameArr as $name) {
            $property = $service->getPropertyByNames($ptid, $name);
            if ($property === false) {
                $ret['ret'] = 0;
                $ret['msg'] = "抱歉，服务器错误2，请稍后再试";
                return $ret;
            }
            if (!empty($property)) {
                $ret['ret'] = 0;
                $ret['msg'] = '属性名称"' . $name . '"已存在';
                return $ret;
            }
        }

        foreach ($proNameArr as $name) {
            $callback = $service->Addprotype($ptid, $name);
            if ($callback === false) {
                $ret['ret'] = 0;
                $ret['msg'] = "抱歉，服务器错误3，请稍后再试";
                return $ret;
            }
        }

        $ret['ret'] = 1;
        $ret['msg'] = "属性添加成功";
        return $ret;
    }
}