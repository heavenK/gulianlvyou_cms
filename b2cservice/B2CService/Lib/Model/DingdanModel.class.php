<?php

class DingdanModel extends Model {
	protected $trueTableName = 'b2cservice_member_dingdan';	
   // 自动验证设置 
    protected $_validate = array( 
        array('serverdataID', 'require', 'serverdataID不能为空！', 1,'',1), 
        array('lxr_name', 'require', 'lxr_name不能为空！', 1,'',1), 
        array('lxr_telnum', 'require', 'lxr_telnum不能为空！', 1,'',1), 
        array('lxr_email', 'require', 'lxr_email不能为空！', 1,'',1), 
        array('chengrenshu', 'require', 'chengrenshu不能为空！', 1,'',1), 
    );
	
    // 自动填充设置 
    protected $_auto = array(
        array('mid', 'set_mid', 1,'callback','mid',1),//array('field','填充内容','填充条件','附加规则',[额外参数],[表单数据标记])
        array('time', 'time', 1,'function'),//array('field','填充内容','填充条件','附加规则',[额外参数],[表单数据标记])
        array('status', 'set_status', 1,'callback','status',1),//array('field','填充内容','填充条件','附加规则',[额外参数],[表单数据标记])
        array('status_system', 'set_status_system', 1,'callback','status_system',1),//1正常,-1删除
        array('ertongshu', '0', 1),//1正常,-1删除
    ); 
	
	protected function set_mid($mid) {
		if($mid != '')	
			return $mid;
		else{
			$loginsta = A("MethodService")->ajax_loginsta();
			return $loginsta['mid'];
		}
	}
	
	protected function set_status($status) {
		if($status != '')	
			return $status;
		else
			return '准备中';
	}
	
	protected function set_status_system($status_system) {//1正常,-1删除
		if($status_system != '')	
			return $status_system;
		else
			return 1;
	}


}
?>