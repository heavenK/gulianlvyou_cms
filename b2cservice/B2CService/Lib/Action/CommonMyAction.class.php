<?php

class CommonMyAction extends Action{
	
    protected $loginsta   =  null;
	
    public function _initialize() {
        $M_ID = GetNum(GetCookie("DedeUserID"));
		$cfg_cookie_encode = GetDEDEInfo('cfg_cookie_encode');
		echo "<pre>";
		dump($_COOKIE);
		
		//用户未登录
		$this->loginsta = A("DEDEInfo")->ajax_loginsta('arrary');
//        if (false === $this->loginsta)
//            redirect(ROOT_URL.'member');
		$this->_myinit();	
    }
	
	
    public function _myinit() {	}
	
	
}
?>