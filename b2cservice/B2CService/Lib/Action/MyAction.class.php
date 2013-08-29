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
		//广告
		$tips = $this->_getadstips();
		$this->assign("tips",$tips);
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
		$this->assign("user",$u);
		$joinerall = $Joiner->where("`mid` = '$u[mid]'")->findall();
		$this->assign("joinerall",$joinerall);
		$this->display();
	}
	
	
	function joiner(){
		$this->assign("mark",'常用游客信息');
		$id = $_REQUEST['id'];
		$Joiner = D("Joiner");
		$joiner = $Joiner->where("`id` = '$id'")->find();
		$datatext = unserialize($joiner['datatext']);
		foreach($datatext as $key => $val){
			if($val)
			$joiner[$key] = $val;
		}
		$this->assign("joiner",$joiner);
		$this->display();
	}
	
	
	function dopostjoiner(){
		if(!isChineseName($_REQUEST['name']))
			ShowMsg("请使用中文");
		if(!isIdCard($_REQUEST['sfz_haoma']))
			ShowMsg("身份证号错误");
		$data = $_REQUEST;
		if($data['zhengjiantype'] == '港澳通行证' || $data['zhengjiantype'] == '台湾通行证'){
			$data['txz_type'] = $data['zhengjiantype'];
			$data['txz_haoma'] = $data['zhengjianhaoma'];
		}
		else{
			$data['hz_type'] = $data['zhengjiantype'];
			$data['hz_haoma'] = $data['zhengjianhaoma'];
		}
		$data['datatext'] = serialize($data);
		$Joiner = D("Joiner");
		if(false === $Joiner->mycreate($data)){
			dump($Joiner);
			//ShowMsg("发生错误");
			exit;
		}
		$redirect_rul = MY_INDEX.'My/joinerlist';
		redirect($redirect_rul);
	}
	
	
	function joinerdelete(){
		$id = $_REQUEST['id'];
		$Joiner = D("Joiner");
		if(false === $Joiner->where("`id` = '$id'")->delete()){
			ShowMsg("发生错误");
			exit;
		}
		$redirect_rul = MY_INDEX.'My/joinerlist';
		redirect($redirect_rul);
		
	}
	
	//广告
	function _getadstips(){
		$DEDEArchives = D("DEDEArchives");//文章主表
		$tips = $DEDEArchives->where("`typeid` = '15'")->order('id desc')->findall();
		return $tips;
	}
	
	
	//广告
	function showtips(){
		$tips = $this->_getadstips();
		$this->assign("tips",$tips);
		$this->display('tips_ads');
	}
	
}
?>