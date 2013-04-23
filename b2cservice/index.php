<?php
define('IN_ET', TRUE);
require('./define.inc.php');

define('ET_ROOT', dirname(__FILE__));
define('MODE_NAME','mycore');
define('THINK_PATH',dirname(__FILE__).'/../../ThinkPHP/');
define('APP_NAME', 'b2cservice');
define('APP_PATH', './B2CService/');
define('DEFAULT_TYPE','default');
define('__PUBLIC__',ET_URL."Public");
define('SITE_URL',ET_URL);
define('APP_DEBUG', true);
//常用定义
define('ROOT_URL','http://'.$_SERVER['HTTP_HOST'].'/');
define('SITE_PUBLIC',ROOT_URL.'demand/');

require(APP_PATH.'Common/Function.php');
require(APP_PATH.'Common/B2CFunction.php');

require(THINK_PATH.'ThinkPHP.php');

?>