<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditArticleType extends BaseAction{

	public function action(){
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        FileUtil::requireService('ArticleServ');
        $serv = new ArticleServ();
       /* $type = $serv->getArticleTypeById($id);
        $params = array();
        $params['style'] = 'media';
		$params['substyle'] = 'articles';
        $params['type'] = $type;*/
        $params['id'] = $id;
		return $params;
	}

}