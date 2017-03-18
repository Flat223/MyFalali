<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddvideoServ extends BaseAction{
    public function action()
    {
        $category = isset($_POST['category']) ? $_POST['category'] : "";
        $title = isset($_POST['title']) ? $_POST['title'] : "";
        $intro = isset($_POST['intro']) ? $_POST['intro'] : "";
        $content = isset($_POST['content']) ? $_POST['content'] : "";
        $url = isset($_POST['url']) ? $_POST['url'] : "";
        $images = isset($_POST['images']) ? $_POST['images'] : "";
        $time = time();
        $dbAgent = DBAgent::getInstance();
        $table = 'article';
        $insertColumns = array('type', 'categoryId', 'title', 'intro', 'content', 'time', 'status', 'country', 'images');
        $insertVals = array('3', $category, $title, $intro, $content, $time, 1, $images, $url);
        if ($dbAgent->insertRecord($table, $insertColumns, $insertVals, $hasPrefix = true)) {
            $result['ret'] = '1';
            $result['msg'] = '成功';
            exit(json_encode($result));
        } else {
            $result['ret'] = '0';
            $result['msg'] = '失败';
            exit(json_encode($result));
        }
    }
}
        
?>