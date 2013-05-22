<?php
	require(dirname(__FILE__).'/define2.inc.php');
	//配置
	define('MODE_NAME','mycore');
	define('THINK_PATH',dirname(__FILE__).'/../../ThinkPHP/');
	define('APP_NAME', 'b2cservice');
	//define('DEFAULT_TYPE','default');
	define('APP_DEBUG', true);
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