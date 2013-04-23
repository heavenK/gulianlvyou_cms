<?php
define("DE_ItemEcode",'Shop_De_');//识别购物车Cookie前缀,非开发人员请不要随意更改!
require_once(dirname(__FILE__).'/../../include/common.inc.php');
if($_REQUEST['type'] == 'cfgCookieEncode')
echo $cfg_cookie_encode;
if($_REQUEST['type'] == 'DE_ItemEcode')
echo DE_ItemEcode;
?>


















