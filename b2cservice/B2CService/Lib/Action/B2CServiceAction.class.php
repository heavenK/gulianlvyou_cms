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
		$where['tid'] = $_REQUEST['source'];
		$where['first'] = 0;
		$DISCUZForumpost = D("DISCUZForumpost");
		$tips = $DISCUZForumpost->where($where)->order('pid desc')->find();
		$DISCUZCommonMember = D("DISCUZCommonMember");
		echo "document.write('<ol>";
		foreach($tips as $v){
			$uc = $DISCUZCommonMember->where("`username` = '$v[authorid]'")->find();
			$face = BBS_URL.'uc_server/avatar.php?uid='.$uc['uid'];
			echo "<li><b><img src=\"".$face."\" width=\"16\" height=\"16\" /></b><h1><a href=\"".BBS_URL."home.php?mod=space&uid=".$uc['uid']."&do=profile&from=space"."\" target=\"_blank\">".$uc['username']."</a>:&nbsp;".$v['message']."</h1></li>";
		}
		echo "</ol>');";
	}
	
	
	
	
	
	
}

?>