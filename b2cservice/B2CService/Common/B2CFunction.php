<?php

function FileGetContents($url){
	$d = file_get_contents($url);
	$d = str_replace('﻿','',$d);//未知原因数据序列化后多3个不可见字符问题，序列化失败解决办法。
	$d = unserialize($d);
	return $d;
}


function GetDEDEInfo($type){
	$loginsta = FileGetContents(B2CSERVICE_URL."apis/info_collector.php?type=".$type);
	return trim($loginsta);
}


function GetCookie($key)
{
	$cfg_cookie_encode = GetDEDEInfo('cfg_cookie_encode');
	if( !isset($_COOKIE[$key]) || !isset($_COOKIE[$key.'__ckMd5']) )
	{
		return '';
	}
	else
	{
		if($_COOKIE[$key.'__ckMd5']!=substr(md5($cfg_cookie_encode.$_COOKIE[$key]),0,16))
		{
			return '';
		}
		else
		{
			return $_COOKIE[$key];
		}
	}
}

	/**
	 *  获取整数值
	 *
	 * @access    public
	 * @param     string  $fnum  处理的数值
	 * @return    string
	 */
	function GetNum($fnum){
		$fnum = preg_replace("/[^0-9\.]/", '', $fnum);
		return $fnum;
	}


    /**
     *  添加一个商品编号及信息
     *
     * @param     string  $id  购物车ID
     * @param     string  $value  值
     * @return    void
     */
    function DEDEShopCar_addItem($id, $value)
    {
        $productsId = GetDEDEInfo('DE_ItemEcode').$id;
		MakeOrders($id);
        saveCookie($productsId,$value);
		return $id;
    }
	
    /**
     *  创建一个专有订单编号
     *
     * @return    string
     */
    function MakeOrders($id)
    {
        $OrdersId = 'DLGL'.time().'RN'.mt_rand(100,999);
        deCrypt(saveCookie("OrdersId_".$id,$OrdersId));
        return $OrdersId;
    }

    //解密接口字符串
    function deCrypt($txt)
    {
        $txt = setKey(base64_decode($txt));
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++)
        {
            $tmp .= $txt[$i] ^ $txt[++$i];
        }
        return $tmp;
    }

	//创建加密的_cookie
	function saveCookie($key,$value)
	{
		if(is_array($value))
		{
			$value = enCrypt(enCode($value));
		}
		else
		{
			$value = enCrypt($value);
		}
		setcookie($key,$value,time()+36000,'/');
	}
	
    //加密接口字符
    function enCrypt($txt)
    {
        srand((double)microtime() * 1000000);
        $encrypt_key = md5(rand(0, 32000));
        $ctr = 0;
        $tmp = '';
        for($i = 0; $i < strlen($txt); $i++)
        {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
        }
        return base64_encode(setKey($tmp));
    }

    //串行化数组
    function enCode($array)
    {
        $arrayenc = array();
        foreach($array as $key => $val)
        {
            $arrayenc[] = $key.'='.urlencode($val);
        }
        return implode('&', $arrayenc);
    }

    //处理加密数据
    function setKey($txt)
    {
		$cfg_cookie_encode = GetDEDEInfo('cfg_cookie_encode');
        $encrypt_key = md5(strtolower($cfg_cookie_encode));
        $ctr = 0;
        $tmp = '';
        for($i = 0; $i < strlen($txt); $i++)
        {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
        }
        return $tmp;
    }

    /**
     *  得到订单记录
     *
     * @return    array
     */
    function DEDEShopCar_getItem($orderID)
    {
		$id = str_replace("OrdersId_","",$orderID);
        $Products = array();
        $DE_ItemEcode = GetDEDEInfo('DE_ItemEcode');
        $key = GetDEDEInfo('DE_ItemEcode').$id;
		$vals = $_COOKIE[$key];
		if(preg_match("#".$DE_ItemEcode."#", $key) && preg_match("#[^_0-9a-z]#", $key))
		{
			parse_str(deCrypt($vals), $arrays);
			$values = @array_values($arrays);
			if(!empty($values))
			{
				$Products[$key] = $arrays;
			}
		}
        return $Products[$key];
    }

/**
 *  检查用户名的合法性
 *
 * @access    public
 * @param     string  $uid  用户UID
 * @param     string  $msgtitle  提示标题
 * @param     string  $ckhas  检查是否存在
 * @return    string
 */
function DEDECheckUserID($uid, $msgtitle='用户名', $ckhas=TRUE)
{
//    global $cfg_mb_notallow,$cfg_mb_idmin,$cfg_md_idurl,$cfg_soft_lang,$dsql;
	$cfg_mb_notallow = GetDEDEInfo('cfg_mb_notallow');
	$cfg_mb_idmin = GetDEDEInfo('cfg_mb_idmin');
	$cfg_md_idurl = GetDEDEInfo('cfg_md_idurl');
	$cfg_soft_lang = GetDEDEInfo('cfg_soft_lang');
    if($cfg_mb_notallow != '')
    {
        $nas = explode(',', $cfg_mb_notallow);
        if(in_array($uid, $nas))
        {
            return $msgtitle.'为系统禁止的标识！';
        }
    }
    if($cfg_md_idurl=='Y' && preg_match("/[^a-z0-9]/i",$uid))
    {
        return $msgtitle.'必须由英文字母或数字组成！';
    }

    if($cfg_soft_lang=='utf-8')
    {
        $ck_uid = utf82gb($uid);
    }
    else
    {
        $ck_uid = $uid;
    }

    for($i=0; isset($ck_uid[$i]); $i++)
    {
        if(ord($ck_uid[$i]) > 0x80)
        {
            if(isset($ck_uid[$i+1]) && ord($ck_uid[$i+1])>0x40)
            {
                $i++;
            }
            else
            {
                return $msgtitle.'可能含有乱码，建议你改用英文字母和数字组合！';
            }
        }
        else
        {
            if(preg_match("/[^0-9a-z@\.-]/i",$ck_uid[$i]))
            {
                return $msgtitle.'不能含有 [@]、[.]、[-]以外的特殊符号！';
            }
        }
    }
    if($ckhas)
    {
//        $row = $dsql->GetOne("SELECT * FROM `#@__member` WHERE userid LIKE '$uid' ");
		$DEDEMember = D("DEDEMember");
		$row = $DEDEMember->where("`userid` like '$uid'")->find();
        if(is_array($row)) return $msgtitle."已经存在！";
    }
    return 'ok';
}

    /**
     *  检查用户是否合法
     *
     * @access    public
     * @param     string  $loginuser  登录用户名
     * @param     string  $loginpwd  用户密码
     * @return    string
     */
    function DEDECheckUser(&$loginuser, $loginpwd)
    {
//        global $dsql;

        //检测用户名的合法性
        $rs = DEDECheckUserID($loginuser,'用户名',FALSE);

        //用户名不正确时返回验证错误，原登录名通过引用返回错误提示信息
        if($rs!='ok')
        {
            $loginuser = $rs;
            return '0';
        }

        //matt=10 是管理员关连的前台帐号，为了安全起见，这个帐号只能从后台登录，不能直接从前台登录
//        $row = $dsql->GetOne("SELECT mid,matt,pwd,logintime FROM `#@__member` WHERE userid LIKE '$loginuser' ");
		$DEDEMember = D("DEDEMember");
		$row = $DEDEMember->where("`userid` like '$loginuser'")->find();
        if(is_array($row))
        {
            if(DEDEGetShortPwd($row['pwd']) != DEDEGetEncodePwd($loginpwd))
            {
                return -1;
            }
            else
            {
                //管理员帐号不允许从前台登录
                if($row['matt']==10) {
                    return -2;
                }
                else {
                    DEDEPutLoginInfo($row['mid'], $row['logintime']);
                    return 1;
                }
            }
        }
        else
        {
            return 0;
        }
    }

    /**
     *  把数据库密码转为特定长度
     *  如果数据库密码是明文的，本程序不支持
     *
     * @access    public
     * @param     string
     * @return    string
     */
    function DEDEGetShortPwd($dbpwd)
    {
//        global $cfg_mb_pwdtype;
		$cfg_mb_pwdtype = GetDEDEInfo('cfg_mb_pwdtype');
        if(empty($cfg_mb_pwdtype)) $cfg_mb_pwdtype = '32';
        $dbpwd = trim($dbpwd);
        if(strlen($dbpwd)==16)
        {
            return $dbpwd;
        }
        else
        {
            switch($cfg_mb_pwdtype)
            {
                case 'l16':
                    return substr($dbpwd, 0, 16);
                case 'r16':
                    return substr($dbpwd, 16, 16);
                case 'm16':
                    return substr($dbpwd, 8, 16);
                default:
                    return $dbpwd;
            }
        }
    }

    /**
     *  用户登录
     *  把登录密码转为指定长度md5数据
     *
     * @access    public
     * @param     string  $pwd  需要加密的密码
     * @return    string
     */
    function DEDEGetEncodePwd($pwd)
    {
//        global $cfg_mb_pwdtype;
		$cfg_mb_pwdtype = GetDEDEInfo('cfg_mb_pwdtype');
        if(empty($cfg_mb_pwdtype)) $cfg_mb_pwdtype = '32';
        switch($cfg_mb_pwdtype)
        {
            case 'l16':
                return substr(md5($pwd), 0, 16);
            case 'r16':
                return substr(md5($pwd), 16, 16);
            case 'm16':
                return substr(md5($pwd), 8, 16);
            default:
                return md5($pwd);
        }
    }


    /**
     *  保存用户cookie
     *
     * @access    public
     * @param     string  $uid  用户ID
     * @param     string  $logintime  登录限制时间
     * @return    void
     */
    function DEDEPutLoginInfo($uid, $logintime=0)
    {
//        global $cfg_login_adds, $dsql;
		$cfg_login_adds = GetDEDEInfo('cfg_login_adds');
        //登录增加积分(上一次登录时间必须大于两小时)
		$DEDEMember = D("DEDEMember");
		$row = $DEDEMember->where("`mid` = '$uid'")->find();
        if(time() - $logintime > 7200 && $cfg_login_adds > 0)
        {
//            $dsql->ExecuteNoneQuery("Update `#@__member` set `scores`=`scores`+{$cfg_login_adds} where mid='$uid' ");
			$row['scores'] = $row['scores']+$cfg_login_adds;
        }
        $M_ID = $uid;
        $M_LoginTime = time();
        $loginip = GetIP();
//        $inquery = "UPDATE `#@__member` SET loginip='$loginip',logintime='".$this->M_LoginTime."' WHERE mid='".$uid."'";
		$row['loginip'] = $loginip;
		$row['logintime'] = $M_LoginTime;
		$row = $DEDEMember->save($row);
//        $dsql->ExecuteNoneQuery($inquery);
		$M_KeepTime = 3600 * 24 * 7;
		PutCookie('DedeUserID',$uid,$M_KeepTime);
		PutCookie('DedeLoginTime',$M_LoginTime,$M_KeepTime);
    }



/**
 *  获取用户真实地址
 *
 * @return    string  返回用户ip
 */
if ( ! function_exists('GetIP'))
{
    function GetIP()
    {
        static $realip = NULL;
        if ($realip !== NULL)
        {
            return $realip;
        }
        if (isset($_SERVER))
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第x个非unknown的有效IP字符? */
                foreach ($arr as $ip)
                {
                    $ip = trim($ip);
                    if ($ip != 'unknown')
                    {
                        $realip = $ip;
                        break;
                    }
                }
            }
            elseif (isset($_SERVER['HTTP_CLIENT_IP']))
            {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            }
            else
            {
                if (isset($_SERVER['REMOTE_ADDR']))
                {
                    $realip = $_SERVER['REMOTE_ADDR'];
                }
                else
                {
                    $realip = '0.0.0.0';
                }
            }
        }
        else
        {
            if (getenv('HTTP_X_FORWARDED_FOR'))
            {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            }
            elseif (getenv('HTTP_CLIENT_IP'))
            {
                $realip = getenv('HTTP_CLIENT_IP');
            }
            else
            {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = ! empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realip;
    }
}

/**
 *  设置Cookie记录
 *
 * @param     string  $key    键
 * @param     string  $value  值
 * @param     string  $kptime  保持时间
 * @param     string  $pa     保存路径
 * @return    void
 */
if ( ! function_exists('PutCookie'))
{
    function PutCookie($key, $value, $kptime=0, $pa="/")
    {
//        global $cfg_cookie_encode,$cfg_domain_cookie;
		$cfg_cookie_encode = GetDEDEInfo('cfg_cookie_encode');
		$cfg_domain_cookie = GetDEDEInfo('cfg_domain_cookie');
        setcookie($key, $value, time()+$kptime, $pa,$cfg_domain_cookie);
        setcookie($key.'__ckMd5', substr(md5($cfg_cookie_encode.$value),0,16), time()+$kptime, $pa,$cfg_domain_cookie);
    }
}


/**
 *  删除缓存
 *
 * @access    public
 * @param     string  $prefix  前缀
 * @param     string  $key  键
 * @param     string  $is_memcache  是否为memcache缓存
 * @return    string
 */
if ( ! function_exists('DelCache'))
{
    /* 删缓存 */
    function DelCache($prefix, $key, $is_memcache = TRUE)
    {
//        global $cache_helper_config;
		$cache_helper_config = GetDEDEInfo('cache_helper_config');
		$DEDEDATA = GetDEDEInfo('DEDEDATA');
        $key = md5 ( $key );
        /* 如果启用MC缓存 */
        if (! empty ( $cache_helper_config['memcache'] ) && $cache_helper_config['memcache'] ['is_mc_enable'] === TRUE && $is_memcache === TRUE)
        {
            $mc_path = empty ( $cache_helper_config['memcache'] ['mc'] [substr ( $key, 0, 1 )] ) ? $cache_helper_config['memcache'] ['mc'] ['default'] : $cache_helper_config['memcache'] ['mc'] [substr ( $key, 0, 1 )];
            $mc_path = parse_url ( $mc_path );
            $key = ltrim ( $mc_path ['path'], '/' ) . '_' . $prefix . '_' . $key;
            if (empty ( $GLOBALS ['mc_' . $mc_path ['host']] ))
            {
                $GLOBALS ['mc_' . $mc_path ['host']] = new Memcache ( );
                $GLOBALS ['mc_' . $mc_path ['host']]->connect ( $mc_path ['host'], $mc_path ['port'] );
            }
            return $GLOBALS ['mc_' . $mc_path ['host']]->delete ( $key );
        }
        $key = substr ( $key, 0, 2 ) . '/' . substr ( $key, 2, 2 ) . '/' . substr ( $key, 4, 2 ) . '/' . $key;
//        return @unlink ( DEDEDATA . "/cache/$prefix/$key.php" );
        return @unlink ( $DEDEDATA . "/cache/$prefix/$key.php" );
    }
}


if ( ! function_exists('ShowMsg'))
{
    function ShowMsg($word,$url="",$target="self")
	{
		if(!$url)
			$url = "$target.location='$url';";
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>$word</title><script type=\"text/javascript\" >alert('$word');$url</script>
</head><body></body></html>";
		exit;
	}
}


/**
 *  UTF-8 转GB编码
 *
 * @access    public
 * @param     string  $utfstr  需要转换的字符串
 * @return    string
 */
if ( ! function_exists('utf82gb'))
{
    function utf82gb($utfstr)
    {
        if(function_exists('iconv'))
        {
            return iconv('utf-8','gbk//ignore',$utfstr);
        }
	}
}


/**更改订单号
 */
function API_change_orderID($orderID)
{
	A("MethodService")->_change_orderID();
}



?>