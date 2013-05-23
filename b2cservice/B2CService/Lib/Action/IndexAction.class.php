<?php

class IndexAction extends Action{
	
    public function index() {
		$this->toadmin();
		$this->display('login');
		
    }
	
    private function toadmin() {
		if(A("MethodService")->ajax_loginsta()){
			redirect(MY_URL);
		}
    }
	
    public function test() {
		echo 111;
	}
	
	
}
?>