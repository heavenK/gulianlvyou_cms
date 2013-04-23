<?php

class IndexAction extends Action{
	
    public function index() {
		echo "test";
    }
	
    public function ajax_loginsta() {
        $M_ID = GetNum(GetCookie("DedeUserID"));
        $M_LoginTime = GetCookie("DedeLoginTime");
        if(empty($M_ID))
        {
            return false;
        }else{
            $M_ID = intval($M_ID);
			$DEDEMember = D("DEDEMember");
			$member = $DEDEMember->where("`mid` = '$M_ID'")->find();
		}
		echo $lgoinsta->MemberLogin['M_MbType'];
    }
	
    public function ajax_cookie() {
        $M_ID = GetNum(GetCookie("DedeUserID"));
        $M_LoginTime = GetCookie("DedeLoginTime");
        if(empty($M_ID))
        {
            return false;
        }else{
            $M_ID = intval($M_ID);
			$DEDEMember = D("DEDEMember");
			$member = $DEDEMember->where("`mid` = '$M_ID'")->find();
		}
		echo $lgoinsta->MemberLogin['M_MbType'];
    }
	
	
	
	
}
?>