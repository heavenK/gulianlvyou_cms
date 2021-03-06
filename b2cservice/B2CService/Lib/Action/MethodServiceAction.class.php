<?php

class MethodServiceAction extends CommonAction{
	
    public function _initialize() {
		if($_REQUEST['_URL_'][0] == 'MethodService'){
			$this->display('Index:error');
			exit;
		}
	}
	
	//检查产品
    public function _listdata($datatype,$where,$pagenum = 20) {
		
		if($datatype == '订单'){
			$class_name = 'Dingdan';
			//$where['datatype'] = $datatype;
			//处理搜索
			if($where['start_time'] && $where['end_time']){
				$where['time'] = array('between',"'".strtotime($where['start_time']).",".strtotime($where['end_time'])."'");
			}
			$where['title_copy'] = array('like','%'.$where['title_copy'].'%');
			$where['lxr_name'] = array('like','%'.$where['lxr_name'].'%');
			$where['lxr_telnum'] = array('like','%'.$where['lxr_telnum'].'%');
			$where['lxr_email'] = array('like','%'.$where['lxr_email'].'%');
			if($where['remark'])
			$where['remark'] = array('like','%'.$where['remark'].'%');
		}
		
		$where['status_system'] = '1';
		
		if(!$where['status'])
		$where['status'] = array('exp',"is '准备中'");

		$where = A("Method")->_facade($class_name,$where);//过滤搜索项
		$where = A("Method")->_arraytostr_filter($where);//字符串化条件数组
		
		$DataOM = D($class_name);
        import("@.ORG.Page");
        C('PAGE_NUMBERS',10);
		$tempcount = $DataOM->where($where)->findall();
		$count = count($tempcount);
		$p= new Page($count,$pagenum);
		$page = $p->show();
		if(!$order)
			$order = 'time desc';
        $chanpin = $DataOM->where($where)->limit($p->firstRow.','.$p->listRows)->order($order)->select();
		$redata['page'] = $page;
		$redata['datalist'] = $chanpin;
		return $redata;
	}

	
	//检查产品
    public function _checkchanpin($chanpinID,$checkmark = 0) {
		$zituan = FileGetContents(SERVER_INDEX."Server/getzituanbyID/chanpinID/".$chanpinID);
		if($zituan['error']){
			return false;	
		}
		else{
			if($checkmark == 1){
				if($zituan['status'] == '下架' || $zituan['status'] == '截止'){
					return false;
				}
				if( time()-strtotime(jisuanriqi($zituan['chutuanriqi'],$zituan['baomingjiezhi'],'减少')) > 0){
					return false;
				}
			}
			return $zituan;
		}
	}
	
	//检查产品
    public function _checkchanpin_qianzheng($chanpinID,$checkmark = 0) {
		$qianzheng = FileGetContents(SERVER_INDEX."Server/getqianzhengbyID/chanpinID/".$chanpinID);
		if($qianzheng['error']){
			return false;	
		}
		else{
			if($checkmark == 1){
				if($qianzheng['status'] == '下架' || $qianzheng['status'] == '截止'){
					return false;
				}
			}
			return $qianzheng;
			
		}
	}
	
	
	//用户状态
    public function ajax_loginsta($mid='') {
		if(!$mid)
        $M_ID = GetNum(GetCookie("DedeUserID"));
		else
		$M_ID = $mid;
        if(empty($M_ID))
        {
            return false;
        }else{
            $M_ID = intval($M_ID);
			$DEDEMember = D("DEDEMember");
			$member = $DEDEMember->where("`mid` = '$M_ID'")->find();
			if(!$member)
				return false;
			//获得论坛用户uid
			$DISCUZCommonMember = D("DISCUZCommonMember");
			$uc = $DISCUZCommonMember->where("`username` = '$member[userid]'")->find();
			$member['uc'] = $uc;
			return $member;
		}
    }
	
	
	//获得订单
    public function _getdingdan($orderID) {
		$Dingdan = D("Dingdan");
		$dingdan = $Dingdan->where("`orderID` = '$orderID'")->find();
		return $dingdan;
    }
	
	
	//团员生成
    public function _createDingdanJoiner($DingdanJoiner,$_REQUEST,$id) {
		$cus['name'] = $_REQUEST['name'.$id];
		$cus['manorchild'] = $_REQUEST['manorchild'.$id];
		$cus['sex'] = $_REQUEST['sex'.$id];
		$cus['zhengjiantype'] = $_REQUEST['zhengjiantype'.$id];
		$cus['zhengjianhaoma'] = $_REQUEST['zhengjianhaoma'.$id];
		$cus['telnum'] = $_REQUEST['telnum'.$id];
		$cus['pinyin'] = $_REQUEST['pinyin'.$id];
		$cus['birthday'] = $_REQUEST['birthday'.$id];
		$cus['hujidi'] = $_REQUEST['hujidi'.$id];
		$cus['has_lyzj'] = $_REQUEST['has_lyzj'.$id];
		$cus['lyzj_type'] = $_REQUEST['lyzj_type'.$id];
		$cus['lyzj_haoma'] = $_REQUEST['lyzj_haoma'.$id];
		$cus['lyzj_qianfariqi'] = $_REQUEST['lyzj_qianfariqi'.$id];
		$cus['lyzj_youxiaoqi'] = $_REQUEST['lyzj_youxiaoqi'.$id];
		$cus['lyzj_qianfadi'] = $_REQUEST['lyzj_qianfadi'.$id];
		if($_REQUEST['id'])
			$cus['id'] = $_REQUEST['id'][$id];
		else{
			$cus['dingdanID'] = $_REQUEST['dingdanID'];
			$Dingdan = D("Dingdan");	
			$order = $Dingdan->where("`id` = '$cus[dingdanID]'")->find();
			if($cus['manorchild'] == '儿童')
				$cus['price'] = $order['child_price'];
			else
				$cus['price'] = $order['adult_price'];
		}
		if(!isChineseName($cus['name']))
			ShowMsg("请使用中文");
		if(!isIdCard($cus['zhengjianhaoma']))
			ShowMsg("身份证号错误");
		if(false !== $DingdanJoiner->mycreate($cus))
			return true;
		else{
			dump($DingdanJoiner);
			return false;	
		}
	}
	
		
    public function _change_orderNo($orderID) {
		$dingdan = $this->_getdingdan($orderID);
		if(!$dingdan)
			return false;
		$dingdan['orderNo'] = MakeOrders($dingdan['serverdataID']);
		$Dingdan = D("Dingdan");
		if($Dingdan->save($dingdan))
		return $dingdan['orderNo'];
	}
	
	
    public function _check_dingdan_valid($orderID) {
		$order = $this->_getdingdan($orderID);
		if(!$order){
			return false;
		}
		if($order['type'] == '签证')
			$chanpin = $this->_checkchanpin_qianzheng($order['serverdataID']);
		else
			$chanpin = $this->_checkchanpin($order['serverdataID']);
		if(false === $chanpin){
			return false;
		}
		$datalist['order'] = $order;
		$datalist['chanpin'] = $chanpin;
		return $datalist;
	}
	
	
    public function _createUserJoiner($order) {
		$DingdanJoiner = D("DingdanJoiner");
		$joinerall = $DingdanJoiner->where("`dingdanID` = '$order[id]'")->findall();
		$Joiner = D("Joiner");
		foreach($joinerall as $v){
			$j = $v;
			unset($j['id']);
			if($v['has_lyzj']){
				if($v['lyzj_type'] == '因私护照（P）' || $v['lyzj_type'] == '因公护照（I）' || $v['lyzj_type'] == '外交护照（D）'){
					$j['hz_haoma'] = $v['lyzj_haoma'];
					$j['hz_qianfadi'] = $v['lyzj_qianfadi'];
					$j['hz_qianfariqi'] = $v['lyzj_qianfariqi'];
					$j['hz_youxiaoriqi'] = $v['lyzj_youxiaoriqi'];
				}
				if($v['lyzj_type'] == '港澳通行证' || $v['lyzj_type'] == '台湾通行证'){
					$j['txz_haoma'] = $v['lyzj_haoma'];
					$j['txz_qianfadi'] = $v['lyzj_qianfadi'];
					$j['txz_qianfariqi'] = $v['lyzj_qianfariqi'];
					$j['txz_youxiaoriqi'] = $v['lyzj_youxiaoriqi'];
				}
			}
			if($v['zhengjiantype'] == '身份证'){
				$j['sfz_haoma'] = $v['zhengjianhaoma'];
			}
			if($j['hz_haoma'])
				$where['hz_haoma'] = $j['hz_haoma'];
			if($j['hz_haoma'])
				$where['txz_haoma'] = $j['txz_haoma'];
			if($j['sfz_haoma'])
				$where['sfz_haoma'] = $j['sfz_haoma'];
			$ishas = $Joiner->where($where)->find();
			if(!$ishas){
				$j['mid'] = $order['mid'];
				$j['chushengriqi'] = $v['birthday'];
				$j['datatext'] =  serialize($j);
				if(!$Joiner->mycreate($j)){
					dump($Joiner);
					echo 'error';
					exit;
				}
			}
		}
		
	}
	
	
    public function _change_order_tempstatus($orderID,$status) {
		$order = $this->_getdingdan($orderID);
		if(!$order){
			return false;
		}
		$Dingdan = D("Dingdan");
		$order['status_temp'] = $status;
		$dingdan = $Dingdan->where("`orderID` = '$order[orderID]'")->save($order);
		return $dingdan;
	}
	
	
	
	
	
	
}
?>