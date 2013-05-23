<?php
if ( ! function_exists('authcode'))
{
	function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
		$ckey_length = 4;
		$key = md5($key ? $key : SITE_URL);
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
		$result = '';
		$box = range(0, 255);
		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}
}


if ( ! function_exists('daddslashes'))
{
	function daddslashes($string) {
		$string=str_replace("'",'"',$string);
		!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
		if(!MAGIC_QUOTES_GPC) {
			if(is_array($string)) {
				foreach($string as $key => $val) {
					$string[$key] = daddslashes($val);
				}
			} else {
				$string = addslashes($string);
			}
		}
		return $string;
	}
}


if ( ! function_exists('gotomail'))
{
	function gotomail($mail) {
		$temp=explode('@',$mail);
		$t=strtolower($temp[1]);
	
		if ($t=='163.com') {
			return 'mail.163.com';
		} else if ($t=='vip.163.com') {
			return 'vip.163.com';
		} else if ($t=='126.com') {
			return 'mail.126.com';
		} else if ($t=='qq.com' || $t=='vip.qq.com' || $t=='foxmail.com') {
			return 'mail.qq.com';
		} else if ($t=='gmail.com') {
			return 'mail.google.com';
		} else if ($t=='sohu.com') {
			return 'mail.sohu.com';
		} else if ($t=='tom.com') {
			return 'mail.tom.com';
		} else if ($t=='vip.sina.com') {
			return 'vip.sina.com';
		} else if ($t=='sina.com.cn' || $t=='sina.com') {
			return 'mail.sina.com.cn';
		} else if ($t=='tom.com') {
			return 'mail.tom.com';
		} else if ($t=='yahoo.com.cn' || $t=='yahoo.cn') {
			return 'mail.cn.yahoo.com';
		} else if ($t=='tom.com') {
			return 'mail.tom.com';
		} else if ($t=='yeah.net') {
			return 'www.yeah.net';
		} else if ($t=='21cn.com') {
			return 'mail.21cn.com';
		} else if ($t=='hotmail.com') {
			return 'www.hotmail.com';
		} else if ($t=='sogou.com') {
			return 'mail.sogou.com';
		} else if ($t=='188.com') {
			return 'www.188.com';
		} else if ($t=='139.com') {
			return 'mail.10086.cn';
		} else if ($t=='189.cn') {
			return 'webmail15.189.cn/webmail';
		} else if ($t=='wo.com.cn') {
			return 'mail.wo.com.cn/smsmail';
		} else if ($t=='139.com') {
			return 'mail.10086.cn';
		} else {
			return '';
		}
	}
}


if ( ! function_exists('real_ip'))
{
	function real_ip(){
		static $realip = NULL;
		if ($realip !== NULL){
			return $realip;
		}
		if (isset($_SERVER)){
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
				$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				/* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
				foreach ($arr as $ip){
					$ip = trim($ip);
					if ($ip != 'unknown'){
						$realip = $ip;
						break;
					}
				}
			}elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
				$realip = $_SERVER['HTTP_CLIENT_IP'];
			} else {
				if (isset($_SERVER['REMOTE_ADDR'])){
					$realip = $_SERVER['REMOTE_ADDR'];
				}else {
					$realip = '0.0.0.0';
				}
			}
		} else {
			if (getenv('HTTP_X_FORWARDED_FOR')){
				$realip = getenv('HTTP_X_FORWARDED_FOR');
			}  elseif (getenv('HTTP_CLIENT_IP')){
				$realip = getenv('HTTP_CLIENT_IP');
			} else  {
				$realip = getenv('REMOTE_ADDR');
			}
		}
		preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
		$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
		return $realip;
	}
}
?>