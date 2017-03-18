<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class FitmentMore extends BaseAction
{

    public function action()
    {

        $params = array();
        $params['style'] = 'fitment';
        return $params;

    }
}
