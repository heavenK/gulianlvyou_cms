<?php

class CommonMyAction extends Action{
	
    protected $loginsta   =  null;
	
    public function _initialize() {
		$this->_myinit();	
        $M_ID = GetNum(GetCookie("DedeUserID"));
		$cfg_cookie_encode = GetDEDEInfo('cfg_cookie_encode');
		//用户未登录
		$this->loginsta = A("DEDEInfo")->ajax_loginsta('arrary');
        if (false === $this->loginsta)
            redirect(ROOT_URL.'member');
		$this->assign("user",$this->loginsta);
    }
	
	
    public function _myinit() {	}
	
	
}
?>