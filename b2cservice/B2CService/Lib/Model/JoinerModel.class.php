<?php

class JoinerModel extends Model {
	protected $trueTableName = 'b2cservice_member_joiner';	
	
   // 自动验证设置 
    protected $_validate = array( 
        array('mid', 'require', 'mid不能为空！', 1,'',1), 
        array('name', 'require', 'name不能为空！', 1,'',1), 
        array('sex', 'require', 'sex不能为空！', 1,'',1), 
    ); 
    // 自动填充设置 
    protected $_auto = array( 
    ); 

	protected $_link = array(
	);


}
?>