<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetInvoiceListServ extends BaseAction
{

    public function action()
    {
        $user = UserAgent::getUser();
        $type = isset($_REQUEST['type']) ? trim($_REQUEST['type']) : "";

        FileUtil::requireService("InvoiceServ");
        $service = new InvoiceServ();
        $invoiceArray = $service->getUserInvoiceList($user['mid'], $type);
        if ($invoiceArray === false) {
            $ret['ret'] = 0;
            $ret['msg'] = "抱歉，服务器错误，请稍后再试";
            return $ret;
        }
        if ($invoiceArray == null) {
            if ($type == 1) {
                $title = "普通发票";
            } else if ($type == 2) {
                $title = "电子发票";
            } else {
                $title = "增值税发票";
            }
            $ret['ret'] = 0;
            $ret['msg'] = "暂无" . $title;
            return $ret;
        }

        $ret['ret'] = 1;
        $ret['msg'] = "获取成功";
        $ret['invoice'] = $invoiceArray;
        return $ret;
    }
}
?>