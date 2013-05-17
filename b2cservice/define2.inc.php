<?php
	require(dirname(__FILE__).'/define.inc.php');
	//全局
    define('ROOT_URL','http://www.'.SITE_ROOT_NAME.'.com/');
//    define('SERVER_URL','http://www.'.SERVER_ROOT_NAME.'.com/');
    define('B2CSERVICE_URL',ROOT_URL.'b2cservice/');
    define('ORDER_URL','http://order.'.SITE_ROOT_NAME.'.com/');
    define('MY_URL','http://my.'.SITE_ROOT_NAME.'.com/');
    define('BBS_URL','http://bbs.'.SITE_ROOT_NAME.'.com/');
	//常用定义
    define('SERVER_INDEX',SERVER_URL.'index.php?s=/');
    define('B2CSERVICE_INDEX',B2CSERVICE_URL.'index.php?s=/');
    define('ORDER_INDEX',ORDER_URL.'index.php?s=/');
    define('MY_INDEX',MY_URL.'index.php?s=/');
    define('SITE_DATA',B2CSERVICE_URL.'Data/');
	define('SITE_PUBLIC',ROOT_URL.'demand/');
	define('__PUBLIC__',B2CSERVICE_URL."Public/");
	define('B2CSERVICE_PUBLIC',B2CSERVICE_URL."Public/");
	//配置
	define('MODE_NAME','mycore');
	define('THINK_PATH',dirname(__FILE__).'/../../ThinkPHP/');
	define('APP_NAME', 'b2cservice');
	define('APP_PATH', dirname(__FILE__).'/B2CService/');
	define('B2CSERVICE_PATH', dirname(__FILE__));
	//define('DEFAULT_TYPE','default');
	define('APP_DEBUG', true);
	require(APP_PATH.'Common/Function.php');
	require(APP_PATH.'Common/B2CFunction.php');
	//UC配置
	define('DATABASE_PREFIX', 'glly_');
	define('UC_API', 1);
	define('UC_ROOT', ROOT_URL.'uc_client');
	define('IN_UC', TRUE);
	define('UC_CLIENT_VERSION', '1.5.0');
	define('UC_CLIENT_RELEASE', '20090121');
	define('UC_DATADIR', UC_ROOT.'./data/');
	define('UC_DATAURL', UC_API.'/data');
	define('UC_CONNECT', 'mysql');
	define('UC_API_FUNC', UC_CONNECT == 'mysql' ? 'uc_api_mysql' : 'uc_api_post');
	
	
?>