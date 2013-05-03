<?php

class DEDEInfoAction extends Action{
	//检查用户登录
    public function ajax_loginsta($returntype) {
		$u = A("MethodService")->ajax_loginsta();
		if($returntype == 'arrary')
			return $u;
		$u = json_encode($u);
		echo  $_GET['jsoncallback'].'('.$u.')';
    }
	
	public function ajax_login(){
		
	$cfg_cookie_encode = GetDEDEInfo('cfg_cookie_encode');
		dump($cfg_cookie_encode);
//		$userid = $_REQUEST['uname'];
//		$pwd = $_REQUEST['password'];
		$userid = 'aaa';
		$pwd = 1;
//        if(DEDECheckUserID($userid,'',false)!='ok')
//        {
//			$res = json_encode("你输入的用户名不合法！");
//			echo  $_GET['jsoncallback'].'('.$res.')';
//            exit();
//        }
//        if($pwd=='')
//        {
//			$res = json_encode("密码不能为空！");
//			echo  $_GET['jsoncallback'].'('.$res.')';
//            exit();
//        }

        //检查帐号
        $rs = DEDECheckUser($userid,$pwd);  
		$DEDEMember = D("DEDEMember");
		//require ROOT_URL.'uc_client/client.php';
//		require(dirname(__FILE__).'/../../../../uc_client/client.php');
		import("uc_client.client",dirname(__FILE__).'/../../../../',".php");
        #api{{
        if(1)
        {
            //检查帐号
            list($uid, $username, $password, $email) = uc_user_login($userid, $pwd);
			
			
					dump($userid);
					dump($pwd);
					dump($uid);
					dump($username);
					dump($password);
					dump($email);
			exit;
            if($uid > 0) {
                $password = md5($password);
                //当UC存在用户,而CMS不存在时,就注册一个    
                if(!$rs) {
                    //会员的默认金币
//                    $row = $dsql->GetOne("SELECT `money`,`scores` FROM `".DATABASE_PREFIX."arcrank` WHERE `rank`='10' ");
					$row = $DEDEMember->execute("SELECT `money`,`scores` FROM `".DATABASE_PREFIX."arcrank` WHERE `rank`='10' ");
                    $scores = is_array($row) ? $row['scores'] : 0;
                    $money = is_array($row) ? $row['money'] : 0;
                    $logintime = $jointime = time();
                    $loginip = $joinip = GetIP();
                    $res = $DEDEMember->execute("INSERT INTO ".DATABASE_PREFIX."member SET `mtype`='个人',`userid`='$username',`pwd`='$password',`uname`='$username',`sex`='男' ,`rank`='10',`money`='$money', `email`='$email', `scores`='$scores', `matt`='0', `face`='',`safequestion`='0',`safeanswer`='', `jointime`='$jointime',`joinip`='$joinip',`logintime`='$logintime',`loginip`='$loginip';");
//                    $res = $dsql->ExecuteNoneQuery("INSERT INTO ".DATABASE_PREFIX."member SET `mtype`='个人',`userid`='$username',`pwd`='$password',`uname`='$username',`sex`='男' ,`rank`='10',`money`='$money', `email`='$email', `scores`='$scores', `matt`='0', `face`='',`safequestion`='0',`safeanswer`='', `jointime`='$jointime',`joinip`='$joinip',`logintime`='$logintime',`loginip`='$loginip';");
                    if($res) {
                       // $mid = $dsql->GetLastID();
						$mid = mysql_insert_id();
                        $data = array
                        (
                        0 => "INSERT INTO `".DATABASE_PREFIX."member_person` SET `mid`='$mid', `onlynet`='1', `sex`='男', `uname`='$username', `qq`='', `msn`='', `tel`='', `mobile`='', `place`='', `oldplace`='0' ,
                                 `birthday`='1980-01-01', `star`='1', `income`='0', `education`='0', `height`='160', `bodytype`='0', `blood`='0', `vocation`='0', `smoke`='0', `marital`='0', `house`='0',
                       `drink`='0', `datingtype`='0', `language`='', `nature`='', `lovemsg`='', `address`='',`uptime`='0';",
                        1 => "INSERT INTO `".DATABASE_PREFIX."member_tj` SET `mid`='$mid',`article`='0',`album`='0',`archives`='0',`homecount`='0',`pagecount`='0',`feedback`='0',`friend`='0',`stow`='0';",
                        2 => "INSERT INTO `".DATABASE_PREFIX."member_space` SET `mid`='$mid',`pagesize`='10',`matt`='0',`spacename`='{$uname}的空间',`spacelogo`='',`spacestyle`='person', `sign`='',`spacenews`='';",
                        3 => "INSERT INTO `".DATABASE_PREFIX."member_flink` SET `mid`='$mid', `title`='织梦内容管理系统', `url`='http://www.dedecms.com';"
                        );                        
//                        foreach($data as $val) $dsql->ExecuteNoneQuery($val);
                        foreach($data as $val) $DEDEMember->execute($val);
                    }
                }
                $rs = 1;
//                $row = $dsql->GetOne("SELECT `mid`, `pwd` FROM ".DATABASE_PREFIX."member WHERE `userid`='$username'");
				$row = $DEDEMember->where("`userid`='$username'")->find();
                if(isset($row['mid']))
                {
                    DEDEPutLoginInfo($row['mid']);
//                    if($password!=$row['pwd']) $dsql->ExecuteNoneQuery("UPDATE ".DATABASE_PREFIX."member SET `pwd`='$password' WHERE mid='$row[mid]'");
                    if($password!=$row['pwd']){
						$row['pwd'] = $password;
						$row = $DEDEMember->save($row);
					}
                }
                //生成同步登录的代码
                $ucsynlogin = uc_user_synlogin($uid);
            } else if($uid == -1) {
                //当UC不存在该用而CMS存在,就注册一个.
                if($rs) {
//                    $row = $dsql->GetOne("SELECT `email` FROM ".DATABASE_PREFIX."member WHERE userid='$userid'"); 
					$row = $DEDEMember->where("`userid`='$userid'")->find();
                    $uid = uc_user_register($userid, $pwd, $row['email']);
                    if($uid > 0) $ucsynlogin = uc_user_synlogin($uid);
                } else {
                    $rs = -1;
                }
            } else {
                $rs = -1;
            }
        }
        #/aip}}        
					dump("1111");
        if($rs==0)
        {
            ShowMsg("用户名不存在！", "-1", 0, 2000);
            exit();
        }
        else if($rs==-1) {
            ShowMsg("密码错误！", "-1", 0, 2000);
            exit();
        }
        else if($rs==-2) {
            ShowMsg("管理员帐号不允许从前台登录！", "-1", 0, 2000);
            exit();
        }
        else
        {
			$row = $DEDEMember->where("`userid`='$userid'")->find();
            // 清除会员缓存
            DelCache($row['mid']);
			//DelCache($cfg_ml->M_ID);
            if(empty($gourl) || preg_match("#action|_do#i", $gourl))
            {
                ShowMsg("成功登录，5秒钟后转向系统主页...","index.php",0,2000);
            }
            else
            {
                $gourl = str_replace('^','&',$gourl);
                ShowMsg("成功登录，现在转向指定页面...",$gourl,0,2000);
            }
            exit();
        }
		
		
		
	}
	
	
	
	
	

}
?>