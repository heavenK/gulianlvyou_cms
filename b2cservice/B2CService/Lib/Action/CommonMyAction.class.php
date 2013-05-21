<?php

class CommonMyAction extends Action{
	
    protected $loginsta   =  null;
	
    public function _initialize() {
		
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