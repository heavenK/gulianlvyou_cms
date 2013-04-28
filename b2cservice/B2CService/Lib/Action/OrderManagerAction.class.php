<?php

class OrderManagerAction extends Action{
	
    public function _initialize() {
		$this->_myinit();	
	}
	
    public function _myinit() {
		$this->assign("navposition",'订单管理');
	}
	
    public function index() {
		
		$datalist = A("MethodService")->_listdata("订单",$_REQUEST);		
		$this->assign("page",$datalist['page']);
		$this->assign("chanpin_list",$datalist['datalist']);
		$this->display('dingdanlist');
	}
	
	
	
	
	
	
	
}
?>