<?php
	require(dirname(__FILE__).'/define.inc.php');
	//全局
    define('ROOT_URL','http://www.'.SITE_ROOT_NAME.'.com/');
    define('SERVER_URL','http://www.'.SERVER_ROOT_NAME.'.com/');
    define('B2CSERVICE_URL',ROOT_URL.'b2cservice/');
    define('ORDER_URL','http://order.'.SITE_ROOT_NAME.'.com/');
    define('MY_URL','http://my.'.SITE_ROOT_NAME.'.com/');
    define('BBS_URL','http://bbs.'.SITE_ROOT_NAME.'.com/');
	//常用定义
    define('SERVER_INDEX',SERVER_URL.'index.php?s=/');
    define('SITE_DATA',B2CSERVICE_URL.'Data/');
	define('SITE_PUBLIC',ROOT_URL.'demand/');
	define('__PUBLIC__',B2CSERVICE_URL."Public");
	//配置
	define('MODE_NAME','mycore');
	define('THINK_PATH',dirname(__FILE__).'/../../ThinkPHP/');
	define('APP_NAME', 'b2cservice');
	define('APP_PATH', dirname(__FILE__).'/B2CService/');
	//define('DEFAULT_TYPE','default');
	define('APP_DEBUG', true);
	require(APP_PATH.'Common/Function.php');
	require(APP_PATH.'Common/B2CFunction.php');
	
	
	
?>