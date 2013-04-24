<?php

class OrderAction extends Action{
	
    public function book1() {
		$zituan = A("MethodService")->_checkchanpin($_REQUEST['chanpinID']);
		if(false === $zituan){
			echo "产品不存在或已经停止销售！！";
			exit;
		}
		$xianlu = unserialize($zituan['xianlulist']['datatext']);
		$zongjia = $zituan['adult_price']*$_REQUEST['chengrenshu']+$zituan['child_price']*$_REQUEST['ertongshu'];
		$this->assign("zongjia",$zongjia);
		$this->assign("zituan",$zituan);
		$this->assign("xianlu",$xianlu);
		$this->display();
	}
	
    public function dopostbook1() {
		$chanpin = A("MethodService")->_checkchanpin($_REQUEST['chanpinID']);
		if(false === $chanpin){
			echo "产品不存在或已经停止销售！！";
			exit;
		}
		//提交到订单
		$rows = $_REQUEST;
		$rows['serverdataID'] = $_REQUEST['chanpinID'];
		$rows['title_copy'] = $chanpin['title_copy'];
		$rows['chufadi_copy'] = $chanpin['xianlulist']['chufadi'];
		$rows['chutuanriqi_copy'] = $chanpin['chutuanriqi'];
		$rows['tuanhao_copy'] = $chanpin['tuanhao'];
		$rows['tianshu_copy'] = $chanpin['xianlulist']['tianshu'];
		$rows['chengrenshu'] = $_REQUEST['chengrenshu'];
		$rows['ertongshu'] = $_REQUEST['ertongshu'];
		$rows['lxr_name'] = $_REQUEST['lxr_name'];
		$rows['lxr_telnum'] = $_REQUEST['lxr_telnum'];
		$rows['lxr_email'] = $_REQUEST['lxr_email'];
		$rows['telservice'] = $_REQUEST['telservice'];
		$rows['orderID'] = MakeOrders($rows['serverdataID']);
		$Dingdan = D("Dingdan");
		if(false !== $Dingdan->mycreate($rows)){
			redirect(SITE_INDEX."Order/book2/orderID/".$rows['orderID']);
		}
		else
		dump($Dingdan);
	}
	
	
    public function book2() {
		$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
		if(!$order){
			echo "订单不存在！！";
			exit;
		}
		$zituan = A("MethodService")->_checkchanpin($order['serverdataID']);
		if(false === $zituan){
			echo "产品不存在或已经停止销售！！";
			exit;
		}
		$order['zongjia'] = $order['chengrenshu']*$zituan['adult_price']+$order['ertongshu']*$order['child_price'];
		$this->assign("order",$order);
		$this->assign("zituan",$zituan);
		$this->assign("xianlu",$xianlu);
		$this->display();
	}
	
	
    public function dopostbook2() {
		//检查dataOM
		$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
		if(!$order){
			echo "订单不存在！！";
			exit;
		}
		$DingdanJoiner = D("DingdanJoiner");
		 // 手动进行令牌验证
		 if (!$DingdanJoiner->autoCheckToken($_REQUEST)){
			 // 令牌验证错误
			echo "token error!!!";
			return false;
		 }
		 else{
			C('TOKEN_ON',false);
		 }
		$DingdanJoiner->startTrans();
		//成人
		for($i = 0; $i < $order['chengrenshu'];$i++){
			if(false === A("MethodService")->_createDingdanJoiner($DingdanJoiner,$_REQUEST,$i)){
				$DingdanJoiner->rollback();
				echo "error!!!";
		$this->display('book1');
				return false;
			}
		}
		//儿童
		for($i = 0; $i < $order['ertongshu'];$i++){
			if(false === A("MethodService")->_createDingdanJoiner($DingdanJoiner,$_REQUEST,$i)){
				$DingdanJoiner->rollback();
				echo "error!!!";
				return false;
			}
		}
		$DingdanJoiner->commit();
		redirect(SITE_INDEX."Order/book3/orderID/".$_REQUEST['orderID']);
		
	}
	
	
    public function book3() {
		//检查dataOM
		$order = A("MethodService")->_getdingdan($_REQUEST['orderID']);
		if(!$order){
			echo "订单不存在！！";
			exit;
		}
		$DingdanJoiner = D("DingdanJoiner");
		$djall = $DingdanJoiner->where("`dingdanID` = '$order[id]'")->findall();
		$this->assign("order",$order);
		$this->assign("tuanyuanall",$djall);
		$this->display();
		
	}
	
	
	
	
	
	
}
?>