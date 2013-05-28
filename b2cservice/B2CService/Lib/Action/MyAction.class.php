<?php

class MyAction extends CommonMyAction{
	
	function index(){
		$this->assign("mark",'首页');
		$u = A("DEDEInfo")->ajax_loginsta('arrary');
		if(empty($u['face']))
			$u['face']=($u['sex']=='女')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
		$this->assign("user",$u);
		//订单列表
		$Dingdan = D("Dingdan");
		$orderall = $Dingdan->where("`mid` = '$u[mid]' AND `status_system` = '1'")->order("time desc")->limit('0,2')->findall();
		$this->assign("orderall",$orderall);
		$this->display();
	}
	
	
	function orderlist(){
		$this->assign("mark",'我的订单');
		$u = A("DEDEInfo")->ajax_loginsta('arrary');
		if(empty($u['face']))
			$u['face']=($u['sex']=='女')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
		$this->assign("user",$u);
		//订单列表
		$Dingdan = D("Dingdan");
		$orderall = $Dingdan->where("`mid` = '$u[mid]' AND `status_system` = '1'")->order("time desc")->findall();
		$this->assign("orderall",$orderall);
		$this->display();
	}
	
	
	function personinfo(){
		$this->assign("mark",'个人信息管理');
		$u = A("DEDEInfo")->ajax_loginsta('arrary');
		if(empty($u['face']))
			$u['face']=($u['sex']=='女')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
		$this->assign("user",$u);
		$MemberExtand = D("MemberExtand");
		$p = $MemberExtand->where("`mid` = '$u[mid]'")->find();
		$this->assign("extand",$p);
		
		$this->display();
	}
	
	
	function dopostPersoninfo(){
		C('TOKEN_ON',false);
		$data = $_REQUEST;
		$MemberExtand = D("MemberExtand");
		$MemberExtand->mycreate($data);
		$redirect_rul = MY_INDEX.'My/personinfo';
		redirect($redirect_rul);
	}
	
	
	function joinerlist(){
		$this->assign("mark",'常用游客信息');
		$Joiner = D("Joiner");
		$u = A("DEDEInfo")->ajax_loginsta('arrary');
		$joinerall = $Joiner->where("`mid` = '$u[mid]'")->findall();
		$this->assign("joinerall",$joinerall);
		$this->display();
	}
	
	
	function joiner(){
		$this->assign("mark",'常用游客信息');
		$id = $_REQUEST['id'];
		$Joiner = D("Joiner");
		$joiner = $Joiner->where("`id` = '$id'")->find();
		$d = $joiner['datatext'];
//		$d = str_replace('﻿','',$d);//未知原因数据序列化后多3个不可见字符问题，序列化失败解决办法。
		$d = unserialize($d);
//		dump($d);
		$this->assign("joiner",$d);
		$this->display();
	}
	
	
	function dopostjoiner(){
		$data = $_REQUEST;
		$data['datatext'] = serialize($data);
		$Joiner = D("Joiner");
		if(false === $Joiner->mycreate($data)){
			echo 'error';
			exit;
		}
		$redirect_rul = MY_INDEX.'My/joinerlist';
		redirect($redirect_rul);
	}
	
	
	
	
	
	
	
	
	
	
	
	
}
?>