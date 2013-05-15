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
		
		dump($where);
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
    public function _checkchanpin($chanpinID) {
		$zituan = FileGetContents(SERVER_INDEX."Server/getzituanbyID/chanpinID/".$chanpinID);
		if('false' != $zituan)
			return $zituan;
		else
			return false;	
		
	}
	
	
	//用户状态
    public function ajax_loginsta() {
        $M_ID = GetNum(GetCookie("DedeUserID"));
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
		if($_REQUEST['id'][$id]){
			$id = $_REQUEST['id'][$id];
			$cus['id'] = $id;
		}
		$cus['dingdanID'] = $_REQUEST['dingdanID'];
		$cus['name'] = $_REQUEST['name'.$id];
		$cus['manorchild'] = $_REQUEST['manorchild'.$id];
		$cus['sex'] = $_REQUEST['sex'.$id];
		$cus['zhengjiantype'] = $_REQUEST['zhengjiantype'.$id];
		$cus['zhengjianhaoma'] = $_REQUEST['zhengjianhaoma'.$id];
		$cus['telnum'] = $_REQUEST['telnum'.$id];
		$cus['pinyin'] = $_REQUEST['pinyin'.$id];
		$cus['birthday'] = $_REQUEST['birthday'.$id];
		$cus['hujidi'] = $_REQUEST['hujidi'.$id];
		$cus['lyzj_type'] = $_REQUEST['lyzj_type'.$id];
		$cus['lyzj_haoma'] = $_REQUEST['lyzj_haoma'.$id];
		$cus['lyzj_qianfariqi'] = $_REQUEST['lyzj_qianfariqi'.$id];
		$cus['lyzj_youxiaoqi'] = $_REQUEST['lyzj_youxiaoqi'.$id];
		$cus['lyzj_qianfadi'] = $_REQUEST['lyzj_qianfadi'.$id];
		if(false !== $DingdanJoiner->mycreate($cus))
			return true;
		else{
			dump($DingdanJoiner);
			return false;	
		}
	}
		
		
	
	
	
	
	
	
	
	
	
	
}
?>