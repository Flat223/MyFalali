<?php
//设置服务常量
define("DBPrefix", "labring");
define("DBName", "labring");
define("DBAddress","115.29.224.199");
define("DBUser","root");
define("DBPassword","labring2016");
define("Language", 'zh');
define("Theme", 'pc');
define("Debug", '1');
define("API_DIR", "service|api|do");

include_once(dirname(__FILE__)."/language/".Language.".php");

$siteconfig = array(
                   "powererby"=>"iChuk™ Operating System Ltd,.",
                   "author"=>"jackbenz",
                   "version"=>"0.9.1",
                   "sitename"=>$language['sitename'],
                   "copyright"=>$language['copyright'],
                   "beian"=>$language['beian'],
                   "keywords"=>$language['keywords'],
                   "description"=>$language['description']
               );

?>