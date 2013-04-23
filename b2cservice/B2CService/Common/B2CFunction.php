<?php

function FileGetContents($url){
	$d = file_get_contents($url);
	$d = str_replace('﻿','',$d);//未知原因数据序列化后多3个不可见字符问题，序列化失败解决办法。
	$d = unserialize($d);
	return $d;
}


function GetDEDEInfo($type){
	$loginsta = file_get_contents(ET_URL."apis/info_collector.php?type=".$type);
	return trim($loginsta);
}


function GetCookie($key)
{
	$cfg_cookie_encode = GetDEDEInfo('cfgCookieEncode');
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
		$cfg_cookie_encode = GetDEDEInfo('cfgCookieEncode');
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












?>