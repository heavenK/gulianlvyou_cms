<?php

class MemberExtandModel extends RelationModel {
	protected $trueTableName = 'b2cservice_member_extand';	
	protected $pk = 'mid';	
	
	protected $_link = array(
	);
	
   // 自动验证设置 
    protected $_validate = array( 
        array('mid', 'require', 'mid不能为空！', 1,'',1), 
    ); 
	
	
	

}
?>