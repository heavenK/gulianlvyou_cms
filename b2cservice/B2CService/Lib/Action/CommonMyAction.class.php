<?php

class CommonMyAction extends Action{
	
    public function _initialize() {
		//用户未登录
		$u = A("DEDEInfo")->ajax_loginsta('arrary');
        if (false === $u)
            redirect(SITE_INDEX.'Index/index');
		$this->_myinit();	
    }
	
	
    public function _myinit() {	}
	
	
}
?>