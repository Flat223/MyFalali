<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddCompanyMember extends BaseAction{

	public function action(){
		$params['style'] = 'user';
		$params['substyle'] = 'AddCompanyMember';
		return $params;
	}
}