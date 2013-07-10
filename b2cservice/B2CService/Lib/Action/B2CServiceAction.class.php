<?php

class B2CServiceAction extends Action{
	
	
    public function getorder() {
		$orderID = $_REQUEST['orderID'];
		$Dingdan = D("Dingdan");
		$order = $Dingdan->relation("joinerlist")->where("`orderID` = '$orderID'")->find();
		if(!$order){
			$returndata['msg'] = '订单获取失败！';
			$returndata['error'] = 'true';
			echo serialize($returndata);
		}
		else
			echo serialize($order);
		
	}
	
	
    public function dopost_gexingdingzhi() {
		C('TOKEN_ON',false);
		$Gexingdingzhi = D("Gexingdingzhi");
		$_REQUEST['datatext'] = serialize($_REQUEST);
		$Gexingdingzhi->mycreate($_REQUEST);
		ShowMsg("提交成功，我们会尽量与您联系。",ROOT_URL);
	}
	
    public function faq_about() {
		$where['tid'] = $_REQUEST['tid'];
		$where['first'] = 0;
		$DISCUZForumpost = D("DISCUZForumpost");
		$tips = $DISCUZForumpost->where($where)->order('pid desc')->find();
		echo "document.write('<ol>";
		echo "<li><b><img src=\"/demand/images/pic_01.jpg\" width=\"16\" height=\"16\" /></b><h1><a href=\"#\" target=\"_blank\">丢丢</a>:&nbsp;夏日悠游漓江山夏日悠游漓江山夏日悠游漓江山</h1></li>";
		echo "<li><b><img src=\"/demand/images/pic_01.jpg\" width=\"16\" height=\"16\" /></b><h1><a href=\"#\" target=\"_blank\">丢丢</a>:&nbsp;夏日悠游漓江山夏日悠游漓江山夏日悠游漓江山</h1></li>";
		echo "<li><b><img src=\"/demand/images/pic_01.jpg\" width=\"16\" height=\"16\" /></b><h1><a href=\"#\" target=\"_blank\">丢丢</a>:&nbsp;夏日悠游漓江山夏日悠游漓江山夏日悠游漓江山</h1></li>";
		echo "</ol>');";
	}
	
	
	
	
	
	
}

?>